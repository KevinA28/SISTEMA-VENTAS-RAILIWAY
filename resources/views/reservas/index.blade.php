{{-- =====================================================================
     ARCHIVO: index.blade.php
     UBICACIÓN: resources/views/reservas/index.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Reservas')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --ink: #0d1117; --ink-2: #374151; --ink-3: #6b7280; --ink-4: #9ca3af;
    --line: #e5e7eb; --line-2: #f3f4f6;
    --blue: #1d4ed8; --blue-l: #eff6ff; --blue-m: #dbeafe;
    --green: #059669; --amber: #d97706; --red: #dc2626; --purple: #7c3aed;
}
body { font-family: 'DM Sans', sans-serif; }

/* ── FILTROS ── */
.filtros-card {
    background: white; border: 1px solid var(--line);
    border-radius: 12px; padding: 1rem 1.25rem;
    margin-bottom: 1.25rem; display: flex;
    gap: .75rem; align-items: center; flex-wrap: wrap;
}
.f-ctrl {
    padding: 8px 12px; border: 1.5px solid var(--line); border-radius: 8px;
    font-size: .84rem; font-family: 'DM Sans', sans-serif;
    color: var(--ink); background: white; outline: none; transition: border-color .15s;
    -webkit-appearance: none;
}
.f-ctrl:focus { border-color: var(--blue); box-shadow: 0 0 0 3px #dbeafe; }
.f-ctrl.search { flex: 1; min-width: 200px; }

/* ── TABLA ── */
.reservas-card { background: white; border: 1px solid var(--line); border-radius: 12px; overflow: hidden; }
.reservas-header {
    padding: .875rem 1.25rem; border-bottom: 1px solid var(--line);
    display: flex; justify-content: space-between; align-items: center;
}
.reservas-header .title {
    font-size: .88rem; font-weight: 700; color: var(--ink);
    display: flex; align-items: center; gap: .5rem;
}
.reservas-header .title .count {
    background: var(--line-2); border-radius: 999px;
    padding: 2px 10px; font-size: .72rem; font-weight: 700; color: var(--ink-3);
}

.res-table { width: 100%; border-collapse: collapse; }
.res-table thead th {
    padding: .65rem 1rem; font-size: .68rem; font-weight: 700;
    letter-spacing: .08em; text-transform: uppercase;
    color: var(--ink-4); background: var(--line-2);
    border-bottom: 1px solid var(--line); white-space: nowrap;
}
.res-table thead th:first-child { padding-left: 1.25rem; }
.res-table tbody td {
    padding: .9rem 1rem; border-bottom: 1px solid var(--line);
    font-size: .84rem; color: var(--ink-2); vertical-align: middle;
}
.res-table tbody td:first-child { padding-left: 1.25rem; }
.res-table tbody tr:last-child td { border-bottom: none; }
.res-table tbody tr:hover td { background: #fafbff; }

/* ── BADGES ── */
.badge-estado {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: 4px 10px; border-radius: 999px;
    font-size: .72rem; font-weight: 700; white-space: nowrap;
}
.badge-estado::before { content:''; width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0; }
.est-consulta    { background:#f1f5f9; color:#475569; }
.est-pre_reserva { background:#fffbeb; color:#92400e; }
.est-confirmada  { background:#ecfdf5; color:#065f46; }
.est-mitad_pago  { background:#eff6ff; color:#1e40af; }
.est-pagado      { background:#f0fdf4; color:#15803d; }
.est-cancelada   { background:#fef2f2; color:#991b1b; }
.est-finalizada  { background:#f5f3ff; color:#5b21b6; }

.canal-badge { display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;color:var(--ink-3);font-weight:500; }

.codigo-reserva {
    font-family:'DM Mono',monospace; font-size:.78rem; font-weight:600;
    color:var(--blue); background:var(--blue-l); padding:3px 8px; border-radius:6px; white-space:nowrap;
}

/* ── BOTONES ── */
.btn-tabla {
    padding:5px 12px; border-radius:7px; font-size:.78rem; font-weight:600;
    font-family:'DM Sans',sans-serif; cursor:pointer;
    display:inline-flex; align-items:center; gap:.3rem;
    text-decoration:none; transition:all .15s;
    border:1.5px solid var(--line); color:var(--ink-2); background:white;
}
.btn-tabla:hover { border-color:var(--blue); color:var(--blue); background:var(--blue-l); }

.btn-filtrar {
    background:var(--ink); color:#fff; border:none; padding:8px 16px;
    border-radius:8px; font-size:.84rem; font-weight:600; cursor:pointer;
    display:inline-flex; align-items:center; gap:.4rem;
    text-decoration:none; transition:background .15s;
}
.btn-filtrar:hover { background:var(--ink-2); }

.btn-clear {
    background:transparent; color:var(--ink-3); border:1.5px solid var(--line);
    padding:8px 12px; border-radius:8px; font-size:.84rem; cursor:pointer;
    display:inline-flex; align-items:center; transition:all .15s; text-decoration:none;
}
.btn-clear:hover { color:var(--red); border-color:var(--red); }

/* ── EMPTY ── */
.empty-res { text-align:center; padding:4rem 1rem; color:var(--ink-4); }
.empty-res i { font-size:2.5rem; display:block; margin-bottom:.75rem; }
.empty-res p { font-size:.88rem; margin-bottom:1rem; }

/* ── PAGINACIÓN ── */
.pag-footer {
    padding:.875rem 1.25rem; border-top:1px solid var(--line);
    display:flex; justify-content:space-between; align-items:center; background:var(--line-2);
}
.pag-info { font-size:.78rem; color:var(--ink-4); }

/* ── PAGO BAR ── */
.pago-bar-wrap { display:flex; align-items:center; gap:.5rem; }
.pago-bar { flex:1; height:5px; background:var(--line); border-radius:999px; overflow:hidden; min-width:50px; }
.pago-bar-fill { height:100%; border-radius:999px; transition:width .3s; }
</style>
@endpush

@section('contenido')

<form method="GET" action="{{ route('reservas.index') }}">
<div class="filtros-card">
    <div style="position:relative;flex:1;min-width:200px;">
        <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-4);font-size:.85rem;pointer-events:none;"></i>
        <input type="text" name="buscar" value="{{ request('buscar') }}"
            class="f-ctrl search" style="padding-left:32px;"
            placeholder="Buscar por código, cliente o tour...">
    </div>
    <select name="estado" class="f-ctrl" style="min-width:160px;">
        <option value="">Todos los estados</option>
        @foreach($estados as $estado)
            <option value="{{ $estado->id }}" {{ request('estado') == $estado->id ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_',' ',$estado->nombre)) }}
            </option>
        @endforeach
    </select>
    <select name="canal" class="f-ctrl" style="min-width:140px;">
        <option value="">Todos los canales</option>
        <option value="whatsapp"       {{ request('canal')=='whatsapp'       ? 'selected':'' }}>WhatsApp</option>
        <option value="presencial"     {{ request('canal')=='presencial'     ? 'selected':'' }}>Presencial</option>
        <option value="llamada"        {{ request('canal')=='llamada'        ? 'selected':'' }}>Llamada</option>
        <option value="redes_sociales" {{ request('canal')=='redes_sociales' ? 'selected':'' }}>Redes Sociales</option>
        <option value="web"            {{ request('canal')=='web'            ? 'selected':'' }}>Web</option>
    </select>
    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="f-ctrl" title="Desde">
    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="f-ctrl" title="Hasta">
    <button type="submit" class="btn-filtrar"><i class="bi bi-funnel"></i> Filtrar</button>
    @if(request()->hasAny(['buscar','estado','canal','fecha_desde','fecha_hasta']))
        <a href="{{ route('reservas.index') }}" class="btn-clear" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
    @endif
</div>
</form>

<div class="reservas-card">
    <div class="reservas-header">
        <div class="title">
            <i class="bi bi-calendar-check" style="color:var(--blue)"></i>
            Lista de Reservas
            <span class="count">{{ $reservas->total() ?? $reservas->count() }}</span>
        </div>
    </div>

    <table class="res-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Tour</th>
                <th>Fecha tour</th>
                <th>Pago</th>
                <th>Estado</th>
                <th>Canal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservas as $reserva)
            @php
                $total   = $reserva->precio_total ?? 0;
                $pagado  = $reserva->monto_pagado ?? 0;
                $pct     = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
                $barColor = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#1d4ed8' : '#d97706');
                $estadoSlug = str_replace(' ','_', strtolower($reserva->estado->nombre ?? 'consulta'));
                $canales = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2'];
            @endphp
            <tr>
                <td>
                    <a href="{{ route('reservas.show', $reserva) }}" class="codigo-reserva" style="text-decoration:none;">
                        {{ $reserva->codigo_reserva }}
                    </a>
                </td>
                <td>
                    <div style="font-weight:600;font-size:.84rem;color:var(--ink);">{{ $reserva->cliente->nombre_completo }}</div>
                    <div style="font-size:.72rem;color:var(--ink-4);">
                        <i class="bi bi-telephone" style="font-size:.65rem;"></i>
                        {{ $reserva->cliente->telefono_whatsapp ?? '—' }}
                    </div>
                </td>
                <td style="max-width:180px;">
                    <div style="font-weight:500;font-size:.84rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $reserva->nombre_tour ?? ($reserva->fechaTour?->tour?->nombre ?? '—') }}
                    </div>
                </td>
                <td style="white-space:nowrap;">
                    <div style="font-size:.84rem;font-weight:600;">
                        {{ $reserva->fecha_tour ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y') : ($reserva->fechaTour?->fecha ? \Carbon\Carbon::parse($reserva->fechaTour->fecha)->format('d/m/Y') : '—') }}
                    </div>
                    <div style="font-size:.72rem;color:var(--ink-4);">
                        {{ $reserva->hora_salida ?? $reserva->fechaTour?->hora_salida ?? '' }}
                    </div>
                </td>
                <td style="min-width:120px;">
                    <div class="pago-bar-wrap" title="S/ {{ number_format($pagado,2) }} de S/ {{ number_format($total,2) }}">
                        <div class="pago-bar">
                            <div class="pago-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                        </div>
                        <span style="font-size:.72rem;font-weight:700;color:{{ $barColor }};font-family:'DM Mono',monospace;white-space:nowrap;">{{ $pct }}%</span>
                    </div>
                    <div style="font-size:.7rem;color:var(--ink-4);margin-top:2px;">
                        S/ {{ number_format($pagado,2) }} / {{ number_format($total,2) }}
                    </div>
                </td>
                <td>
                    <span class="badge-estado est-{{ $estadoSlug }}">
                        {{ ucfirst(str_replace('_',' ',$reserva->estado->nombre)) }}
                    </span>
                </td>
                <td>
                    <span class="canal-badge">
                        <i class="bi {{ $canales[$reserva->canal_contacto] ?? 'bi-chat' }}"></i>
                    </span>
                </td>
                <td>
                    <a href="{{ route('reservas.show', $reserva) }}" class="btn-tabla">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-res">
                        <i class="bi bi-calendar-x"></i>
                        <p>No hay reservas que coincidan con los filtros.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if(method_exists($reservas, 'hasPages') && $reservas->hasPages())
    <div class="pag-footer">
        <div class="pag-info">
            Mostrando {{ $reservas->firstItem() }}–{{ $reservas->lastItem() }} de {{ $reservas->total() }} reservas
        </div>
        {{ $reservas->links() }}
    </div>
    @endif
</div>

@endsection