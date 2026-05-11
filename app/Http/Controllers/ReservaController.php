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
        ]);
        return (new ReservasExport($filtros))->download();
    }

    // ── REPORTE SALUD PDF ─────────────────────────────────────────────
    public function reporteSalud(Request $request)
    {
        $request->validate([
            'fecha'        => 'required|date',
            'tour'         => 'nullable|string|max:200',
            'solo_alertas' => 'nullable|in:0,1',
        ]);

        $fecha       = $request->fecha;
        $tourFiltro  = $request->tour ?? null;
        $soloAlertas = $request->boolean('solo_alertas', false);

        $query = Reserva::with(['cliente', 'estado', 'pasajeros.salud'])
            ->whereDate('fecha_tour', $fecha)
            ->whereHas('estado', fn($q) => $q->whereNotIn('nombre', ['cancelada']))
            ->orderBy('hora_salida');

        if ($tourFiltro) {
            $query->where('nombre_tour', 'like', '%' . $tourFiltro . '%');
        }

        $reservas = $query->get();

        if ($soloAlertas) {
            $reservas = $reservas->filter(function ($reserva) {
                $tieneAlertaTitular = $reserva->alergias_titular
                    || $reserva->restricciones_alimentarias_titular
                    || $reserva->titular_obs_medicas;

                $tieneAlertaPasajero = $reserva->pasajeros->filter(fn($p) =>
                    $p->salud && (
                        $p->salud->alergias
                        || $p->salud->restricciones_alimentarias
                        || $p->salud->condiciones_medicas
                    )
                )->isNotEmpty();

                return $tieneAlertaTitular || $tieneAlertaPasajero;
            });
        }

        $totalPasajeros = $reservas->sum(fn($r) => $r->pasajeros->count());

        $conAlertas = $reservas->sum(fn($r) => $r->pasajeros->filter(function ($p) use ($r) {
            $tieneAlerta = $p->salud && (
                $p->salud->alergias
                || $p->salud->restricciones_alimentarias
                || $p->salud->condiciones_medicas
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
                $conAlertas
            );

        return response()->streamDownload(
            fn() => print($pdfOutput),
            'reporte-salud-' . $fecha . '.pdf',
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
            return back()->with('error', 'Error al reenviar: ' . $e->getMessage());
        }
    }
}