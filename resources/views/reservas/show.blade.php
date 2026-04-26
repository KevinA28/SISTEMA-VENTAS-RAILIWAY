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
    background: white; border: 1px solid var(--line); border-radius: 14px;
    padding: 1.25rem 1.5rem; margin-bottom: 1.25rem;
    display: flex; justify-content: space-between; align-items: center;
    gap: 1rem; flex-wrap: wrap;
}
.reserva-codigo {
    font-family: 'DM Mono', monospace; font-size: 1.35rem; font-weight: 700;
    color: var(--blue); letter-spacing: .03em;
}
.reserva-meta { font-size: .76rem; color: var(--ink-4); margin-top: .2rem; }
.reserva-meta strong { color: var(--ink-3); }

/* ── ESTADO BADGE ── */
.estado-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 6px 14px; border-radius: 999px; font-size: .8rem; font-weight: 700;
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
    background: white; border: 1px solid var(--line);
    border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem;
}
.info-card-header {
    padding: .8rem 1.25rem; border-bottom: 1px solid var(--line);
    font-size: .75rem; font-weight: 700; color: var(--ink-3);
    text-transform: uppercase; letter-spacing: .07em;
    display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    background: var(--line-2);
}
.info-card-header .hdr-left { display: flex; align-items: center; gap: .45rem; }
.info-card-body { padding: 1.1rem 1.25rem; }

/* ── DATO LABEL/VALUE ── */
.dato { display: flex; flex-direction: column; gap: 3px; }
.dato .lbl {
    font-size: .67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--ink-4);
}
.dato .val { font-size: .86rem; font-weight: 600; color: var(--ink); line-height: 1.4; }
.dato .val.mono { font-family: 'DM Mono', monospace; }

/* Grid de datos 2 columnas dentro de card */
.datos-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .85rem 1.25rem;
}
.datos-grid .dato.full { grid-column: 1 / -1; }

/* ── CLIENTE CARD ── */
.cliente-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: var(--blue-l); color: var(--blue);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 700; flex-shrink: 0;
    border: 2px solid var(--blue-m);
}

/* ── TABLA INTERNA ── */
.inner-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
.inner-table thead th {
    padding: .55rem 1rem; font-size: .67rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--ink-4); background: var(--line-2);
    border-bottom: 1px solid var(--line); white-space: nowrap; text-align: left;
}
.inner-table thead th:first-child { padding-left: 1.25rem; }
.inner-table thead th.th-center { text-align: center; }
.inner-table tbody td {
    padding: .75rem 1rem; border-bottom: 1px solid var(--line);
    color: var(--ink-2); vertical-align: middle;
}
.inner-table tbody td:first-child { padding-left: 1.25rem; }
.inner-table tbody td.td-center { text-align: center; }
.inner-table tbody tr:last-child td { border-bottom: none; }
.inner-table tbody tr:hover td { background: #fafbff; }

/* ── PAGO RESUMEN ── */
.pago-resumen {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 0; border-top: 1px solid var(--line); background: var(--line-2);
}
.pago-res-item {
    padding: .875rem 1.25rem; border-right: 1px solid var(--line);
}
.pago-res-item:last-child { border-right: none; }
.pago-res-item .pr-label {
    font-size: .67rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .07em; color: var(--ink-4); margin-bottom: .2rem;
}
.pago-res-item .pr-val { font-family: 'DM Mono', monospace; font-size: 1rem; font-weight: 700; }

/* ── BARRA DE PROGRESO ── */
.progress-wrap {
    padding: .875rem 1.25rem .5rem;
    display: flex; flex-direction: column; gap: .4rem;
}
.progress-header {
    display: flex; justify-content: space-between; align-items: center;
}
.progress-track {
    background: var(--line); border-radius: 999px; height: 8px; overflow: hidden;
}
.progress-fill { height: 100%; border-radius: 999px; transition: width .5s ease; }

/* ── BADGE PAGO TIPO ── */
.tipo-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 3px 9px; border-radius: 6px; font-size: .71rem; font-weight: 600; white-space: nowrap;
}
.tipo-adelanto      { background: var(--amber-l); color: #92400e; }
.tipo-saldo         { background: var(--blue-l);  color: #1e40af; }
.tipo-pago_completo { background: var(--green-l); color: #065f46; }
.tipo-mitad_pago    { background: var(--blue-l);  color: #1e40af; }

.val-pendiente  { background: var(--amber-l); color: #92400e; }
.val-verificado { background: var(--green-l); color: #065f46; }
.val-rechazado  { background: var(--red-l);   color: #991b1b; }

/* ── VOUCHER DESPLEGABLE ── */
.voucher-toggle {
    background: none; border: 1.5px solid var(--line); border-radius: 7px;
    padding: 4px 10px; font-size: .72rem; font-weight: 600; color: var(--ink-3);
    cursor: pointer; display: inline-flex; align-items: center; gap: .3rem;
    font-family: 'DM Sans', sans-serif; transition: all .15s;
}
.voucher-toggle:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }
.voucher-panel {
    display: none; margin-top: .5rem; background: var(--line-2);
    border: 1px solid var(--line); border-radius: 8px;
    padding: .75rem; text-align: center;
}
.voucher-panel.open { display: block; animation: fadeIn .2s ease; }
.voucher-panel img {
    max-width: 100%; max-height: 220px; border-radius: 6px;
    border: 1px solid var(--line); object-fit: contain;
}
.voucher-panel a.ver-full {
    display: inline-flex; align-items: center; gap: .3rem;
    margin-top: .4rem; font-size: .75rem; color: var(--blue); text-decoration: none; font-weight: 600;
}
.voucher-panel a.ver-full:hover { text-decoration: underline; }
@keyframes fadeIn { from { opacity:0; transform:translateY(-4px); } to { opacity:1; transform:translateY(0); } }

/* ── BOTONES ACCIÓN ── */
.btn-sm-green {
    background: var(--green); color: #fff; border: none; border-radius: 8px;
    padding: 6px 14px; font-size: .79rem; font-weight: 600; cursor: pointer;
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

.btn-editar {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 7px 16px; border-radius: 9px; font-size: .82rem; font-weight: 600;
    background: white; color: var(--ink-2); border: 1.5px solid var(--line);
    text-decoration: none; transition: all .15s; font-family: 'DM Sans', sans-serif;
}
.btn-editar:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }

/* ── HISTORIAL ── */
.historial-timeline { padding: 1rem 1.25rem; }
.timeline-item {
    display: flex; gap: .875rem; position: relative; padding-bottom: 1.1rem;
}
.timeline-item:last-child { padding-bottom: 0; }
.tl-left { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
.tl-dot {
    width: 30px; height: 30px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; flex-shrink: 0; border: 2px solid;
}
.tl-line { flex: 1; width: 2px; background: var(--line); margin: 3px 0; min-height: 16px; }
.tl-right { flex: 1; padding-top: .15rem; }
.tl-estado { font-size: .82rem; font-weight: 700; }
.tl-meta {
    font-size: .72rem; color: var(--ink-4); margin-top: .15rem;
    display: flex; gap: .6rem; flex-wrap: wrap; align-items: center;
}
.tl-motivo {
    margin-top: .35rem; background: var(--line-2); border-radius: 6px;
    padding: .35rem .6rem; font-size: .73rem; color: var(--ink-3); font-style: italic;
    border-left: 2px solid var(--line);
}

/* Responsive */
@media (max-width: 576px) {
    .datos-grid { grid-template-columns: 1fr; }
    .pago-resumen { grid-template-columns: 1fr; }
    .pago-res-item { border-right: none; border-bottom: 1px solid var(--line); }
    .pago-res-item:last-child { border-bottom: none; }
}
</style>
@endpush

@section('contenido')

<a href="{{ route('reservas.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Reservas
</a>

{{-- ── HEADER ── --}}
<div class="reserva-header">
    <div style="display:flex;align-items:center;gap:.875rem;">
        <div>
            <div class="reserva-codigo">{{ $reserva->codigo_reserva }}</div>
            <div class="reserva-meta">
                Registrada el {{ $reserva->created_at->format('d/m/Y \a \l\a\s H:i') }}
                &nbsp;·&nbsp; Por <strong>{{ $reserva->usuarioAdmin->nombre_completo ?? 'Sistema' }}</strong>
                &nbsp;·&nbsp;
                @php $canalIcon = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2']; @endphp
                <strong><i class="bi {{ $canalIcon[$reserva->canal_contacto] ?? 'bi-chat' }}"></i> {{ ucfirst($reserva->canal_contacto) }}</strong>
            </div>
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
        @php $estadoSlug = str_replace(' ','_',strtolower($reserva->estado->nombre ?? 'consulta')); @endphp
        <span class="estado-badge est-{{ $estadoSlug }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>
            {{ ucfirst(str_replace('_',' ',$reserva->estado->nombre)) }}
        </span>
        <a href="{{ route('reservas.edit', $reserva) }}" class="btn-editar">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- ═══════ COLUMNA IZQUIERDA ═══════ --}}
    <div class="col-lg-8">

        {{-- ── CLIENTE + TOUR ── --}}
        <div class="row g-3 mb-3">

            {{-- CLIENTE --}}
            <div class="col-md-6">
                <div class="info-card" style="margin-bottom:0;height:100%;">
                    <div class="info-card-header">
                        <div class="hdr-left"><i class="bi bi-person-circle"></i> Cliente</div>
                    </div>
                    <div class="info-card-body">
                        {{-- Avatar + nombre --}}
                        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;padding-bottom:.875rem;border-bottom:1px solid var(--line);">
                            <div class="cliente-avatar">
                                {{ strtoupper(substr($reserva->cliente->nombre_completo, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:.92rem;color:var(--ink);line-height:1.3;">
                                    {{ $reserva->cliente->nombre_completo }}
                                </div>
                                <div style="font-size:.72rem;color:var(--ink-4);margin-top:2px;">
                                    {{ $reserva->cliente->tipo_documento }}: 
                                    <span style="font-family:'DM Mono',monospace;font-weight:600;color:var(--ink-3);">
                                        {{ $reserva->cliente->numero_documento }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Datos en grid ── --}}
                        <div class="datos-grid">
                            <div class="dato full">
                                <div class="lbl">Celular / WhatsApp</div>
                                <div class="val">
                                    @if($reserva->cliente->telefono)
                                        <a href="https://wa.me/51{{ $reserva->cliente->telefono }}" target="_blank"
                                           style="color:var(--green);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;">
                                            <i class="bi bi-whatsapp"></i> +51 {{ $reserva->cliente->telefono }}
                                        </a>
                                    @else
                                        <span style="color:var(--ink-4);">—</span>
                                    @endif
                                </div>
                            </div>

                            @if($reserva->cliente->telefono2 ?? false)
                            <div class="dato full">
                                <div class="lbl">Teléfono alternativo</div>
                                <div class="val">{{ $reserva->cliente->telefono2 }}</div>
                            </div>
                            @endif

                            <div class="dato full">
                                <div class="lbl">Correo electrónico</div>
                                <div class="val">
                                    @if($reserva->cliente->email)
                                        <a href="mailto:{{ $reserva->cliente->email }}"
                                           style="color:var(--blue);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;">
                                            <i class="bi bi-envelope" style="font-size:.8rem;"></i>
                                            {{ $reserva->cliente->email }}
                                        </a>
                                    @else
                                        <span style="color:var(--ink-4);">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOUR --}}
            <div class="col-md-6">
                <div class="info-card" style="margin-bottom:0;height:100%;">
                    <div class="info-card-header">
                        <div class="hdr-left"><i class="bi bi-map"></i> Tour</div>
                    </div>
                    <div class="info-card-body">
                        <div class="datos-grid">
                            <div class="dato full">
                                <div class="lbl">Tour</div>
                                <div class="val">{{ $reserva->nombre_tour ?? ($reserva->fechaTour?->tour?->nombre ?? '—') }}</div>
                            </div>
                            <div class="dato full">
                                <div class="lbl">Fecha y hora de salida</div>
                                <div class="val mono">
                                    {{ $reserva->fecha_tour ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y') : ($reserva->fechaTour?->fecha ? \Carbon\Carbon::parse($reserva->fechaTour->fecha)->format('d/m/Y') : '—') }}
                                    &nbsp;—&nbsp;
                                    {{ $reserva->hora_salida ?? $reserva->fechaTour?->hora_salida ?? '—' }}
                                </div>
                            </div>
                            <div class="dato">
                                <div class="lbl">Adultos</div>
                                <div class="val">{{ $reserva->cantidad_adultos }}</div>
                            </div>
                            <div class="dato">
                                <div class="lbl">Niños</div>
                                <div class="val">{{ $reserva->cantidad_ninos > 0 ? $reserva->cantidad_ninos : '—' }}</div>
                            </div>
                            <div class="dato">
                                <div class="lbl">Total pasajeros</div>
                                <div class="val">{{ $reserva->cantidad_adultos + $reserva->cantidad_ninos }}</div>
                            </div>
                            <div class="dato">
                                <div class="lbl">Procedencia</div>
                                <div class="val">{{ $reserva->ciudad_procedencia ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── PASAJEROS ── --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-people"></i> Pasajeros</div>
                <span style="font-size:.75rem;color:var(--ink-4);text-transform:none;letter-spacing:0;font-weight:400;">
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
                        <th class="th-center">Edad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reserva->pasajeros as $pasajero)
                    <tr>
                        <td style="font-weight:600;">{{ $pasajero->nombre_completo }}</td>
                        <td>
                            <span style="background:var(--line-2);border-radius:6px;padding:2px 8px;font-size:.74rem;font-weight:600;color:var(--ink-3);">
                                {{ ucfirst($pasajero->tipo) }}
                            </span>
                        </td>
                        <td style="font-family:'DM Mono',monospace;font-size:.78rem;">
                            {{ $pasajero->tipo_documento ? $pasajero->tipo_documento.': '.$pasajero->numero_documento : '—' }}
                        </td>
                        <td class="td-center" style="font-family:'DM Mono',monospace;">{{ $pasajero->edad ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding:1.5rem;text-align:center;color:var(--ink-4);font-size:.84rem;">
                <i class="bi bi-people" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                Sin pasajeros adicionales registrados
            </div>
            @endif
        </div>

        {{-- ── PAGOS ── --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-credit-card"></i> Pagos registrados</div>
            </div>

            {{-- Barra de progreso ── --}}
            @php
                $total  = $reserva->precio_total ?? 0;
                $pagado = $reserva->monto_pagado ?? 0;
                $saldo  = max(0, $total - $pagado);
                $pct    = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
                $barColor = $pct >= 100 ? 'var(--green)' : ($pct >= 50 ? 'var(--blue)' : 'var(--amber)');
                $barColorHex = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#1d4ed8' : '#d97706');
            @endphp

            <div class="progress-wrap">
                <div class="progress-header">
                    <span style="font-size:.73rem;font-weight:700;color:var(--ink-3);">Progreso de pago</span>
                    <span id="pago-pct-label"
                          style="font-size:.78rem;font-weight:700;color:{{ $barColor }};font-family:'DM Mono',monospace;">
                        {{ $pct }}%
                    </span>
                </div>
                <div class="progress-track">
                    <div class="progress-fill" id="pago-progress-bar"
                         style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:.2rem;">
                    <span style="font-size:.7rem;color:var(--ink-4);">
                        Pagado: <strong style="color:var(--green);font-family:'DM Mono',monospace;">
                            S/ {{ number_format($pagado,2) }}
                        </strong>
                    </span>
                    <span style="font-size:.7rem;color:var(--ink-4);">
                        Total: <strong style="font-family:'DM Mono',monospace;">
                            S/ {{ number_format($total,2) }}
                        </strong>
                    </span>
                </div>
            </div>

            {{-- Tabla de pagos ── --}}
            <table class="inner-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th class="th-center">Tipo</th>
                        <th class="th-center">Monto</th>
                        <th>N° Operación</th>
                        <th class="th-center">Voucher</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reserva->pagos as $pago)
                    @php
                        $tipoPago = str_replace(' ','_',strtolower($pago->tipo_pago ?? ''));
                    @endphp
                    <tr>
                        {{-- Fecha --}}
                        <td style="font-family:'DM Mono',monospace;font-size:.78rem;white-space:nowrap;">
                            {{ $pago->fecha_pago->format('d/m/Y') }}
                        </td>

                        {{-- Método --}}
                        <td style="font-size:.82rem;font-weight:500;">
                            {{ $pago->metodoPago->nombre ?? '—' }}
                        </td>

                        {{-- Tipo --}}
                        <td class="td-center">
                            <span class="tipo-badge tipo-{{ $tipoPago }}">
                                {{ ucfirst(str_replace('_',' ',$pago->tipo_pago)) }}
                            </span>
                        </td>

                        {{-- Monto --}}
                        <td class="td-center">
                            <span style="font-family:'DM Mono',monospace;font-weight:700;color:var(--green);font-size:.88rem;">
                                S/ {{ number_format($pago->monto,2) }}
                            </span>
                        </td>

                        {{-- N° Operación --}}
                        <td style="font-family:'DM Mono',monospace;font-size:.75rem;color:var(--ink-4);">
                            {{ $pago->numero_operacion ?? '—' }}
                        </td>

                        {{-- Voucher desplegable --}}
                        <td class="td-center">
                            @if($pago->archivo_baucher)
                            <div>
                                <button type="button"
                                        class="voucher-toggle"
                                        onclick="toggleVoucher('voucher-{{ $pago->id }}', this)">
                                    <i class="bi bi-image"></i> Ver
                                    <i class="bi bi-chevron-down" style="font-size:.65rem;transition:transform .2s;"></i>
                                </button>
                                <div id="voucher-{{ $pago->id }}" class="voucher-panel">
                                    <img src="{{ Storage::url($pago->archivo_baucher) }}"
                                         alt="Voucher pago"
                                         onerror="this.closest('.voucher-panel').innerHTML='<span style=\'font-size:.75rem;color:var(--ink-4)\'>No se pudo cargar la imagen</span>'">
                                    <div>
                                        <a href="{{ Storage::url($pago->archivo_baucher) }}" target="_blank" class="ver-full">
                                            <i class="bi bi-box-arrow-up-right"></i> Abrir en nueva pestaña
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @else
                                <span style="color:var(--ink-4);font-size:.75rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2rem;color:var(--ink-4);font-size:.84rem;">
                            <i class="bi bi-cash-stack" style="font-size:1.4rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            Sin pagos registrados aún
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Resumen ── --}}
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

            {{-- Botón completar pago (50% → pagado) ── --}}
            @php
                $estadoSlugCheck = str_replace(' ','_',strtolower($reserva->estado->nombre ?? ''));
                $estadosShow    = isset($estados) ? $estados : \App\Models\EstadoReserva::all();
                $estadoPagadoId  = $estadosShow->firstWhere('nombre','pagado')?->id ?? null;
            @endphp
            @if($estadoSlugCheck === 'mitad_pago' && $estadoPagadoId)
            <div style="padding:.875rem 1.25rem;border-top:1px solid var(--line);background:var(--blue-l);">
                <form method="POST" action="{{ route('reservas.cambiarEstado', $reserva) }}"
                      style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;"
                      id="form-completar-pago">
                    @csrf @method('POST')
                    <input type="hidden" name="estado_id" value="{{ $estadoPagadoId }}">
                    <input type="hidden" name="motivo" value="Pago completado desde vista de reserva">
                    <div style="font-size:.82rem;font-weight:600;color:#1e40af;display:flex;align-items:center;gap:.4rem;">
                        <i class="bi bi-info-circle-fill"></i>
                        Esta reserva tiene el 50% pagado. ¿Completar pago?
                    </div>
                    <button type="submit"
                            class="btn-sm-green"
                            style="background:#1d4ed8;"
                            onclick="return confirm('¿Marcar como pagada al 100%?')"
                            onmouseenter="this.style.background='#1e40af'"
                            onmouseleave="this.style.background='#1d4ed8'">
                        <i class="bi bi-check-circle-fill"></i> Completar pago al 100%
                    </button>
                </form>
            </div>
            @endif
        </div>

    </div>

    {{-- ═══════ COLUMNA DERECHA ═══════ --}}
    <div class="col-lg-4">

        {{-- ── HISTORIAL DE ESTADOS ── --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="hdr-left"><i class="bi bi-clock-history"></i> Historial de estados</div>
                <span style="font-size:.74rem;color:var(--ink-4);text-transform:none;letter-spacing:0;font-weight:400;">
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
                        'mitad_pago'  => ['bg'=>'#eff6ff','border'=>'#1d4ed8','icon'=>'bi-currency-dollar','text'=>'#1e40af'],
                        'pagado'      => ['bg'=>'#f0fdf4','border'=>'#15803d','icon'=>'bi-check-all','text'=>'#15803d'],
                        'cancelada'   => ['bg'=>'#fef2f2','border'=>'#dc2626','icon'=>'bi-x-circle','text'=>'#991b1b'],
                        'finalizada'  => ['bg'=>'#f5f3ff','border'=>'#7c3aed','icon'=>'bi-star-fill','text'=>'#5b21b6'],
                    ];
                @endphp

                @forelse($historial as $h)
                @php
                    $slug = strtolower(str_replace(' ','_',$h->estadoNuevo->nombre ?? 'consulta'));
                    $dc   = $dotColors[$slug] ?? $dotColors['consulta'];
                    $fecha = \Carbon\Carbon::parse($h->fecha_cambio);
                    // Detectar si es un cambio de 50% → pagado para mostrarlo especial
                    $slugAnterior = strtolower(str_replace(' ','_',$h->estadoAnterior->nombre ?? ''));
                    $esPagoCompleto = ($slug === 'pagado' && $slugAnterior === 'mitad_pago');
                @endphp
                <div class="timeline-item">
                    <div class="tl-left">
                        <div class="tl-dot"
                             style="background:{{ $dc['bg'] }};border-color:{{ $dc['border'] }};color:{{ $dc['text'] }};">
                            <i class="bi {{ $dc['icon'] }}" style="font-size:.72rem;"></i>
                        </div>
                        @if(!$loop->last)
                        <div class="tl-line"></div>
                        @endif
                    </div>
                    <div class="tl-right">
                        <div class="tl-estado" style="color:{{ $dc['text'] }};">
                            {{ ucfirst(str_replace('_',' ',$h->estadoNuevo->nombre ?? '')) }}
                            @if($esPagoCompleto)
                                <span style="font-size:.67rem;font-weight:600;background:#f0fdf4;color:#15803d;padding:1px 7px;border-radius:20px;margin-left:.3rem;vertical-align:middle;">
                                    ✓ Pago completo
                                </span>
                            @endif
                        </div>
                        <div class="tl-meta">
                            <span>
                                <i class="bi bi-calendar3" style="font-size:.63rem;"></i>
                                {{ $fecha->format('d/m/Y') }}
                            </span>
                            <span>
                                <i class="bi bi-clock" style="font-size:.63rem;"></i>
                                {{ $fecha->format('H:i') }}
                            </span>
                            @if(isset($h->usuarioAdmin))
                            <span>
                                <i class="bi bi-person" style="font-size:.63rem;"></i>
                                {{ $h->usuarioAdmin->nombre_completo ?? 'Sistema' }}
                            </span>
                            @endif
                        </div>
                        @if($slugAnterior && $slugAnterior !== $slug)
                        <div style="font-size:.68rem;color:var(--ink-4);margin-top:.2rem;display:flex;align-items:center;gap:.3rem;">
                            <span style="opacity:.7;">{{ ucfirst(str_replace('_',' ',$slugAnterior)) }}</span>
                            <i class="bi bi-arrow-right" style="font-size:.6rem;"></i>
                            <span style="color:{{ $dc['text'] }};font-weight:600;">{{ ucfirst(str_replace('_',' ',$h->estadoNuevo->nombre ?? '')) }}</span>
                        </div>
                        @endif
                        @if($h->motivo)
                        <div class="tl-motivo">"{{ $h->motivo }}"</div>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding:.75rem 0 .25rem;text-align:center;color:var(--ink-4);font-size:.83rem;">
                    <i class="bi bi-clock-history" style="display:block;font-size:1.4rem;margin-bottom:.4rem;opacity:.3;"></i>
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
                <div class="datos-grid">
                    <div class="dato">
                        <div class="lbl">Hora de recojo</div>
                        <div class="val mono">{{ $reserva->logistica->hora_recojo ?? '—' }}</div>
                    </div>
                    <div class="dato">
                        <div class="lbl">Guía asignado</div>
                        <div class="val">{{ $reserva->logistica->nombre_guia ?? '—' }}</div>
                    </div>
                    <div class="dato full">
                        <div class="lbl">Punto de encuentro</div>
                        <div class="val">{{ $reserva->logistica->direccion_recojo ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
/* ── Voucher desplegable ── */
function toggleVoucher(id, btn) {
    const panel   = document.getElementById(id);
    const chevron = btn.querySelector('.bi-chevron-down, .bi-chevron-up');
    const isOpen  = panel.classList.contains('open');

    // Cerrar todos los demás
    document.querySelectorAll('.voucher-panel.open').forEach(p => {
        if (p.id !== id) {
            p.classList.remove('open');
            const otherBtn = document.querySelector('[onclick*="' + p.id + '"]');
            if (otherBtn) {
                const ch = otherBtn.querySelector('i:last-child');
                if (ch) { ch.className = 'bi bi-chevron-down'; ch.style.transform = ''; }
            }
        }
    });

    panel.classList.toggle('open');
    if (chevron) {
        if (panel.classList.contains('open')) {
            chevron.className = 'bi bi-chevron-up';
            chevron.style.transform = '';
        } else {
            chevron.className = 'bi bi-chevron-down';
            chevron.style.transform = '';
        }
    }
}

/* ── Actualización de barra de progreso después de "Completar pago" ──
   Si el formulario form-completar-pago existe, al enviarlo animamos la barra
   antes de que la página recargue para una transición suave. ── */
const formCompletarPago = document.getElementById('form-completar-pago');
if (formCompletarPago) {
    formCompletarPago.addEventListener('submit', function(e) {
        // Animar la barra al 100% antes del redirect del servidor
        const bar   = document.getElementById('pago-progress-bar');
        const label = document.getElementById('pago-pct-label');
        if (bar)   { bar.style.width = '100%'; bar.style.background = '#059669'; }
        if (label) { label.textContent = '100%'; label.style.color = '#059669'; }
        // Dejar que el form continúe normalmente (no se previene el default)
    });
}
</script>
@endpush

@endsection