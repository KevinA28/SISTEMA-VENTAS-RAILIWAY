<?php
// =====================================================================
// ARCHIVO: ClienteController.php
// UBICACIÓN: app/Http/Controllers/ClienteController.php
// =====================================================================

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Models\Cliente;
use App\Services\ClienteService;
use App\Services\Integrations\ReniecService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteService $clienteService,
        private ReniecService  $reniecService,
    ) {}

    public function index(Request $request)
{
    $clientes = Cliente::when($request->buscar, function ($q, $buscar) {
            $q->where('nombre_completo', 'like', "%$buscar%")
              ->orWhere('numero_documento', 'like', "%$buscar%")
              ->orWhere('telefono', 'like', "%$buscar%")
              ->orWhere('email', 'like', "%$buscar%");
        })
        ->withCount('reservas')
        ->withSum('reservas', 'precio_total')
        ->orderByDesc('reservas_count')
        ->paginate(25);

    return view('clientes.index', compact('clientes'));
}
    public function create()
    {
        return view('clientes.create');
    }

    public function store(StoreReservaRequest $request)
{
    try {
        $datos   = $request->validated();
        $reserva = $this->reservaService->crear($datos);

        // ✅ Preparar la respuesta
        $redirect = redirect()->route('reservas.index')
            ->with('success', 'Reserva creada correctamente.');

        // ✅ Enviar respuesta al navegador AHORA y continuar en segundo plano
        ob_end_clean();
        header('Connection: close');
        header('Content-Length: 0');
        flush();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request(); // ← libera el navegador en PHP-FPM
        }

        // ✅ Esto corre DESPUÉS de que el navegador ya fue redirigido
        $this->reservaService->enviarNotificacionesCreacion($reserva, $datos);

        return $redirect;

    } catch (\Exception $e) {
        \Log::error('Error al crear reserva', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'input'   => $request->except(['archivo_baucher']),
        ]);
        return back()
            ->withInput()
            ->with('error', 'Error: ' . $e->getMessage()
                . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')');
    }
}
    public function show(Cliente $cliente)
    {
        $cliente = $this->clienteService->cargarDetalle($cliente);

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre_completo'   => 'required|string|max:200',
            'email'             => 'nullable|email',
            'telefono_whatsapp' => 'nullable|string|max:20',
        ]);

        $this->clienteService->actualizar($cliente, $request->validated());

        return redirect()->route('clientes.show', $cliente)
            ->with('success', 'Cliente actualizado.');
    }

    /**
     * AJAX: busca en BD local primero, si no consulta RENIEC.
     * Usado en el formulario de nueva reserva para autocompletar.
     */
    public function buscarDocumento(Request $request)
    {
        $resultado = $this->clienteService->buscarOConsultarDocumento(
            $request->numero,
            $request->tipo ?? 'DNI'
        );

        return response()->json($resultado);
    }

    // =========================================================================
    // AJAX — SUNAT / RENIEC
    // Rutas en web.php (fuera del grupo auth):
    //   Route::get('/api/buscar-dni/{dni}', [ClienteController::class, 'buscarDni']);
    //   Route::get('/api/buscar-ruc/{ruc}', [ClienteController::class, 'buscarRuc']);
    // =========================================================================

    /**
     * Buscar persona por DNI.
     * Flujo: BD local → ReniecService (apis.net.pe)
     * GET /api/buscar-dni/{dni}
     */
    public function buscarDni(string $dni)
    {
        if (!preg_match('/^\d{8}$/', $dni)) {
            return response()->json([
                'success' => false,
                'error'   => 'El DNI debe tener exactamente 8 dígitos.',
            ], 422);
        }

        // 1 — Buscar en BD local (no gasta consultas API)
        $cliente = Cliente::where('numero_documento', $dni)
            ->where('tipo_documento', 'DNI')
            ->first();

        if ($cliente) {
            return response()->json([
                'success'    => true,
                'nombre'     => $cliente->nombre_completo,
                'sexo'       => $cliente->genero ?? '',
                'cliente_id' => $cliente->id,
                'fuente'     => 'local',
            ]);
        }

        // 2 — Consultar RENIEC vía ReniecService (apis.net.pe)
        try {
            $datos = $this->reniecService->consultarDni($dni);

            if (!$datos || empty($datos['nombre_completo'])) {
                return response()->json([
                    'success' => false,
                    'error'   => 'DNI no encontrado en RENIEC.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'nombre'  => strtoupper($datos['nombre_completo']),
                'fuente'  => 'reniec',
            ]);

        } catch (\Exception $e) {
            Log::error('buscarDni error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Error al consultar RENIEC. Ingrésalo manualmente.',
            ], 500);
        }
    }

    /**
     * Buscar empresa por RUC.
     * Flujo: ReniecService (apis.net.pe/sunat)
     * GET /api/buscar-ruc/{ruc}
     */
    public function buscarRuc(string $ruc)
    {
        if (!preg_match('/^\d{11}$/', $ruc)) {
            return response()->json([
                'success' => false,
                'error'   => 'El RUC debe tener exactamente 11 dígitos.',
            ], 422);
        }

        try {
            $datos = $this->reniecService->consultarRuc($ruc);

            if (!$datos || empty($datos['nombre_completo'])) {
                return response()->json([
                    'success' => false,
                    'error'   => 'RUC no encontrado en SUNAT.',
                ], 404);
            }

            return response()->json([
                'success'      => true,
                'razon_social' => strtoupper($datos['nombre_completo']),
                'direccion'    => $datos['direccion'] ?? '',
                'fuente'       => 'sunat',
            ]);

        } catch (\Exception $e) {
            Log::error('buscarRuc error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error'   => 'Error al consultar SUNAT. Ingrésalo manualmente.',
            ], 500);
        }
    }
}