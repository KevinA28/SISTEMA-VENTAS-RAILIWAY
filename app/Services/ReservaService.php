<?php
// =====================================================================
// ARCHIVO: ReservaService.php
// UBICACIÓN: app/Services/ReservaService.php
// =====================================================================

namespace App\Services;

use App\Models\Cliente;
use App\Models\EstadoReserva;
use App\Models\HistorialEstado;
use App\Models\MetodoPago;
use App\Models\Reserva;
use App\Services\Integrations\MailService;
use App\Services\Integrations\PdfService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReservaService
{
    public function __construct(
        private PdfService  $pdfService,
        private MailService $mailService,
    ) {}

    public function crear(array $datos): Reserva
    {
        return DB::transaction(function () use ($datos) {

            // ── 1. Resolver o crear cliente ───────────────────────
            $clienteId = $datos['cliente_id'] ?? null;

            if (!$clienteId) {
                $cliente = Cliente::firstOrCreate(
                    ['telefono' => $datos['titular_telefono']],
                    [
                        'nombre_completo'   => $datos['titular_nombre'],
                        'email'             => $datos['titular_email']             ?? null,
                        'tipo_documento'    => $datos['titular_tipo_documento']    ?? null,
                        'numero_documento'  => $datos['titular_numero_documento']  ?? null,
                        'fecha_nacimiento'  => $datos['titular_fecha_nacimiento']  ?? null,
                        'genero'            => $datos['titular_genero']            ?? null,
                        'nacionalidad'      => $datos['titular_nacionalidad']      ?? 'Peruana',
                        'telefono2'         => $datos['titular_telefono2']         ?? null,
                    ]
                );
                $clienteId = $cliente->id;
            }

            // ── 2. Resolver estado inicial ────────────────────────
            $mapaEstados = [
                'mitad_pago' => 'mitad_pago',
                'pagado'     => 'pagado',
                'cancelada'  => 'cancelada',
            ];
            $nombreEstado  = $mapaEstados[$datos['estado_inicial']] ?? 'mitad_pago';
            $estadoInicial = EstadoReserva::where('nombre', $nombreEstado)->firstOrFail();

            // ── 3. Calcular precio total ───────────────────────────
            $precioTotal = (float) $datos['precio_tour']
                         * ((int) $datos['cantidad_adultos'] + (int) $datos['cantidad_ninos']);

            // ── 4. Crear la reserva ───────────────────────────────
            $reserva = Reserva::create([
                'codigo_reserva'                     => Reserva::generarCodigo(),
                'cliente_id'                         => $clienteId,
                'fecha_tour_id'                      => null, // entrada manual, no usa FechaTour
                'estado_id'                          => $estadoInicial->id,
                'usuario_admin_id'                   => Auth::id(),
                'cantidad_adultos'                   => $datos['cantidad_adultos'],
                'cantidad_ninos'                     => $datos['cantidad_ninos'],
                'precio_total'                       => $precioTotal,
                'monto_pagado'                       => $datos['monto_pagado_inicial'],
                'canal_contacto'                     => $datos['canal_contacto'],
                'ciudad_procedencia'                 => $datos['ciudad_procedencia'],
                'tipo_comprobante'                   => $datos['tipo_comprobante'],
                'ruc_factura'                        => $datos['ruc_factura']         ?? null,
                'razon_social'                       => $datos['razon_social']        ?? null,
                'punto_encuentro'                    => $datos['punto_encuentro']     ?? null,
                'hora_recojo'                        => $datos['hora_recojo']         ?? null,
                'alergias_titular'                   => $datos['titular_tiene_alergias'] === 'si'
                                                        ? ($datos['titular_alergias_detalle'] ?? null)
                                                        : null,
                'politica_descripcion'               => $datos['politica_descripcion'] ?? null,
                'politica_tipo'                      => $datos['politica_tipo'] ?? null,
                'restricciones_alimentarias_titular' => $datos['titular_restricciones'] ?? null,
                'observaciones'                      => $datos['observaciones']       ?? null,
            ]);

            // ── 5. Crear pasajero titular ─────────────────────────
            $reserva->pasajeros()->create([
                'nombre_completo'  => $datos['titular_nombre'],
                'tipo'             => 'adulto',
                'tipo_documento'   => $datos['titular_tipo_documento']   ?? null,
                'numero_documento' => $datos['titular_numero_documento'] ?? null,
                'edad'             => null,
                'es_titular'       => true,
            ]);

            // ── 6. Crear pasajeros adicionales ────────────────────
            foreach ($datos['pasajeros'] ?? [] as $p) {
                $reserva->pasajeros()->create([
                    'nombre_completo'  => $p['nombre_completo'],
                    'tipo'             => $p['tipo']             ?? 'adulto',
                    'tipo_documento'   => $p['tipo_documento']   ?? null,
                    'numero_documento' => $p['numero_documento'] ?? null,
                    'edad'             => $p['edad']             ?? null,
                    'es_titular'       => false,
                ]);
            }

            // ── 7. Resolver método de pago ────────────────────────
            $metodoPago = MetodoPago::where('nombre', $datos['metodo_pago'])->firstOrFail();

            // ── 8. Guardar baucher si existe ──────────────────────
            $rutaBaucher = null;
            if (!empty($datos['archivo_baucher']) && $datos['archivo_baucher'] instanceof UploadedFile) {
                $rutaBaucher = $datos['archivo_baucher']->store('baucherss', 'public');
            }

            // ── 9. Registrar el pago ──────────────────────────────
            $reserva->pagos()->create([
                'metodo_pago_id'    => $metodoPago->id,
                'registrado_por'    => Auth::id(),
                'monto'             => $datos['monto_pagado_inicial'],
                'numero_operacion'  => $datos['numero_operacion'] ?? null,
                'archivo_baucher'   => $rutaBaucher,
                'tipo_pago'         => $datos['tipo_pago'],
                'estado_validacion' => 'pendiente',
                'fecha_pago'        => $datos['fecha_pago'],
            ]);

            // ── 10. Historial de estado inicial ───────────────────
            HistorialEstado::create([
                'reserva_id'      => $reserva->id,
                'estado_nuevo_id' => $estadoInicial->id,
                'cambiado_por'    => Auth::id(),
                'motivo'          => 'Reserva creada — canal: ' . $datos['canal_contacto'],
                'fecha_cambio'    => now(),
            ]);

            return $reserva;
        });
    }

    public function cambiarEstado(Reserva $reserva, int $nuevoEstadoId, ?string $motivo = null): void
    {
        $estadoAnterior = $reserva->estado_id;

        $reserva->update(['estado_id' => $nuevoEstadoId]);

        HistorialEstado::create([
            'reserva_id'         => $reserva->id,
            'estado_anterior_id' => $estadoAnterior,
            'estado_nuevo_id'    => $nuevoEstadoId,
            'cambiado_por'       => Auth::id(),
            'motivo'             => $motivo,
            'fecha_cambio'       => now(),
        ]);

        $estadoNuevo = EstadoReserva::find($nuevoEstadoId);
        if ($estadoNuevo && $estadoNuevo->nombre === 'confirmada') {
            $reserva->load(['cliente', 'fechaTour.tour', 'pasajeros']);
            $pdfPath = $this->pdfService->generarConfirmacion($reserva);
            $this->mailService->enviarConfirmacion($reserva, $pdfPath);
        }
    }

    public function cargarDetalle(Reserva $reserva): Reserva
    {
        $reserva->load([
            'cliente',
            'fechaTour.tour',
            'estado',
            'pasajeros.salud',
            'pagos.metodoPago',
            'logistica',
            'historialEstados.estadoAnterior',
            'historialEstados.estadoNuevo',
            'historialEstados.cambiadorPor',
            'comprobantes',
        ]);

        return $reserva;
    }
}