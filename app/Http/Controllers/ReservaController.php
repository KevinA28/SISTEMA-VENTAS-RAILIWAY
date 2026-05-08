<?php
// =====================================================================
// ARCHIVO: ReservaController.php
// UBICACIÓN: app/Http/Controllers/ReservaController.php
// =====================================================================

namespace App\Http\Controllers;

use App\Exports\ReservasExport;
use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\EstadoReserva;
use App\Models\FechaTour;
use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function __construct(private ReservaService $reservaService) {}

    public function index(Request $request)
    {
        $query = Reserva::with(['cliente', 'fechaTour.tour', 'estado'])
                        ->withSum('pagos', 'monto')
                        ->latest();

        if ($request->filled('estado')) {
            $query->where('estado_id', $request->estado);
        }
        if ($request->filled('canal')) {
            $query->where('canal_contacto', $request->canal);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo_reserva', 'like', '%' . $buscar . '%')
                  ->orWhere('nombre_tour',  'like', '%' . $buscar . '%')
                  ->orWhereHas('cliente', function ($c) use ($buscar) {
                      $c->where('nombre_completo',   'like', '%' . $buscar . '%')
                        ->orWhere('numero_documento', 'like', '%' . $buscar . '%')
                        ->orWhere('telefono',         'like', '%' . $buscar . '%')
                        ->orWhere('telefono2',        'like', '%' . $buscar . '%')
                        ->orWhere('email',            'like', '%' . $buscar . '%');
                  });
            });
        }

        $reservas = $query->paginate(20)->withQueryString();
        $estados  = EstadoReserva::all();

        return view('reservas.index', compact('reservas', 'estados'));
    }

    public function create()
    {
        $fechas  = FechaTour::with('tour')
            ->where('estado', 'disponible')
            ->where('fecha', '>=', now())
            ->orderBy('fecha')
            ->get();
        $estados = EstadoReserva::all();

        return view('reservas.create', compact('fechas', 'estados'));
    }

    public function store(StoreReservaRequest $request)
    {
        try {
            $this->reservaService->crear($request->validated());
            return redirect()->route('reservas.index')
                ->with('success', 'Reserva creada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al crear reserva', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
                'input'   => $request->except(['archivo_baucher']),
            ]);
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage()
                    . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')');
        }
    }

    public function show(Reserva $reserva)
    {
        $reserva = $this->reservaService->cargarDetalle($reserva);
        $estados = EstadoReserva::all();

        return view('reservas.show', compact('reserva', 'estados'));
    }

    public function edit(Reserva $reserva)
    {
        $reserva = $this->reservaService->cargarDetalle($reserva);
        $fechas  = FechaTour::with('tour')
            ->where('estado', 'disponible')
            ->where('fecha', '>=', now())
            ->orderBy('fecha')
            ->get();
        $estados = EstadoReserva::all();

        return view('reservas.edit', compact('reserva', 'fechas', 'estados'));
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        try {
            $this->reservaService->actualizar($reserva, $request->validated());
            return redirect()->route('reservas.show', $reserva)
                ->with('success', 'Reserva actualizada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar reserva', [
                'message' => $e->getMessage(),
                'reserva' => $reserva->id,
            ]);
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado_id' => 'required|exists:estados_reserva,id',
            'motivo'    => 'nullable|string|max:500',
        ]);

        $this->reservaService->cambiarEstado($reserva, $request->estado_id, $request->motivo);

        return back()->with('success', 'Estado actualizado.');
    }

    public function registrarPago(Request $request, Reserva $reserva)
    {
        $request->validate([
            'solo_estado'      => 'nullable|in:1,0,true,false',
            'metodo_pago'      => 'nullable|string',
            'monto'            => 'nullable|numeric|min:0.01',
            'numero_operacion' => 'nullable|string|max:100',
            'archivo_baucher'  => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',
        ]);

        try {
            \DB::beginTransaction();

            $estadoPagado = \App\Models\EstadoReserva::where('nombre', 'pagado')->first();
            if (!$estadoPagado) {
                return response()->json(['ok' => false, 'message' => 'Estado "pagado" no encontrado.'], 422);
            }

            $soloEstado = filter_var($request->input('solo_estado', false), FILTER_VALIDATE_BOOLEAN);

            if (!$soloEstado && $request->filled('metodo_pago') && $request->filled('monto')) {
                $metodoPago = \App\Models\MetodoPago::where('clave', $request->metodo_pago)
                                ->orWhere('nombre', $request->metodo_pago)
                                ->first();

                if (!$metodoPago) {
                    return response()->json(['ok' => false, 'message' => 'Método de pago no válido.'], 422);
                }

                $rutaBaucher = null;
                if ($request->hasFile('archivo_baucher')) {
                    $rutaBaucher = $request->file('archivo_baucher')->store('baucherss', 'public');
                }

                $reserva->pagos()->create([
                    'metodo_pago_id'    => $metodoPago->id,
                    'registrado_por'    => \Auth::id(),
                    'monto'             => (float) $request->monto,
                    'numero_operacion'  => $request->numero_operacion,
                    'archivo_baucher'   => $rutaBaucher,
                    'tipo_pago'         => 'saldo',
                    'estado_validacion' => 'pendiente',
                    'fecha_pago'        => now()->toDateString(),
                ]);
            }

            $nuevoMontoPagado = max(
                $reserva->pagos()->sum('monto'),
                (float) $reserva->precio_total
            );

            $reserva->update([
                'estado_id'    => $estadoPagado->id,
                'monto_pagado' => $nuevoMontoPagado,
            ]);

            \App\Models\HistorialEstado::create([
                'reserva_id'         => $reserva->id,
                'estado_anterior_id' => $reserva->getOriginal('estado_id'),
                'estado_nuevo_id'    => $estadoPagado->id,
                'cambiado_por'       => \Auth::id(),
                'motivo'             => $soloEstado
                    ? 'Pago completado al 100% (sin nuevo pago registrado)'
                    : 'Pago completado al 100% — nuevo abono registrado desde modal',
                'fecha_cambio'       => now(),
            ]);

            \DB::commit();

            return response()->json([
                'ok'           => true,
                'message'      => 'Pago completado correctamente.',
                'monto_pagado' => $nuevoMontoPagado,
                'precio_total' => (float) $reserva->precio_total,
                'pct'          => 100,
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error registrarPago', ['msg' => $e->getMessage(), 'reserva' => $reserva->id]);
            return response()->json(['ok' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // ── EXPORTAR EXCEL — sin dependencias externas ────────────────────
    public function exportar(Request $request)
    {
        $filtros = $request->only(['estado', 'canal', 'fecha_desde', 'fecha_hasta', 'buscar']);
        return (new ReservasExport($filtros))->download();
    }

    // ── REENVIAR NOTIFICACIÓN ─────────────────────────────────────────
    public function reenviarNotificacion(Request $request, Reserva $reserva)
    {
        $request->validate([
            'canal' => 'required|in:whatsapp,email,ambos',
        ]);

        $reserva = $this->reservaService->cargarDetalle($reserva);

        try {
            $pdfPath = null;
            try {
                $pdfPath = app(\App\Services\Integrations\PdfService::class)
                    ->generarConfirmacion($reserva);
            } catch (\Exception $e) {
                \Log::warning('reenviarNotificacion: PDF no generado', ['error' => $e->getMessage()]);
            }

            if (in_array($request->canal, ['whatsapp', 'ambos'])) {
                app(\App\Services\Integrations\WhatsAppService::class)
                    ->enviarConfirmacionReserva($reserva);
            }
            if (in_array($request->canal, ['email', 'ambos'])) {
                app(\App\Services\Integrations\MailService::class)
                    ->enviarConfirmacion($reserva, $pdfPath);
            }

            return back()->with('success', 'Notificación reenviada correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al reenviar: ' . $e->getMessage());
        }
    }
}