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
        $tours    = \App\Models\Tour::where('activo', true)
                        ->orderByDesc('veces_usado')
                        ->orderBy('nombre')
                        ->pluck('nombre');
        $ciudades = \App\Models\Reserva::whereNotNull('ciudad_destino')
                        ->where('ciudad_destino', '!=', '')
                        ->distinct()
                        ->orderBy('ciudad_destino')
                        ->pluck('ciudad_destino');

        return view('reservas.index', compact('reservas', 'estados', 'tours', 'ciudades'));
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
        $datos   = $request->validated();
        $reserva = $this->reservaService->crear($datos);

        // ✅ Disparar email en proceso separado (no bloquea)
        $this->reservaService->enviarNotificacionesCreacion($reserva, $datos);

        return redirect()->route('reservas.index')
            ->with('success', 'Reserva creada correctamente.');

    } catch (\Exception $e) {
    \Log::error('Error al crear reserva', [
        'message' => $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine(),
        'input'   => $request->except(['archivo_baucher']),
    ]);
    return back()
        ->withInput()
        ->with('error', 'Ocurrió un error al crear la reserva. Por favor intenta de nuevo.');
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
          ->with('error', 'Ocurrió un error al actualizar la reserva. Por favor intenta de nuevo.');
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
                    $rutaBaucher = $request->file('archivo_baucher')->store('bauchers', 'local');
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

            // BUG 7 FIX: capturar estado anterior ANTES del update
            $estadoAnteriorId = $reserva->estado_id;

            $reserva->update([
                'estado_id'    => $estadoPagado->id,
                'monto_pagado' => $nuevoMontoPagado,
            ]);

            \App\Models\HistorialEstado::create([
                'reserva_id'         => $reserva->id,
                'estado_anterior_id' => $estadoAnteriorId,
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

    // ── EXPORTAR EXCEL ────────────────────────────────────────────────
    // Acepta estados y canales múltiples desde el modal de exportación
    public function exportar(Request $request)
    {
        $filtros = $request->only([
            'estado',  'estados',
            'canal',   'canales',
            'fecha_desde', 'fecha_hasta',
            'buscar',
            'tour', 'ciudad_destino',
        ]);
        return (new ReservasExport($filtros))->download();
    }

    // ── REPORTE SALUD PDF ─────────────────────────────────────────────
    public function reporteSalud(Request $request)
    {
      $request->validate([
            'fecha'          => 'nullable|date|required_without:fecha_desde',
            'fecha_desde'    => 'nullable|date|required_without:fecha',
            'fecha_hasta'    => 'nullable|date|after_or_equal:fecha_desde',
            'tour'           => 'nullable|string|max:200',
            'ciudad_destino' => 'nullable|string|max:200',
            'solo_alertas'   => 'nullable|in:0,1',
        ]);
    $fecha        = $request->fecha ?? null;
        $fechaDesde   = $request->fecha_desde ?? null;
        $fechaHasta   = $request->fecha_hasta ?? null;
        $tourFiltro   = $request->tour ?? null;
        $ciudadFiltro = $request->ciudad_destino ?? null;
        $soloAlertas  = $request->boolean('solo_alertas', false);

        // Para el nombre del archivo y la vista
        $labelFecha = $fecha
            ? $fecha
            : ($fechaDesde . '_al_' . $fechaHasta);

    // ✅ Agregar fechaTour.tour al eager load para el fallback de nombre
    $query = Reserva::with(['cliente', 'estado', 'pasajeros.salud', 'fechaTour.tour'])
        ->where(function ($q) use ($fecha, $fechaDesde, $fechaHasta) {
            if ($fecha) {
                $q->whereDate('fecha_tour', $fecha)
                  ->orWhereHas('fechaTour', fn($fq) => $fq->whereDate('fecha', $fecha));
            } else {
                $q->whereBetween('fecha_tour', [$fechaDesde, $fechaHasta])
                  ->orWhereHas('fechaTour', fn($fq) =>
                      $fq->whereBetween('fecha', [$fechaDesde, $fechaHasta])
                  );
            }
        });
    if ($ciudadFiltro) {
        $query->where('ciudad_destino', 'like', '%' . $ciudadFiltro . '%');
    }

    $reservas = $query->get();

    // ✅ Asegurar que nombre_tour tenga valor para la vista
    $reservas->each(function ($reserva) {
        if (empty($reserva->nombre_tour) && $reserva->fechaTour?->tour) {
            $reserva->nombre_tour = $reserva->fechaTour->tour->nombre;
        }
    });

    if ($soloAlertas) {
        $reservas = $reservas->filter(function ($reserva) {
            $tieneAlertaTitular = $reserva->alergias_titular
                || $reserva->restricciones_alimentarias_titular
                || $reserva->titular_obs_medicas;

            $tieneAlertaPasajero = $reserva->pasajeros->contains(fn($p) =>
                $p->salud && (
                    $p->salud->alergias
                    || $p->salud->restricciones_alimentarias
                    || $p->salud->condiciones_medicas
                )
            );

            return $tieneAlertaTitular || $tieneAlertaPasajero;
        });
    }

    $totalPasajeros = $reservas->sum(fn($r) => $r->pasajeros->count());

    $conAlertas = $reservas->sum(fn($r) => $r->pasajeros->filter(function ($p) use ($r) {
    $salud = $p->salud;
    $tieneAlerta = $salud && (
        $salud->alergias
        || $salud->restricciones_alimentarias
        || $salud->condiciones_medicas
        || $salud->discapacidades
        || $salud->seguro_salud
    );
    if ($p->es_titular) {
        $tieneAlerta = $tieneAlerta
            || $r->alergias_titular
            || $r->restricciones_alimentarias_titular
            || $r->titular_obs_medicas;
    }
    return $tieneAlerta;
})->count());

    $pdfOutput = app(\App\Services\Integrations\PdfService::class)
        ->generarReporteSalud(
            $reservas,
            $fecha,
            $tourFiltro,
            $soloAlertas,
            $totalPasajeros,
            $conAlertas,
            $fechaDesde,
            $fechaHasta
        );

    return response()->streamDownload(
        fn() => print($pdfOutput),
        'reporte-salud-' . $labelFecha . '.pdf',
        ['Content-Type' => 'application/pdf']
    );
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
            return back()->with('error', 'Ocurrió un error al reenviar la notificación. Por favor intenta de nuevo.');
        }
    }
}