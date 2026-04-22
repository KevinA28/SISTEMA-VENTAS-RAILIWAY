{{-- =====================================================================
     ARCHIVO: index.blade.php
     UBICACIÓN: resources/views/reservas/index.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Reservas')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

/* ── TOP BAR ── */
.page-topbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
    margin-bottom: 1.25rem;
}
.page-topbar .page-title {
    font-size: 1.35rem; font-weight: 700; color: var(--ink);
    display: flex; align-items: center; gap: .5rem;
}
.page-topbar .page-title i { color: var(--blue); font-size: 1.25rem; }
.page-topbar .page-subtitle { font-size: .82rem; color: var(--ink-4); margin-top: 2px; }

/* ── BOTÓN REPORTES destacado ── */
.btn-reportes {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: 10px 20px; border-radius: 10px;
    font-size: .88rem; font-weight: 700; font-family: 'DM Sans', sans-serif;
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
    color: #fff; border: none; cursor: not-allowed; opacity: .75;
    text-decoration: none; transition: all .2s;
    box-shadow: 0 2px 8px rgba(124,58,237,.25);
}
.btn-reportes:not([disabled]):not(.disabled):hover {
    opacity: 1; box-shadow: 0 4px 16px rgba(124,58,237,.4);
    transform: translateY(-1px);
}

/* ── FILTROS ── */
.filtros-card {
    background: white; border: 1px solid var(--line);
    border-radius: 14px; padding: 1rem 1.25rem;
    margin-bottom: 1rem; display: flex;
    gap: .75rem; align-items: center; flex-wrap: wrap;
}

/* Barra de búsqueda */
.search-wrap {
    position: relative; flex: 1; min-width: 220px;
}
.search-wrap i.ico {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--ink-4); font-size: .85rem; pointer-events: none;
    transition: color .15s;
}
.search-wrap:focus-within i.ico { color: var(--blue); }
.input-search {
    width: 100%; padding: 9px 36px 9px 34px;
    border: 1.5px solid var(--line); border-radius: 10px;
    font-size: .85rem; font-family: 'DM Sans', sans-serif;
    color: var(--ink); background: white; outline: none;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.input-search:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px #dbeafe;
}
.input-search::placeholder { color: var(--ink-4); }
.search-clear {
    position: absolute; right: 9px; top: 50%; transform: translateY(-50%);
    background: none; border: none; cursor: pointer; color: var(--ink-4);
    padding: 2px 4px; display: none; font-size: .8rem; line-height: 1;
}
.search-clear:hover { color: var(--red); }
.input-search:not(:placeholder-shown) + .search-clear,
.search-wrap.has-value .search-clear { display: flex; align-items: center; }

/* Selector canal */
.f-ctrl {
    padding: 9px 12px; border: 1.5px solid var(--line); border-radius: 10px;
    font-size: .84rem; font-family: 'DM Sans', sans-serif;
    color: var(--ink); background: white; outline: none;
    transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none; appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px;
}
.f-ctrl:focus { border-color: var(--blue); box-shadow: 0 0 0 3px #dbeafe; }

/* Inputs de fecha (flatpickr) */
.date-wrap { position: relative; }
.date-wrap i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--ink-4); font-size: .8rem; pointer-events: none; }
.input-date {
    padding: 9px 12px 9px 30px; border: 1.5px solid var(--line);
    border-radius: 10px; font-size: .84rem; font-family: 'DM Sans', sans-serif;
    color: var(--ink); background: white; outline: none; width: 130px; cursor: pointer;
    transition: border-color .15s, box-shadow .15s;
}
.input-date:focus { border-color: var(--blue); box-shadow: 0 0 0 3px #dbeafe; }
.input-date.active { border-color: var(--blue); background: var(--blue-l); }

/* ── BOTONES FILTRO ESTADO ── */
.estado-btns { display: flex; gap: .4rem; flex-wrap: wrap; align-items: center; }
.btn-estado {
    padding: 7px 14px; border-radius: 20px; font-size: .79rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    border: 1.5px solid var(--line); background: white; color: var(--ink-3);
    transition: all .15s; white-space: nowrap;
    display: inline-flex; align-items: center; gap: .3rem;
}
.btn-estado::before { content:''; width:6px; height:6px; border-radius:50%; background: currentColor; flex-shrink:0; }
.btn-estado:hover { border-color: var(--ink-3); color: var(--ink-2); }
.btn-estado.active-pagado  { background: var(--green-l); border-color: var(--green); color: var(--green); }
.btn-estado.active-cancelado { background: var(--red-l); border-color: var(--red); color: var(--red); }
.btn-estado.active-mitad  { background: var(--blue-l); border-color: var(--blue); color: var(--blue); }

/* Divisor vertical */
.filtros-divider { width: 1px; height: 28px; background: var(--line); flex-shrink: 0; }

.btn-clear {
    background: transparent; color: var(--ink-4); border: 1.5px solid var(--line);
    padding: 8px 12px; border-radius: 10px; font-size: .84rem; cursor: pointer;
    display: inline-flex; align-items: center; transition: all .15s; text-decoration: none;
}
.btn-clear:hover { color: var(--red); border-color: var(--red); }

/* ── TARJETAS DE RESERVA ── */
.reservas-card {
    background: white; border: 1px solid var(--line);
    border-radius: 14px; overflow: hidden;
}
.reservas-header {
    padding: .9rem 1.25rem; border-bottom: 1px solid var(--line);
    display: flex; justify-content: space-between; align-items: center;
}
.reservas-header .title {
    font-size: .9rem; font-weight: 700; color: var(--ink);
    display: flex; align-items: center; gap: .5rem;
}
.reservas-header .title .count {
    background: var(--line-2); border-radius: 999px;
    padding: 2px 10px; font-size: .72rem; font-weight: 700; color: var(--ink-3);
}

/* Grid de tarjetas */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1rem; padding: 1.25rem;
}

.reserva-card {
    border: 1.5px solid var(--line); border-radius: 12px;
    padding: 1rem 1.1rem; background: white;
    transition: box-shadow .15s, border-color .15s, transform .1s;
    display: flex; flex-direction: column; gap: .75rem;
    position: relative;
}
.reserva-card:hover {
    border-color: var(--blue-m);
    box-shadow: 0 4px 16px rgba(29,78,216,.08);
    transform: translateY(-1px);
}

/* Cabecera de tarjeta */
.rc-head { display: flex; justify-content: space-between; align-items: flex-start; gap: .5rem; }
.codigo-reserva {
    font-family: 'DM Mono', monospace; font-size: .76rem; font-weight: 600;
    color: var(--blue); background: var(--blue-l); padding: 3px 9px;
    border-radius: 6px; white-space: nowrap; text-decoration: none;
    transition: background .15s;
}
.codigo-reserva:hover { background: var(--blue-m); }

/* Cuerpo de tarjeta */
.rc-cliente { display: flex; flex-direction: column; gap: 2px; }
.rc-cliente .nombre { font-weight: 700; font-size: .88rem; color: var(--ink); }
.rc-cliente .tel { font-size: .73rem; color: var(--ink-4); display: flex; align-items: center; gap: .3rem; }

.rc-tour { font-size: .83rem; color: var(--ink-2); font-weight: 500; display: flex; align-items: center; gap: .35rem; }
.rc-tour i { color: var(--ink-4); font-size: .75rem; }

.rc-fecha-hora { display: flex; align-items: center; gap: .5rem; font-size: .78rem; color: var(--ink-3); }
.rc-fecha-hora .fecha { font-weight: 600; color: var(--ink-2); }
.rc-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--line); flex-shrink:0; }

/* Barra de pago */
.rc-pago { display: flex; flex-direction: column; gap: 4px; }
.rc-pago-row { display: flex; align-items: center; gap: .5rem; }
.pago-bar { flex: 1; height: 5px; background: var(--line); border-radius: 999px; overflow: hidden; }
.pago-bar-fill { height: 100%; border-radius: 999px; transition: width .4s; }
.pago-pct { font-size: .75rem; font-weight: 700; font-family: 'DM Mono', monospace; white-space: nowrap; }
.pago-detalle { font-size: .71rem; color: var(--ink-4); font-family: 'DM Mono', monospace; }

/* Footer de tarjeta */
.rc-footer { display: flex; justify-content: space-between; align-items: center; gap: .5rem; flex-wrap: wrap; }
.rc-footer-left { display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; }

/* Badges */
.badge-estado {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 4px 10px; border-radius: 999px;
    font-size: .7rem; font-weight: 700; white-space: nowrap;
}
.badge-estado::before { content:''; width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0; }
.est-consulta    { background:#f1f5f9; color:#475569; }
.est-pre_reserva { background:#fffbeb; color:#92400e; }
.est-confirmada  { background:#ecfdf5; color:#065f46; }
.est-mitad_pago  { background:#eff6ff; color:#1e40af; }
.est-pagado      { background:#f0fdf4; color:#15803d; }
.est-cancelada   { background:#fef2f2; color:#991b1b; }
.est-finalizada  { background:#f5f3ff; color:#5b21b6; }

.canal-badge { display:inline-flex;align-items:center;gap:.3rem;font-size:.73rem;color:var(--ink-3);font-weight:500; }

/* Acciones */
.btn-tabla {
    padding: 5px 12px; border-radius: 8px; font-size: .78rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    display: inline-flex; align-items: center; gap: .3rem;
    text-decoration: none; transition: all .15s;
    border: 1.5px solid var(--line); color: var(--ink-2); background: white;
}
.btn-tabla:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }

.btn-completar {
    padding: 5px 12px; border-radius: 8px; font-size: .78rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    display: inline-flex; align-items: center; gap: .3rem;
    border: 1.5px solid #059669; color: #059669; background: #ecfdf5;
    transition: all .15s;
}
.btn-completar:hover { background: #059669; color: white; }

.empty-res { text-align:center; padding:4rem 1rem; color:var(--ink-4); grid-column: 1/-1; }
.empty-res i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.4; }
.empty-res p { font-size:.88rem; margin-bottom:1rem; }

.pag-footer {
    padding: .875rem 1.25rem; border-top: 1px solid var(--line);
    display: flex; justify-content: space-between; align-items: center; background: var(--line-2);
}
.pag-info { font-size: .78rem; color: var(--ink-4); }

/* ── Flatpickr override ── */
.flatpickr-calendar {
    border-radius: 12px !important;
    box-shadow: 0 8px 30px rgba(0,0,0,.12) !important;
    border: 1px solid var(--line) !important;
    font-family: 'DM Sans', sans-serif !important;
    font-size: .83rem !important;
}
.flatpickr-day.selected,
.flatpickr-day.selected:hover { background: var(--blue) !important; border-color: var(--blue) !important; }
.flatpickr-day:hover { background: var(--blue-l) !important; }
.flatpickr-months .flatpickr-month { border-radius: 12px 12px 0 0 !important; }

/* Responsive */
@media (max-width: 640px) {
    .cards-grid { grid-template-columns: 1fr; }
    .filtros-card { flex-direction: column; align-items: stretch; }
    .estado-btns { justify-content: flex-start; }
    .filtros-divider { display: none; }
}
</style>
@endpush

@section('contenido')

{{-- ── TOP BAR ── --}}
<div class="page-topbar">
    <div>
        <div class="page-title">
            <i class="bi bi-calendar-check-fill"></i>
            Reservas
        </div>
        <div class="page-subtitle">Gestiona y monitorea todas las reservas</div>
    </div>
    <a href="#" class="btn-reportes" title="Próximamente" aria-disabled="true">
        <i class="bi bi-file-earmark-bar-graph-fill"></i>
        Generar Reporte
    </a>
</div>

{{-- ── FILTROS ── --}}
<form method="GET" action="{{ route('reservas.index') }}" id="form-filtros">

    {{-- Fila 1: búsqueda + canal + fechas --}}
    <div class="filtros-card">
        {{-- Búsqueda --}}
        <div class="search-wrap" id="search-wrap">
            <i class="bi bi-search ico"></i>
            <input type="text" name="buscar" id="input-buscar"
                value="{{ request('buscar') }}"
                class="input-search"
                placeholder="Buscar código, nombre, DNI o tour…"
                autocomplete="off"
                spellcheck="false">
            <button type="button" class="search-clear" id="btn-search-clear" tabindex="-1" aria-label="Limpiar búsqueda">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <div class="filtros-divider"></div>

        {{-- Canal --}}
        <select name="canal" class="f-ctrl" style="min-width:145px;" onchange="submitForm()">
            <option value="">Todos los canales</option>
            <option value="whatsapp"       {{ request('canal')=='whatsapp'       ? 'selected':'' }}>WhatsApp</option>
            <option value="presencial"     {{ request('canal')=='presencial'     ? 'selected':'' }}>Presencial</option>
            <option value="llamada"        {{ request('canal')=='llamada'        ? 'selected':'' }}>Llamada</option>
            <option value="redes_sociales" {{ request('canal')=='redes_sociales' ? 'selected':'' }}>Redes Sociales</option>
            <option value="web"            {{ request('canal')=='web'            ? 'selected':'' }}>Web</option>
        </select>

        {{-- Fecha desde --}}
        <div class="date-wrap">
            <i class="bi bi-calendar3"></i>
            <input type="text" name="fecha_desde" id="fecha-desde"
                value="{{ request('fecha_desde') }}"
                class="input-date {{ request('fecha_desde') ? 'active' : '' }}"
                placeholder="Desde" readonly>
        </div>

        {{-- Fecha hasta --}}
        <div class="date-wrap">
            <i class="bi bi-calendar3"></i>
            <input type="text" name="fecha_hasta" id="fecha-hasta"
                value="{{ request('fecha_hasta') }}"
                class="input-date {{ request('fecha_hasta') ? 'active' : '' }}"
                placeholder="Hasta" readonly>
        </div>

        @if(request()->hasAny(['buscar','estado','canal','fecha_desde','fecha_hasta']))
            <a href="{{ route('reservas.index') }}" class="btn-clear" title="Limpiar filtros">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
    </div>

    {{-- Fila 2: botones de estado --}}
    <div style="margin-bottom:1.1rem;">
        <div class="estado-btns">
            <span style="font-size:.78rem;color:var(--ink-4);font-weight:600;margin-right:.2rem;">Estado:</span>

            @php
                $estadoPagadoNombre    = 'pagado';
                $estadoCanceladoNombre = 'cancelada';
                $estadoMitadNombre     = 'mitad_pago';
                $estadoActual          = request('estado');

                // Buscar IDs por nombre
                $idPagado    = $estados->firstWhere('nombre', $estadoPagadoNombre)?->id;
                $idCancelado = $estados->firstWhere('nombre', $estadoCanceladoNombre)?->id;
                $idMitad     = $estados->firstWhere('nombre', $estadoMitadNombre)?->id;
            @endphp

            <button type="button" class="btn-estado {{ $estadoActual == $idPagado ? 'active-pagado' : '' }}"
                data-estado="{{ $idPagado }}" onclick="toggleEstado(this)">
                Pagado
            </button>
            <button type="button" class="btn-estado {{ $estadoActual == $idCancelado ? 'active-cancelado' : '' }}"
                data-estado="{{ $idCancelado }}" data-class="active-cancelado" onclick="toggleEstado(this)">
                Cancelado
            </button>
            <button type="button" class="btn-estado {{ $estadoActual == $idMitad ? 'active-mitad' : '' }}"
                data-estado="{{ $idMitad }}" data-class="active-mitad" onclick="toggleEstado(this)">
                50% Pagado
            </button>
        </div>
    </div>

    {{-- Input oculto para estado --}}
    <input type="hidden" name="estado" id="input-estado" value="{{ request('estado') }}">

</form>

{{-- ── TARJETAS ── --}}
<div class="reservas-card">
    <div class="reservas-header">
        <div class="title">
            <i class="bi bi-grid-3x3-gap-fill" style="color:var(--blue)"></i>
            Lista de Reservas
            <span class="count">{{ $reservas->total() ?? $reservas->count() }}</span>
        </div>
    </div>

    <div class="cards-grid">
        @forelse($reservas as $reserva)
        @php
            $total      = $reserva->precio_total ?? 0;
            $pagado     = $reserva->monto_pagado ?? 0;
            $pct        = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
            $barColor   = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#1d4ed8' : '#d97706');
            $estadoSlug = str_replace(' ','_', strtolower($reserva->estado->nombre ?? 'consulta'));
            $canales    = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2'];
            $estadoPagadoId = $estados->firstWhere('nombre','pagado')?->id ?? null;

            $fechaTour = $reserva->fecha_tour
                ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y')
                : ($reserva->fechaTour?->fecha ? \Carbon\Carbon::parse($reserva->fechaTour->fecha)->format('d/m/Y') : '—');
            $horaTour = $reserva->hora_salida ?? $reserva->fechaTour?->hora_salida ?? null;
            $nombreTour = $reserva->nombre_tour ?? $reserva->fechaTour?->tour?->nombre ?? '—';
        @endphp

        <div class="reserva-card">
            {{-- Cabecera --}}
            <div class="rc-head">
                <a href="{{ route('reservas.show', $reserva) }}" class="codigo-reserva">
                    {{ $reserva->codigo_reserva }}
                </a>
                <span class="badge-estado est-{{ $estadoSlug }}">
                    {{ ucfirst(str_replace('_',' ',$reserva->estado->nombre)) }}
                </span>
            </div>

            {{-- Cliente --}}
            <div class="rc-cliente">
                <span class="nombre">{{ $reserva->cliente->nombre_completo }}</span>
                <span class="tel">
                    <i class="bi bi-telephone-fill"></i>
                    {{ $reserva->cliente->telefono ?? '—' }}
                </span>
            </div>

            {{-- Tour --}}
            <div class="rc-tour">
                <i class="bi bi-geo-alt-fill"></i>
                {{ $nombreTour }}
            </div>

            {{-- Fecha / Hora --}}
            <div class="rc-fecha-hora">
                <i class="bi bi-calendar-event" style="font-size:.75rem;"></i>
                <span class="fecha">{{ $fechaTour }}</span>
                @if($horaTour)
                    <span class="rc-dot"></span>
                    <i class="bi bi-clock" style="font-size:.7rem;"></i>
                    {{ $horaTour }}
                @endif
            </div>

            {{-- Pago --}}
            <div class="rc-pago">
                <div class="rc-pago-row" title="S/ {{ number_format($pagado,2) }} de S/ {{ number_format($total,2) }}">
                    <div class="pago-bar">
                        <div class="pago-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                    </div>
                    <span class="pago-pct" style="color:{{ $barColor }}">{{ $pct }}%</span>
                </div>
                <div class="pago-detalle">
                    S/ {{ number_format($pagado,2) }} / {{ number_format($total,2) }}
                </div>
            </div>

            {{-- Footer --}}
            <div class="rc-footer">
                <div class="rc-footer-left">
                    <span class="canal-badge">
                        <i class="bi {{ $canales[$reserva->canal_contacto] ?? 'bi-chat' }}"></i>
                        {{ ucfirst(str_replace('_',' ',$reserva->canal_contacto ?? '')) }}
                    </span>
                </div>
                <div style="display:flex;gap:.4rem;align-items:center;">
                    <a href="{{ route('reservas.show', $reserva) }}" class="btn-tabla">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                    @if($estadoSlug === 'mitad_pago' && $estadoPagadoId)
                    <form method="POST" action="{{ route('reservas.cambiarEstado', $reserva) }}" style="margin:0;">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="estado_id" value="{{ $estadoPagadoId }}">
                        <input type="hidden" name="motivo" value="Pago completado desde lista de reservas">
                        <button type="submit" class="btn-completar"
                            onclick="return confirm('¿Marcar esta reserva como pagada?')">
                            <i class="bi bi-check-circle"></i> Completar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-res">
            <i class="bi bi-calendar-x"></i>
            <p>No hay reservas que coincidan con los filtros.</p>
        </div>
        @endforelse
    </div>

    @if(method_exists($reservas, 'hasPages') && $reservas->hasPages())
    <div class="pag-footer">
        <div class="pag-info">
            Mostrando {{ $reservas->firstItem() }}–{{ $reservas->lastItem() }} de {{ $reservas->total() }} reservas
        </div>
        {{ $reservas->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
(function(){
    /* ── Flatpickr calendarios ── */
    const fpConfig = {
        locale: 'es',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        allowInput: false,
        disableMobile: true,
        onChange: function(){ submitForm(); }
    };
    flatpickr('#fecha-desde', fpConfig);
    flatpickr('#fecha-hasta', fpConfig);

    /* ── Búsqueda AJAX: sin perder foco ni posición del cursor ── */
    const input   = document.getElementById('input-buscar');
    const clearBtn = document.getElementById('btn-search-clear');
    const wrap    = document.getElementById('search-wrap');

    if (input) {
        let timer;
        let fetchId = 0; // para ignorar respuestas de peticiones viejas

        function updateClear(){
            wrap.classList.toggle('has-value', input.value.length > 0);
        }
        updateClear();

        input.addEventListener('input', function(){
            updateClear();
            clearTimeout(timer);
            timer = setTimeout(function(){

                // ── Capturar estado AHORA (no cuando empezó el timer) ──
                const query     = input.value;
                const caretEnd  = input.selectionEnd; // posición real actual

                const url = new URL(window.location.href);
                url.searchParams.set('buscar', query);

                const thisId = ++fetchId;

                fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        // Descartar si ya hay una petición más nueva
                        if (thisId !== fetchId) return;

                        const parser   = new DOMParser();
                        const doc      = parser.parseFromString(html, 'text/html');
                        const newGrid  = doc.querySelector('.cards-grid');
                        const newCount = doc.querySelector('.reservas-header .count');
                        const newPag   = doc.querySelector('.pag-footer');
                        const curGrid  = document.querySelector('.cards-grid');
                        const curCount = document.querySelector('.reservas-header .count');
                        const curPag   = document.querySelector('.pag-footer');

                        if (newGrid  && curGrid)  curGrid.replaceWith(newGrid);
                        if (newCount && curCount) curCount.textContent = newCount.textContent;
                        if (newPag   && curPag)   curPag.replaceWith(newPag);
                        else if (!newPag && curPag) curPag.remove();

                        window.history.replaceState({}, '', url.toString());

                        // ── Restaurar foco y cursor SIN tocar input.value ──
                        // El valor del input NO cambió (el usuario lo escribió),
                        // solo restauramos la posición del cursor tras el DOM update.
                        requestAnimationFrame(function(){
                            input.focus();
                            input.setSelectionRange(caretEnd, caretEnd);
                        });
                    })
                    .catch(() => {
                        // Fallback silencioso: no hacer submit completo para no
                        // interrumpir al usuario; simplemente no actualiza la lista.
                        console.warn('Búsqueda AJAX falló, reintentando en próxima tecla.');
                    });
            }, 400);
        });

        if (clearBtn) {
            clearBtn.addEventListener('click', function(){
                input.value = '';
                wrap.classList.remove('has-value');
                input.focus();
                submitForm();
            });
        }
    }

    /* ── Submit del formulario ── */
    window.submitForm = function(){
        document.getElementById('form-filtros').submit();
    };

    /* ── Botones de estado ── */
    const estadoMap = {
        'pagado': 'active-pagado',
        'cancelado': 'active-cancelado',
        'mitad': 'active-mitad'
    };

    window.toggleEstado = function(btn){
        const estadoId = btn.dataset.estado;
        const inputEstado = document.getElementById('input-estado');
        const allBtns = document.querySelectorAll('.btn-estado');

        // Detectar la clase activa según el botón
        const classes = ['active-pagado','active-cancelado','active-mitad'];
        const activeClass = classes.find(c => btn.classList.contains(c));

        // Si ya está activo, deseleccionar
        if (activeClass) {
            btn.classList.remove(activeClass);
            inputEstado.value = '';
        } else {
            // Desactivar todos
            allBtns.forEach(b => b.classList.remove(...classes));
            // Activar el clickeado
            const map = { 'btn-estado active-pagado': 'active-pagado' };
            // Determinar qué clase poner
            const text = btn.textContent.trim().toLowerCase();
            let cls = 'active-pagado';
            if (text.includes('cancelad')) cls = 'active-cancelado';
            else if (text.includes('50')) cls = 'active-mitad';
            btn.classList.add(cls);
            inputEstado.value = estadoId;
        }

        submitForm();
    };

})();
</script>
@endpush

@endsection