{{-- =====================================================================
     ARCHIVO: show.blade.php
     UBICACIÓN: resources/views/reservas/show.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Reserva ' . $reserva->codigo_reserva)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --ink: #0d1117; --ink-2: #374151; --ink-3: #6b7280; --ink-4: #9ca3af;
    --line: #e5e7eb; --line-2: #f3f4f6;
    --blue: #1d4ed8; --blue-l: #eff6ff; --blue-m: #dbeafe;
    --green: #059669; --green-l: #ecfdf5;
    --amber: #d97706; --amber-l: #fffbeb;
    --red: #dc2626; --red-l: #fef2f2;
    --purple: #7c3aed; --purple-l: #f5f3ff;
}
body { font-family: 'DM Sans', sans-serif; }

/* ── HEADER RESERVA ── */
.reserva-header {
    background: white;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    flex-wrap: wrap;
}
.reserva-codigo {
    font-family: 'DM Mono', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--blue);
    letter-spacing: .03em;
}
.reserva-meta { font-size: .78rem; color: var(--ink-4); margin-top: .25rem; }
.reserva-meta strong { color: var(--ink-3); }

/* ── ESTADO BADGE ── */
.estado-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: .8rem;
    font-weight: 700;
}
.est-consulta    { background: #f1f5f9; color: #475569; }
.est-pre_reserva { background: #fffbeb; color: #92400e; }
.est-confirmada  { background: var(--green-l); color: #065f46; }
.est-mitad_pago  { background: var(--blue-l); color: #1e40af; }
.est-pagado      { background: #f0fdf4; color: #15803d; }
.est-cancelada   { background: var(--red-l); color: #991b1b; }
.est-finalizada  { background: var(--purple-l); color: #5b21b6; }

/* ── CARDS ── */
.info-card {
    background: white;
    border: 1px solid var(--line);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.info-card-header {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid var(--line);
    font-size: .8rem;
    font-weight: 700;
    color: var(--ink-3);
    text-transform: uppercase;
    letter-spacing: .07em;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
}
.info-card-header .hdr-left { display: flex; align-items: center; gap: .5rem; }
.info-card-body { padding: 1.25rem; }

/* ── DATO LABEL/VALUE ── */
.dato { margin-bottom: .875rem; }
.dato:last-child { margin-bottom: 0; }
.dato .lbl { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--ink-4); margin-bottom: .2rem; }
.dato .val { font-size: .88rem; font-weight: 600; color: var(--ink); }
.dato .val.mono { font-family: 'DM Mono', monospace; }

/* ── TABLA INTERNA ── */
.inner-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
.inner-table thead th {
    padding: .55rem 1rem;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--ink-4);
    background: var(--line-2);
    border-bottom: 1px solid var(--line);
}
.inner-table thead th:first-child { padding-left: 1.25rem; }
.inner-table tbody td {
    padding: .75rem 1rem;
    border-bottom: 1px solid var(--line);
    color: var(--ink-2);
    vertical-align: middle;
}
.inner-table tbody td:first-child { padding-left: 1.25rem; }
.inner-table tbody tr:last-child td { border-bottom: none; }

/* ── PAGO RESUMEN ── */
.pago-resumen {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0;
    border-top: 1px solid var(--line);
    background: var(--line-2);
}
.pago-res-item {
    padding: .875rem 1.25rem;
    border-right: 1px solid var(--line);
}
.pago-res-item:last-child { border-right: none; }
.pago-res-item .pr-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--ink-4); margin-bottom: .2rem; }
.pago-res-item .pr-val { font-family: 'DM Mono', monospace; font-size: 1rem; font-weight: 700; }

/* ── BARRA DE PROGRESO ── */
.progress-track {
    background: var(--line);
    border-radius: 999px;
    height: 8px;
    overflow: hidden;
    margin: .75rem 1.25rem;
}
.progress-fill { height: 100%; border-radius: 999px; transition: width .4s ease; }

/* ── HISTORIAL ── */
.historial-timeline { padding: 0 1.25rem 1.25rem; }
.timeline-item {
    display: flex;
    gap: 1rem;
    position: relative;
    padding-bottom: 1.25rem;
}
.timeline-item:last-child { padding-bottom: 0; }
.timeline-item:last-child .tl-line { display: none; }

.tl-left { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
.tl-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    flex-shrink: 0;
    border: 2px solid;
}
.tl-line { flex: 1; width: 2px; background: var(--line); margin: 4px 0; min-height: 20px; }

.tl-right { flex: 1; padding-top: .2rem; }
.tl-estado { font-size: .82rem; font-weight: 700; color: var(--ink); }
.tl-meta { font-size: .74rem; color: var(--ink-4); margin-top: .1rem; display: flex; gap: .75rem; flex-wrap: wrap; }
.tl-motivo {
    margin-top: .4rem;
    background: var(--line-2);
    border-radius: 6px;
    padding: .4rem .6rem;
    font-size: .75rem;
    color: var(--ink-3);
    font-style: italic;
}

/* ── CAMBIAR ESTADO ── */
.estado-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .4rem;
    margin-bottom: .875rem;
}
.est-opt {
    border: 1.5px solid var(--line);
    border-radius: 8px;
    padding: .5rem .4rem;
    text-align: center;
    cursor: pointer;
    font-size: .7rem;
    font-weight: 700;
    color: var(--ink-3);
    transition: all .15s;
    user-select: none;
    line-height: 1.3;
}
.est-opt input { display: none; }
.est-opt:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.est-opt.e-consulta    { border-color: #9ca3af; }
.est-opt.e-pre-reserva { border-color: #f59e0b; background: var(--amber-l); color: #92400e; }
.est-opt.e-confirmada  { border-color: var(--green); background: var(--green-l); color: #065f46; }
.est-opt.e-mitad       { border-color: var(--blue); background: var(--blue-l); color: #1e40af; }
.est-opt.e-pagado      { border-color: #15803d; background: #f0fdf4; color: #15803d; }
.est-opt.e-cancelada   { border-color: var(--red); background: var(--red-l); color: #991b1b; }

.est-opt.e-active { box-shadow: 0 0 0 3px rgba(0,0,0,.1); transform: translateY(-1px); }

/* ── FORM INPUTS ── */
.f-input {
    width: 100%; padding: 9px 12px;
    border: 1.5px solid var(--line); border-radius: 8px;
    font-size: .84rem; font-family: 'DM Sans', sans-serif; color: var(--ink);
    background: white; outline: none; transition: border-color .15s;
    margin-bottom: .5rem; -webkit-appearance: none;
}
.f-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px var(--blue-m); }

/* ── BOTONES ── */
.btn-sm-primary {
    width: 100%; padding: 9px; border: none; border-radius: 8px;
    background: var(--blue); color: #fff; font-size: .84rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .15s;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.btn-sm-primary:hover { background: #1e40af; }

.btn-sm-green {
    background: var(--green); color: #fff; border: none; border-radius: 8px;
    padding: 5px 12px; font-size: .78rem; font-weight: 600; cursor: pointer;
    display: inline-flex; align-items: center; gap: .3rem;
    font-family: 'DM Sans', sans-serif; transition: background .15s; text-decoration: none;
}
.btn-sm-green:hover { background: #047857; color: #fff; }

.btn-back {
    display: inline-flex; align-items: center; gap: .4rem;
    color: var(--ink-3); font-size: .84rem; font-weight: 500;
    text-decoration: none; margin-bottom: 1rem; transition: color .15s;
}
.btn-back:hover { color: var(--blue); }

/* ── BADGE PAGO TIPO ── */
.tipo-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 2px 8px; border-radius: 6px;
    font-size: .72rem; font-weight: 600;
}
.tipo-adelanto    { background: var(--amber-l);  color: #92400e; }
.tipo-saldo       { background: var(--blue-l);   color: #1e40af; }
.tipo-pago_completo { background: var(--green-l); color: #065f46; }

.val-pendiente  { background: var(--amber-l); color: #92400e; }
.val-verificado { background: var(--green-l); color: #065f46; }
.val-rechazado  { background: var(--red-l);   color: #991b1b; }

/* ── BAUCHER PREVIEW ── */
.baucher-thumb {
    width: 48px; height: 48px; border-radius: 6px;
    object-fit: cover; border: 1px solid var(--line);
    cursor: pointer;
}
</style>
@endpush

@section('contenido')

<a href="{{ route('reservas.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Reservas
</a>

{{-- ── HEADER ── --}}
<div class="reserva-header">
    <div>
        <div class="reserva-codigo">{{ $reserva->codigo_reserva }}</div>
        <div class="reserva-meta">
            Registrada el {{ $reserva->created_at->format('d/m/Y \a \l\a\s H:i') }}
            &nbsp;·&nbsp;
            Por <strong>{{ $reserva->usuarioAdmin->nombre_completo ?? 'Sistema' }}</strong>
            &nbsp;·&nbsp;
            Canal:
            @php $canalIcon = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2']; @endphp
            <strong><i class="bi {{ $canalIcon[$reserva->canal_contacto] ?? 'bi-chat' }}"></i> {{ ucfirst($reserva->canal_contacto) }}</strong>
        </div>
    </div>
    <div>
        @php $estadoSlug = str_replace(' ','_',strtolower($reserva->estado->nombre ?? 'consulta')); @endphp
        <span class="estado-badge est-{{ $estadoSlug }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>
            {{ ucfirst(str_replace('_',' ',$reserva->estado->nombre)) }}
        </span>
    </div>
    <a href="{{ route('reservas.edit', $reserva) }}" class="btn-sm-green" style="margin-top:.5rem;">
       <i class="bi bi-pencil"></i> Editar
    </a>
</div>

<div class="row g-3">

    {{-- ═══════ COLUMNA IZQUIERDA ═══════ --}}
    <div class="col-lg-8">

        {{-- Cliente + Tour --}}
        <div class="row g-3 mb-0">
            <div class="col-md-6">
                <div class="info-card" style="margin-bottom:0">
                    <div class="info-card-header">
                        <div class="hdr-left"><i class="bi bi-person-circle"></i> Cliente</div>
                    </div>
                    <div class="info-card-body">
                        <div class="dato">
                            <div class="lbl">Nombre completo</div>
                            <div class="val">{{ $reserva->cliente->nombre_completo }}</div>
                        </div>
                        <div class="dato">
                            <div class="lbl">Documento</div>
                            <div class="val mono">{{ $reserva->cliente->tipo_documento }}: {{ $reserva->cliente->numero_documento }}</div>
                        </div>
                        <div class="dato">
                          <div class="dato">
                             <div class="lbl">Celular / WhatsApp</div>
                              <div class="val">
                                @if($reserva->cliente->telefono)
                                <a href="https://wa.me/51{{ $reserva->cliente->telefono }}" target="_blank"
                                style="color:var(--green);text-decoration:none;font-weight:600;">
                            <i class="bi bi-whatsapp"></i> +51 {{ $reserva->cliente->telefono }}
                       </a>
                 @else — @endif
            </div>
        </div>
            @if($reserva->cliente->telefono2 ?? false)
                <div class="dato">
                   <div class="lbl">Teléfono alternativo</div>
                    <div class="val">{{ $reserva->cliente->telefono2 }}</div>
            </div>
            @endif
                            </div>
                        </div>
                        <div class="dato" style="margin-bottom:0">
                            <div class="lbl">Correo</div>
                            <div class="val">{{ $reserva->cliente->email ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card" style="margin-bottom:0">
                    <div class="info-card-header">
                        <div class="hdr-left"><i class="bi bi-map"></i> Tour</div>
                    </div>
                    <div class="info-card-body">
                        <div class="dato">
                    <div class="lbl">Tour</div>
                        <div class="val">{{ $reserva->nombre_tour ?? ($reserva->fechaTour?->tour?->nombre ?? '—') }}</div>
                    </div>
                    <div class="dato">
                        <div class="lbl">Fecha y hora de salida</div>
                        <div class="val mono">
                            {{ $reserva->fecha_tour ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y') : ($reserva->fechaTour?->fecha ? \Carbon\Carbon::parse($reserva->fechaTour->fecha)->format('d/m/Y') : '—') }}
                              —
                            {{ $reserva->hora_salida ?? $reserva->fechaTour?->hora_salida ?? '—' }}
                        </div>
                    </div>
                        <div class="dato">
                            <div class="lbl">Pasajeros</div>
                            <div class="val">
                                {{ $reserva->cantidad_adultos }} adulto(s)
                                @if($reserva->cantidad_ninos > 0)
                                    + {{ $reserva->cantidad_ninos }} niño(s)
                                @endif
                                <span style="color:var(--ink-4);font-size:.78rem;">
                                    ({{ $reserva->cantidad_adultos + $reserva->cantidad_ninos }} pax total)
                                </span>
                            </div>
                        </div>
                        <div class="dato" style="margin-bottom:0">
                            <div class="lbl">Ciudad de procedencia</div>
                            <div class="val">{{ $reserva->ciudad_procedencia ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top:1.25rem;"></div>

        {{-- Pasajeros --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-people"></i> Pasajeros</div>
                <span style="font-size:.78rem;color:var(--ink-4);text-transform:none;letter-spacing:0;font-weight:400;">
                    {{ $reserva->pasajeros->count() }} registrado(s)
                </span>
            </div>
            @if($reserva->pasajeros->count())
            <table class="inner-table">
                <thead>
                    <tr>
                        <th>Nombre completo</th>
                        <th>Tipo</th>
                        <th>Documento</th>
                        <th>Edad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reserva->pasajeros as $pasajero)
                    <tr>
                        <td style="font-weight:600;">{{ $pasajero->nombre_completo }}</td>
                        <td>
                            <span style="background:var(--line-2);border-radius:6px;padding:2px 8px;font-size:.75rem;font-weight:600;color:var(--ink-3);">
                                {{ ucfirst($pasajero->tipo) }}
                            </span>
                        </td>
                        <td style="font-family:'DM Mono',monospace;font-size:.78rem;">
                            {{ $pasajero->tipo_documento ? $pasajero->tipo_documento.': '.$pasajero->numero_documento : '—' }}
                        </td>
                        <td style="font-family:'DM Mono',monospace;font-size:.82rem;">{{ $pasajero->edad ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding:1.5rem;text-align:center;color:var(--ink-4);font-size:.84rem;">
                <i class="bi bi-people" style="font-size:1.5rem;display:block;margin-bottom:.5rem;"></i>
                Sin pasajeros adicionales registrados
            </div>
            @endif
        </div>

        {{-- Pagos --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-credit-card"></i> Pagos registrados</div>
                <button class="btn-sm-green" data-bs-toggle="modal" data-bs-target="#modalPago">
                    <i class="bi bi-plus"></i> Registrar pago
                </button>
            </div>

            {{-- Barra de progreso de pago --}}
            @php
                $total  = $reserva->precio_total ?? 0;
                $pagado = $reserva->monto_pagado ?? 0;
                $saldo  = max(0, $total - $pagado);
                $pct    = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
                $barColor = $pct >= 100 ? 'var(--green)' : ($pct >= 50 ? 'var(--blue)' : 'var(--amber)');
            @endphp
            <div style="padding: .875rem 1.25rem .25rem; display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:.75rem;font-weight:700;color:var(--ink-3);">Progreso de pago</span>
                <span style="font-size:.75rem;font-weight:700;color:{{ $barColor }};font-family:'DM Mono',monospace;">{{ $pct }}%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
            </div>

            <table class="inner-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>N° Operación</th>
                        <th>Estado</th>
                        <th>Baucher</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reserva->pagos as $pago)
                    <tr>
                        <td style="font-family:'DM Mono',monospace;font-size:.78rem;white-space:nowrap;">
                            {{ $pago->fecha_pago->format('d/m/Y') }}
                        </td>
                        <td style="font-size:.82rem;">{{ $pago->metodoPago->nombre }}</td>
                        <td>
                            <span class="tipo-badge tipo-{{ $pago->tipo_pago }}">
                                {{ ucfirst(str_replace('_',' ',$pago->tipo_pago)) }}
                            </span>
                        </td>
                        <td style="font-family:'DM Mono',monospace;font-weight:700;color:var(--green);">
                            S/ {{ number_format($pago->monto,2) }}
                        </td>
                        <td style="font-family:'DM Mono',monospace;font-size:.75rem;color:var(--ink-4);">
                            {{ $pago->numero_operacion ?? '—' }}
                        </td>
                        <td>
                            <span class="tipo-badge val-{{ $pago->estado_validacion }}">
                                @if($pago->estado_validacion==='verificado') <i class="bi bi-check-circle"></i>
                                @elseif($pago->estado_validacion==='rechazado') <i class="bi bi-x-circle"></i>
                                @else <i class="bi bi-clock"></i> @endif
                                {{ ucfirst($pago->estado_validacion) }}
                            </span>
                        </td>
                        <td>
                            @if($pago->archivo_baucher)
                                <a href="{{ Storage::url($pago->archivo_baucher) }}" target="_blank">
                                    <img src="{{ Storage::url($pago->archivo_baucher) }}" class="baucher-thumb" onerror="this.src='';this.style='display:none'">
                                </a>
                            @else
                                <span style="color:var(--ink-4);font-size:.75rem;">—</span>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2rem;color:var(--ink-4);font-size:.84rem;">
                            <i class="bi bi-cash-stack" style="font-size:1.4rem;display:block;margin-bottom:.5rem;"></i>
                            Sin pagos registrados aún
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pago-resumen">
                <div class="pago-res-item">
                    <div class="pr-label">Total reserva</div>
                    <div class="pr-val" style="color:var(--ink);">S/ {{ number_format($total,2) }}</div>
                </div>
                <div class="pago-res-item">
                    <div class="pr-label">Total pagado</div>
                    <div class="pr-val" style="color:var(--green);">S/ {{ number_format($pagado,2) }}</div>
                </div>
                <div class="pago-res-item">
                    <div class="pr-label">Saldo pendiente</div>
                    <div class="pr-val" style="color:{{ $saldo > 0 ? 'var(--red)' : 'var(--green)' }}">
                        S/ {{ number_format($saldo,2) }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════ COLUMNA DERECHA ═══════ --}}
    <div class="col-lg-4">

        {{-- Cambiar estado --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-arrow-repeat"></i> Cambiar estado</div>
            </div>
            <div class="info-card-body">
                <form method="POST" action="{{ route('reservas.cambiarEstado', $reserva) }}">
                    @csrf
                    @method('PATCH')
                    <div class="estado-grid">
                        @foreach($estados ?? \App\Models\EstadoReserva::all() as $estado)
                        @php
                            $slug = strtolower(str_replace(' ','_',$estado->nombre));
                            $claseMap = ['consulta'=>'e-consulta','pre_reserva'=>'e-pre-reserva','confirmada'=>'e-confirmada','mitad_pago'=>'e-mitad','pagado'=>'e-pagado','cancelada'=>'e-cancelada','finalizada'=>'e-cancelada'];
                            $iconMap  = ['consulta'=>'bi-question-circle','pre_reserva'=>'bi-clock','confirmada'=>'bi-check-circle','mitad_pago'=>'bi-half','pagado'=>'bi-check-all','cancelada'=>'bi-x-circle','finalizada'=>'bi-star'];
                            $clase = $claseMap[$slug] ?? 'e-consulta';
                            $icon  = $iconMap[$slug]  ?? 'bi-circle';
                            $activo = $reserva->estado_id == $estado->id;
                        @endphp
                        <label class="est-opt {{ $clase }} {{ $activo ? 'e-active' : '' }}">
                            <input type="radio" name="estado_id" value="{{ $estado->id }}" {{ $activo ? 'checked':'' }}>
                            <i class="bi {{ $icon }} d-block mb-1" style="font-size:1.1rem;"></i>
                            {{ ucfirst(str_replace('_',' ',$estado->nombre)) }}
                        </label>
                        @endforeach
                    </div>
                    <input type="text" name="motivo" class="f-input" placeholder="Motivo del cambio (opcional)">
                    <button type="submit" class="btn-sm-primary">
                        <i class="bi bi-arrow-repeat"></i> Actualizar estado
                    </button>
                </form>
            </div>
        </div>

        {{-- Historial ── --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-clock-history"></i> Historial de estados</div>
                <span style="font-size:.78rem;color:var(--ink-4);text-transform:none;letter-spacing:0;font-weight:400;">
                    {{ $reserva->historialEstados->count() }} cambio(s)
                </span>
            </div>
            <div class="historial-timeline">
                @php
                    $historial = $reserva->historialEstados->sortByDesc('fecha_cambio');
                    $dotColors = [
                        'consulta'    => ['bg'=>'#f1f5f9','border'=>'#9ca3af','icon'=>'bi-question-circle','text'=>'#475569'],
                        'pre_reserva' => ['bg'=>'#fffbeb','border'=>'#f59e0b','icon'=>'bi-clock','text'=>'#92400e'],
                        'confirmada'  => ['bg'=>'#ecfdf5','border'=>'#059669','icon'=>'bi-check-circle','text'=>'#065f46'],
                        'mitad_pago'  => ['bg'=>'#eff6ff','border'=>'#1d4ed8','icon'=>'bi-half','text'=>'#1e40af'],
                        'pagado'      => ['bg'=>'#f0fdf4','border'=>'#15803d','icon'=>'bi-check-all','text'=>'#15803d'],
                        'cancelada'   => ['bg'=>'#fef2f2','border'=>'#dc2626','icon'=>'bi-x-circle','text'=>'#991b1b'],
                        'finalizada'  => ['bg'=>'#f5f3ff','border'=>'#7c3aed','icon'=>'bi-star','text'=>'#5b21b6'],
                    ];
                @endphp
                @forelse($historial as $h)
                @php
                    $slug = strtolower(str_replace(' ','_',$h->estadoNuevo->nombre ?? 'consulta'));
                    $dc = $dotColors[$slug] ?? $dotColors['consulta'];
                @endphp
                <div class="timeline-item" style="margin-top:{{ $loop->first ? '1.25rem' : '0' }}">
                    <div class="tl-left">
                        <div class="tl-dot"
                             style="background:{{ $dc['bg'] }};border-color:{{ $dc['border'] }};color:{{ $dc['text'] }};">
                            <i class="bi {{ $dc['icon'] }}" style="font-size:.75rem;"></i>
                        </div>
                        @if(!$loop->last)
                        <div class="tl-line"></div>
                        @endif
                    </div>
                    <div class="tl-right">
                        <div class="tl-estado" style="color:{{ $dc['text'] }};">
                            {{ ucfirst(str_replace('_',' ',$h->estadoNuevo->nombre)) }}
                        </div>
                        <div class="tl-meta">
                            <span><i class="bi bi-calendar3" style="font-size:.65rem;"></i>
                                {{ \Carbon\Carbon::parse($h->fecha_cambio)->format('d/m/Y H:i') }}
                            </span>
                            @if(isset($h->usuarioAdmin))
                            <span><i class="bi bi-person" style="font-size:.65rem;"></i>
                                {{ $h->usuarioAdmin->nombre_completo ?? 'Sistema' }}
                            </span>
                            @endif
                        </div>
                        @if($h->motivo)
                        <div class="tl-motivo">"{{ $h->motivo }}"</div>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding:1.25rem 0;text-align:center;color:var(--ink-4);font-size:.84rem;">
                    Sin historial de cambios
                </div>
                @endforelse
            </div>
        </div>

        {{-- Logística --}}
        @if($reserva->logistica)
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-geo-alt"></i> Logística</div>
            </div>
            <div class="info-card-body">
                <div class="dato">
                    <div class="lbl">Hora de recojo</div>
                    <div class="val mono">{{ $reserva->logistica->hora_recojo ?? '—' }}</div>
                </div>
                <div class="dato">
                    <div class="lbl">Punto de encuentro</div>
                    <div class="val">{{ $reserva->logistica->direccion_recojo ?? '—' }}</div>
                </div>
                <div class="dato" style="margin-bottom:0">
                    <div class="lbl">Guía asignado</div>
                    <div class="val">{{ $reserva->logistica->nombre_guia ?? '—' }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection