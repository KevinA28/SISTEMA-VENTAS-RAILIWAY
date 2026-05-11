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
use App\Models\Pasajero;
use App\Models\Reserva;
use App\Services\Integrations\MailService;
use App\Services\Integrations\PdfService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\Integrations\WhatsAppService;

class ReservaService
{
    public function __construct(
        private PdfService      $pdfService,
        private MailService     $mailService,
        private WhatsAppService $whatsAppService,
    ) {}

    // ══════════════════════════════════════════════════════════════════
    // CREAR
    // ══════════════════════════════════════════════════════════════════
    public function crear(array $datos): Reserva
    {
        $reserva = DB::transaction(function () use ($datos) {

            // ── 1. Resolver o crear cliente ───────────────────────
            $clienteId = $datos['cliente_id'] ?? null;

            if (!$clienteId) {
                // Normalizar teléfono — quitar espacios y caracteres no numéricos
                $telefonoNormalizado = preg_replace('/\D/', '', $datos['titular_telefono'] ?? '');

                $cliente = Cliente::where('telefono', $telefonoNormalizado)->first();

                if ($cliente) {
                    // Cliente existente — actualizar datos si vienen nuevos
                    $cliente->update([
                        'nombre_completo'       => $datos['titular_nombre']             ?? $cliente->nombre_completo,
                        'email'                 => $datos['titular_email']              ?? $cliente->email,
                        'tipo_documento'        => $datos['titular_tipo_documento']     ?? $cliente->tipo_documento,
                        'numero_documento'      => $datos['titular_numero_documento']   ?? $cliente->numero_documento,
                        'fecha_nacimiento'      => $datos['titular_fecha_nacimiento']   ?? $cliente->fecha_nacimiento,
                        'genero'                => $datos['titular_genero']             ?? $cliente->genero,
                        'nacionalidad'          => $datos['titular_nacionalidad']       ?? $cliente->nacionalidad,
                        'telefono2'             => $datos['titular_telefono2']          ?? $cliente->telefono2,
                        'emergencia_nombre'     => $datos['emergencia_nombre']          ?? $cliente->emergencia_nombre,
                        'emergencia_parentesco' => $datos['emergencia_parentesco_manual']
                                                    ?? ($datos['emergencia_parentesco'] ?? $cliente->emergencia_parentesco),
                        'emergencia_telefono'   => $datos['emergencia_telefono']        ?? $cliente->emergencia_telefono,
                    ]);
                } else {
                    // Cliente nuevo — crear
                    $cliente = Cliente::create([
                        'telefono'              => $telefonoNormalizado,
                        'nombre_completo'       => $datos['titular_nombre'],
                        'email'                 => $datos['titular_email']             ?? null,
                        'tipo_documento'        => $datos['titular_tipo_documento']    ?? null,
                        'numero_documento'      => $datos['titular_numero_documento']  ?? null,
                        'fecha_nacimiento'      => $datos['titular_fecha_nacimiento']  ?? null,
                        'genero'                => $datos['titular_genero']            ?? null,
                        'nacionalidad'          => $datos['titular_nacionalidad']      ?? 'Peruana',
                        'telefono2'             => $datos['titular_telefono2']         ?? null,
                        'emergencia_nombre'     => $datos['emergencia_nombre']         ?? null,
                        'emergencia_parentesco' => $datos['emergencia_parentesco_manual']
                                                    ?? ($datos['emergencia_parentesco'] ?? null),
                        'emergencia_telefono'   => $datos['emergencia_telefono']       ?? null,
                    ]);
                }

                $clienteId = $cliente->id;
            } // ← cierra if (!$clienteId)

            // ── 2. Estado inicial ─────────────────────────────────
            $mapaEstados = [
                'mitad_pago' => 'mitad_pago',
                'pagado'     => 'pagado',
                'cancelada'  => 'cancelada',
            ];
            $nombreEstado  = $mapaEstados[$datos['estado_inicial']] ?? 'mitad_pago';
            $estadoInicial = EstadoReserva::where('nombre', $nombreEstado)->firstOrFail();

            // ── 3. Crear la reserva ───────────────────────────────
            $reserva = Reserva::create([
                'codigo_reserva'                     => Reserva::generarCodigo(),
                'nombre_tour'                        => $datos['nombre_tour'],
                'fecha_tour'                         => $datos['fecha_tour'],
                'hora_salida'                        => $datos['hora_salida'],
                'cliente_id'                         => $clienteId,
                'fecha_tour_id'                      => null,
                'estado_id'                          => $estadoInicial->id,
                'usuario_admin_id'                   => Auth::id(),
                'cantidad_adultos'                   => $datos['cantidad_adultos'],
                'cantidad_ninos'                     => $datos['cantidad_ninos'],
                'precio_total'                       => (float) $datos['precio_tour'],
                'monto_pagado'                       => $datos['monto_pagado_inicial'],
                'canal_contacto'                     => $datos['canal_contacto'],
                'ciudad_procedencia'                 => $datos['ciudad_procedencia'],
                'ciudad_destino'                     => $datos['ciudad_destino']           ?? null,
                'departamento_destino'               => $datos['departamento_destino']     ?? null,
                'fecha_arribo'                       => $datos['fecha_arribo']             ?? null,
                'fecha_retorno'                      => $datos['fecha_retorno']            ?? null,
                'dias_viaje'                         => $datos['dias_viaje']               ?? null,
                'hora_arribo'                        => $datos['hora_arribo']              ?? null,
                'hora_retorno'                       => $datos['hora_retorno']             ?? null,
                'tipo_transporte'                    => $datos['tipo_transporte']          ?? null,
                'empresa_transporte'                 => $datos['empresa_transporte']       ?? null,
                'aerolinea'                          => $datos['aerolinea']                ?? null,
                'numero_vuelo'                       => $datos['numero_vuelo']             ?? null,
                'hora_salida_vuelo'                  => $datos['hora_salida_vuelo']        ?? null,
                'hora_llegada_vuelo'                 => $datos['hora_llegada_vuelo']       ?? null,
                'nombre_hotel'                       => $datos['nombre_hotel']             ?? null,
                'tipo_habitacion'                    => $datos['tipo_habitacion']          ?? null,
                'tipo_establecimiento'               => $datos['tipo_establecimiento']     ?? null,
                'tipo_cama'                          => $datos['tipo_cama']                ?? null,
                'plan_alimentacion'                  => $datos['plan_alimentacion']        ?? null,
                'tipo_comprobante'                   => $datos['tipo_comprobante'],
                'ruc_factura'                        => $datos['ruc_factura']              ?? null,
                'razon_social'                       => $datos['razon_social']             ?? null,
                'punto_encuentro'                    => $datos['punto_encuentro']          ?? null,
                'hora_recojo'                        => $datos['hora_recojo']              ?? null,
                'alergias_titular'                   => ($datos['titular_tiene_alergias'] ?? '') === 'si'
                                                        ? ($datos['titular_alergias_detalle'] ?? null)
                                                        : null,
                'restricciones_alimentarias_titular' => $datos['titular_restricciones']   ?? null,
                'titular_obs_medicas'                => $datos['titular_obs_medicas']      ?? null,
                'politica_descripcion'               => $datos['politica_descripcion']     ?? null,
                'politica_tipo'                      => $datos['politica_tipo']            ?? null,
                'observaciones'                      => $datos['observaciones']            ?? null,
            ]);

            // ── 4. Pasajero titular ───────────────────────────────
            $titular = $reserva->pasajeros()->create([
                'nombre_completo'  => $datos['titular_nombre'],
                'tipo'             => 'adulto',
                'tipo_documento'   => $datos['titular_tipo_documento']   ?? null,
                'numero_documento' => $datos['titular_numero_documento'] ?? null,
                'fecha_nacimiento' => $datos['titular_fecha_nacimiento'] ?? null,
                'edad'             => null,
                'es_titular'       => true,
            ]);
            $this->guardarSaludTitular($titular, $datos);

            // ── 5. Pasajeros adicionales ──────────────────────────
            if (empty($datos['solo_pasajero'])) {
                foreach ($datos['pasajeros'] ?? [] as $p) {
                    if (empty(trim($p['nombre_completo'] ?? ''))) continue;
                    $pasajero = $reserva->pasajeros()->create([
                        'nombre_completo'  => $p['nombre_completo'],
                        'tipo'             => $p['tipo']             ?? 'adulto',
                        'tipo_documento'   => $p['tipo_documento']   ?? null,
                        'numero_documento' => $p['numero_documento'] ?? null,
                        'fecha_nacimiento' => !empty($p['fecha_nacimiento']) ? $p['fecha_nacimiento'] : null,
                        'edad'             => isset($p['edad']) && $p['edad'] !== '' ? (int) $p['edad'] : null,
                        'es_titular'       => false,
                    ]);
                    $this->guardarSaludPasajero($pasajero, $p);
                }
            }

            // ── 6. Método de pago ─────────────────────────────────
            $metodoPago = MetodoPago::where('clave', $datos['metodo_pago'])
                            ->orWhere('nombre', $datos['metodo_pago'])
                            ->firstOrFail();

            // ── 7. Baucher ────────────────────────────────────────
            $rutaBaucher = null;
            if (!empty($datos['archivo_baucher']) && $datos['archivo_baucher'] instanceof UploadedFile) {
                $rutaBaucher = $datos['archivo_baucher']->store('baucherss', 'public');
            }

            // ── 8. Pago inicial ───────────────────────────────────
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
            $reserva->update([
               'monto_pagado' => $reserva->pagos()->sum('monto'),
            ]);

            // ── 9. Historial ──────────────────────────────────────
            HistorialEstado::create([
                'reserva_id'      => $reserva->id,
                'estado_nuevo_id' => $estadoInicial->id,
                'cambiado_por'    => Auth::id(),
                'motivo'          => 'Reserva creada — canal: ' . $datos['canal_contacto'],
                'fecha_cambio'    => now(),
            ]);

            return $reserva;
        }); // ← cierra DB::transaction

        // Notificaciones FUERA de la transacción
        $this->enviarNotificacionesCreacion($reserva, $datos);
        return $reserva;
    }
    

    // ══════════════════════════════════════════════════════════════════
    // CAMBIAR ESTADO
    // ══════════════════════════════════════════════════════════════════
    public function cambiarEstado(Reserva $reserva, int $nuevoEstadoId, ?string $motivo = null): void
    {
        $estadoAnterior = $reserva->estado_id;
        $estadoNuevo    = EstadoReserva::findOrFail($nuevoEstadoId);

        $actualizacion = ['estado_id' => $nuevoEstadoId];

        if ($estadoNuevo->nombre === 'pagado') {
            $sumaPagos = $reserva->pagos()->sum('monto');
            $actualizacion['monto_pagado'] = max($sumaPagos, (float) $reserva->precio_total);
        }

        $reserva->update($actualizacion);

        HistorialEstado::create([
            'reserva_id'         => $reserva->id,
            'estado_anterior_id' => $estadoAnterior,
            'estado_nuevo_id'    => $nuevoEstadoId,
            'cambiado_por'       => Auth::id(),
            'motivo'             => $motivo,
            'fecha_cambio'       => now(),
        ]);

        if ($estadoNuevo->nombre === 'confirmada' && !$reserva->notificacion_enviada) {
            $reserva->load(['cliente', 'fechaTour.tour', 'pasajeros']);
            $pdfPath = $this->pdfService->generarConfirmacion($reserva);
            $this->mailService->enviarConfirmacion($reserva, $pdfPath);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // CARGAR DETALLE
    // ══════════════════════════════════════════════════════════════════
    public function cargarDetalle(Reserva $reserva): Reserva
    {
        $reserva->load([
            'cliente',
            'fechaTour.tour',
            'estado',
            'pasajeros.salud',
            'pagos.metodoPago',
            'pagos.registradoPor',
            'logistica',
            'historialEstados.estadoAnterior',
            'historialEstados.estadoNuevo',
            'historialEstados.cambiadorPor',
            'comprobantes',
        ]);

        return $reserva;
    }

    // ══════════════════════════════════════════════════════════════════
    // ACTUALIZAR
    // ══════════════════════════════════════════════════════════════════
    public function actualizar(Reserva $reserva, array $datos): Reserva
    {
        return DB::transaction(function () use ($reserva, $datos) {

            // ── 1. Actualizar cliente ─────────────────────────────
            $cliente = $reserva->cliente;
            if ($cliente) {
                $cliente->update([
                    'nombre_completo'       => $datos['titular_nombre']              ?? $cliente->nombre_completo,
                    'email'                 => $datos['titular_email']               ?? $cliente->email,
                    'tipo_documento'        => $datos['titular_tipo_documento']      ?? $cliente->tipo_documento,
                    'numero_documento'      => $datos['titular_numero_documento']    ?? $cliente->numero_documento,
                    'fecha_nacimiento'      => $datos['titular_fecha_nacimiento']    ?? $cliente->fecha_nacimiento,
                    'genero'                => $datos['titular_genero']              ?? $cliente->genero,
                    'nacionalidad'          => $datos['titular_nacionalidad']        ?? $cliente->nacionalidad,
                    'telefono2'             => $datos['titular_telefono2']           ?? $cliente->telefono2,
                    'emergencia_nombre'     => $datos['emergencia_nombre']           ?? $cliente->emergencia_nombre,
                    'emergencia_parentesco' => $datos['emergencia_parentesco_manual']
                                               ?? ($datos['emergencia_parentesco']  ?? $cliente->emergencia_parentesco),
                    'emergencia_telefono'   => $datos['emergencia_telefono']         ?? $cliente->emergencia_telefono,
                ]);
            }

            // ── 2. Estado ─────────────────────────────────────────
            $mapaEstados = [
                'mitad_pago' => 'mitad_pago',
                'pagado'     => 'pagado',
                'cancelada'  => 'cancelada',
            ];
            $nombreEstado = $mapaEstados[$datos['estado_inicial']] ?? 'mitad_pago';
            $estado       = EstadoReserva::where('nombre', $nombreEstado)->firstOrFail();
            $estadoCambio = $estado->id !== $reserva->estado_id;
            $estadoAnteriorId = $reserva->estado_id;

            // ── 3. Actualizar reserva ─────────────────────────────
            $reserva->update([
                'nombre_tour'                        => $datos['nombre_tour'],
                'fecha_tour'                         => $datos['fecha_tour'],
                'hora_salida'                        => $datos['hora_salida'],
                'estado_id'                          => $estado->id,
                'cantidad_adultos'                   => $datos['cantidad_adultos'],
                'cantidad_ninos'                     => $datos['cantidad_ninos'],
                'precio_total'                       => (float) $datos['precio_tour'],
                'canal_contacto'                     => $datos['canal_contacto'],
                'ciudad_procedencia'                 => $datos['ciudad_procedencia'],
                'ciudad_destino'                     => $datos['ciudad_destino']           ?? null,
                'departamento_destino'               => $datos['departamento_destino']     ?? null,
                'fecha_arribo'                       => $datos['fecha_arribo']             ?? null,
                'fecha_retorno'                      => $datos['fecha_retorno']            ?? null,
                'dias_viaje'                         => $datos['dias_viaje']               ?? null,
                'hora_arribo'                        => $datos['hora_arribo']              ?? null,
                'hora_retorno'                       => $datos['hora_retorno']             ?? null,
                'tipo_transporte'                    => $datos['tipo_transporte']          ?? null,
                'empresa_transporte'                 => $datos['empresa_transporte']       ?? null,
                'aerolinea'                          => $datos['aerolinea']                ?? null,
                'numero_vuelo'                       => $datos['numero_vuelo']             ?? null,
                'hora_salida_vuelo'                  => $datos['hora_salida_vuelo']        ?? null,
                'hora_llegada_vuelo'                 => $datos['hora_llegada_vuelo']       ?? null,
                'nombre_hotel'                       => $datos['nombre_hotel']             ?? null,
                'tipo_habitacion'                    => $datos['tipo_habitacion']          ?? null,
                'tipo_establecimiento'               => $datos['tipo_establecimiento']     ?? null,
                'tipo_cama'                          => $datos['tipo_cama']                ?? null,
                'plan_alimentacion'                  => $datos['plan_alimentacion']        ?? null,
                'tipo_comprobante'                   => $datos['tipo_comprobante'],
                'ruc_factura'                        => $datos['ruc_factura']              ?? null,
                'razon_social'                       => $datos['razon_social']             ?? null,
                'punto_encuentro'                    => $datos['punto_encuentro']          ?? null,
                'hora_recojo'                        => $datos['hora_recojo']              ?? null,
                'alergias_titular'                   => ($datos['titular_tiene_alergias'] ?? '') === 'si'
                                                        ? ($datos['titular_alergias_detalle'] ?? null)
                                                        : null,
                'restricciones_alimentarias_titular' => $datos['titular_restricciones']   ?? null,
                'titular_obs_medicas'                => $datos['titular_obs_medicas']      ?? null,
                'politica_descripcion'               => $datos['politica_descripcion']     ?? null,
                'politica_tipo'                      => $datos['politica_tipo']            ?? null,
                'observaciones'                      => $datos['observaciones']            ?? null,
                'email_contacto'                     => $datos['titular_email'] ?? null,
            ]);

            // ── 4. Reemplazar pasajeros + salud ───────────────────
            $reserva->pasajeros()->where('es_titular', false)->delete();

            $titular = $reserva->pasajeros()->where('es_titular', true)->firstOrFail();
            $titular->update([
                 'nombre_completo'  => $datos['titular_nombre'],
                 'tipo_documento'   => $datos['titular_tipo_documento']   ?? null,
                 'numero_documento' => $datos['titular_numero_documento'] ?? null,
                 'fecha_nacimiento' => $datos['titular_fecha_nacimiento'] ?? null,
              ]);
               $titular->salud()->delete();
               $this->guardarSaludTitular($titular, $datos);

            if (empty($datos['solo_pasajero'])) {
                foreach ($datos['pasajeros'] ?? [] as $p) {
                    if (empty(trim($p['nombre_completo'] ?? ''))) continue;
                    $pasajero = $reserva->pasajeros()->create([
                        'nombre_completo'  => $p['nombre_completo'],
                        'tipo'             => $p['tipo']             ?? 'adulto',
                        'tipo_documento'   => $p['tipo_documento']   ?? null,
                        'numero_documento' => $p['numero_documento'] ?? null,
                        'fecha_nacimiento' => !empty($p['fecha_nacimiento']) ? $p['fecha_nacimiento'] : null,
                        'edad'             => isset($p['edad']) && $p['edad'] !== '' ? (int) $p['edad'] : null,
                        'es_titular'       => false,
                    ]);
                    $this->guardarSaludPasajero($pasajero, $p);
                }
            }

            // ── 5. Nuevo pago opcional ────────────────────────────
            if (!empty($datos['metodo_pago']) && !empty($datos['monto_pagado_inicial'])) {
                $metodoPago = MetodoPago::where('clave', $datos['metodo_pago'])
                                ->orWhere('nombre', $datos['metodo_pago'])
                                ->firstOrFail();

                $rutaBaucher = null;
                if (!empty($datos['archivo_baucher']) && $datos['archivo_baucher'] instanceof UploadedFile) {
                    $rutaBaucher = $datos['archivo_baucher']->store('baucherss', 'public');
                }

                $reserva->pagos()->create([
                    'metodo_pago_id'    => $metodoPago->id,
                    'registrado_por'    => Auth::id(),
                    'monto'             => $datos['monto_pagado_inicial'],
                    'numero_operacion'  => $datos['numero_operacion'] ?? null,
                    'archivo_baucher'   => $rutaBaucher,
                    'tipo_pago'         => $datos['tipo_pago'] ?? 'adelanto',
                    'estado_validacion' => 'pendiente',
                    'fecha_pago'        => $datos['fecha_pago'] ?? now(),
                ]);

                $reserva->update([
                    'monto_pagado' => $reserva->pagos()->sum('monto'),
                ]);
            }

            // ── 6. Historial estado ───────────────────────────────
            if ($estadoCambio) {
                HistorialEstado::create([
                    'reserva_id'         => $reserva->id,
                    'estado_anterior_id' => $estadoAnteriorId,
                    'estado_nuevo_id'    => $estado->id,
                    'cambiado_por'       => Auth::id(),
                    'motivo'             => 'Reserva editada — estado actualizado',
                    'fecha_cambio'       => now(),
                ]);
            }

            return $reserva->fresh();
        });
    }

    // ══════════════════════════════════════════════════════════════════
    // HELPERS PRIVADOS — SALUD
    // ══════════════════════════════════════════════════════════════════
    private function guardarSaludTitular(Pasajero $pasajero, array $datos): void
    {
        $tieneAlergias      = ($datos['titular_tiene_alergias'] ?? 'no') === 'si';
        $tieneRestricciones = !empty(trim($datos['titular_restricciones']  ?? ''));
        $tieneObsMedicas    = !empty(trim($datos['titular_obs_medicas']    ?? ''));
        $tieneDiscap        = !empty(trim($datos['titular_discapacidades'] ?? ''));
        $tieneSeguro        = !empty(trim($datos['titular_seguro_salud']   ?? ''));
 
        // Guardar si hay CUALQUIER dato de salud, incluido seguro
        if ($tieneAlergias || $tieneRestricciones || $tieneObsMedicas || $tieneDiscap || $tieneSeguro) {
            $pasajero->salud()->create([
                'alergias'                   => $tieneAlergias
                                                ? ($datos['titular_alergias_detalle'] ?? null)
                                                : null,
                'restricciones_alimentarias' => $datos['titular_restricciones']      ?? null,
                'condiciones_medicas'        => $datos['titular_obs_medicas']        ?? null,
                'medicamentos'               => null,
                'discapacidades'             => $tieneDiscap
                                                ? ($datos['titular_discapacidades']  ?? null)
                                                : null,
                'discapacidad_otro'          => $tieneDiscap
                                                ? ($datos['titular_discapacidad_otro'] ?? null)
                                                : null,
                'seguro_salud'               => !empty($datos['titular_seguro_salud'])
                                                ? $datos['titular_seguro_salud']
                                                : null,
            ]);
        }
    }
 
    private function guardarSaludPasajero(Pasajero $pasajero, array $p): void
    {
        $tieneAlergias      = ($p['tiene_alergias'] ?? 'no') === 'si';
        $tieneRestricciones = !empty(trim($p['restricciones']   ?? ''));
        $tieneObsMedicas    = !empty(trim($p['obs_medicas']     ?? ''));
        $tieneDiscap        = !empty(trim($p['discapacidades']  ?? ''));
        $tieneSeguro        = !empty(trim($p['seguro_salud']    ?? ''));
 
        if ($tieneAlergias || $tieneRestricciones || $tieneObsMedicas || $tieneDiscap || $tieneSeguro) {
            $pasajero->salud()->create([
                'alergias'                   => $tieneAlergias
                                                ? ($p['alergias_detalle'] ?? null)
                                                : null,
                'restricciones_alimentarias' => $p['restricciones']       ?? null,
                'condiciones_medicas'        => $p['obs_medicas']         ?? null,
                'medicamentos'               => null,
                'discapacidades'             => $tieneDiscap
                                                ? ($p['discapacidades']   ?? null)
                                                : null,
                'discapacidad_otro'          => $tieneDiscap
                                                ? ($p['discapacidad_otro'] ?? null)
                                                : null,
                'seguro_salud'               => !empty($p['seguro_salud'])
                                                ? $p['seguro_salud']
                                                : null,
            ]);
        }
    }
    // ══════════════════════════════════════════════════════════════════
    // NOTIFICACIONES CREACIÓN
    // ══════════════════════════════════════════════════════════════════
    public function enviarNotificacionesCreacion(Reserva $reserva, array $datos): void
    {
        try {
            $notifWhatsapp = ($datos['notif_whatsapp'] ?? '0') === '1';
            $notifEmail    = ($datos['notif_email']    ?? '0') === '1';

            if (!$notifWhatsapp && !$notifEmail) {
                return;
            }

            $reserva->load(['cliente', 'pasajeros.salud', 'pagos.metodoPago']);

            $pdfPath = null;
            if ($notifEmail) {
                $pdfPath = $this->pdfService->generarConfirmacion($reserva);
            }

            if ($notifWhatsapp) {
                $this->whatsAppService->enviarConfirmacionReserva($reserva);
            }

            if ($notifEmail) {
                $this->mailService->enviarConfirmacion($reserva, $pdfPath);
            }
            if ($notifEmail) {
                $this->mailService->enviarConfirmacion($reserva, $pdfPath);
            }

            $reserva->update(['notificacion_enviada' => true]); 

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('ReservaService: error en notificaciones creación', [
                'reserva_id' => $reserva->id,
                'mensaje'    => $e->getMessage(),
            ]);
        }
    }
}