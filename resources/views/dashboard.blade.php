@extends('layouts.app')
@section('titulo', 'Dashboard')

@push('styles')
<style>
.dash-section { margin-bottom: 1.5rem; }

/* KPIs */
.kpi-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; }
.kpi {
    background: white; border: 1px solid #e2e8f0; border-radius: 12px;
    padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: .25rem;
    position: relative; overflow: hidden;
}
.kpi::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 3px;
}
.kpi.k-amber::before { background: #f59e0b; }
.kpi.k-green::before  { background: #059669; }
.kpi.k-blue::before   { background: #1d4ed8; }
.kpi.k-navy::before   { background: #0f1f3d; }

.kpi-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: .5rem; }
.kpi-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #94a3b8; }
.kpi-icon  { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .95rem; }
.kpi-value { font-size: 2rem; font-weight: 800; color: #0f172a; line-height: 1; }
.kpi-sub   { font-size: .72rem; color: #94a3b8; margin-top: .2rem; }

/* Grid 2 col */
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* Cards */
.panel {
    background: white; border: 1px solid #e2e8f0;
    border-radius: 12px; overflow: hidden;
}
.panel-head {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex; justify-content: space-between; align-items: center;
}
.panel-head-title {
    font-size: .84rem; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: .45rem;
}
.panel-head-title i { color: #94a3b8; }
.panel-link { font-size: .75rem; color: #1d4ed8; text-decoration: none; font-weight: 600; }
.panel-link:hover { text-decoration: underline; }
.panel-body { padding: 1rem 1.25rem; }

/* Accesos rápidos */
.acc-list { display: flex; flex-direction: column; gap: .4rem; }
.acc-item {
    display: flex; align-items: center; gap: .75rem;
    padding: .75rem 1rem; border-radius: 8px;
    border: 1.5px solid #e2e8f0; text-decoration: none;
    transition: all .15s; background: white;
}
.acc-item:hover { border-color: #f59e0b; background: #fffbeb; }
.acc-item.acc-primary { background: #0f1f3d; border-color: #0f1f3d; }
.acc-item.acc-primary:hover { background: #162444; border-color: #162444; }
.acc-ico {
    width: 34px; height: 34px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.acc-text .acc-label { font-size: .84rem; font-weight: 700; color: #0f172a; }
.acc-text .acc-desc  { font-size: .7rem; color: #94a3b8; }
.acc-item.acc-primary .acc-text .acc-label { color: white; }
.acc-item.acc-primary .acc-text .acc-desc  { color: rgba(255,255,255,.45); }
.acc-arrow { margin-left: auto; font-size: .8rem; }

/* Estados */
.est-list { display: flex; flex-direction: column; gap: .55rem; padding: 1rem 1.25rem; }
.est-row  { display: flex; align-items: center; gap: .75rem; }
.est-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.est-name { font-size: .78rem; color: #475569; font-weight: 500; flex: 1; }
.est-bar-wrap { flex: 2; height: 4px; background: #e2e8f0; border-radius: 999px; overflow: hidden; }
.est-bar-fill { height: 100%; border-radius: 999px; transition: width .4s; }
.est-num  { font-size: .78rem; font-weight: 700; color: #0f172a; min-width: 20px; text-align: right; }

/* Tabla últimas reservas */
.res-tbl { width: 100%; border-collapse: collapse; }
.res-tbl thead th {
    padding: .55rem 1rem; font-size: .65rem; font-weight: 700;
    color: #94a3b8; background: #f8fafc; text-align: left;
    text-transform: uppercase; letter-spacing: .08em;
    border-bottom: 1px solid #e2e8f0;
}
.res-tbl tbody td {
    padding: .8rem 1rem; font-size: .82rem; color: #475569;
    border-bottom: 1px solid #f1f5f9;
}
.res-tbl tbody tr:last-child td { border-bottom: none; }
.res-tbl tbody tr:hover td { background: #fafbff; }

.cod {
    font-family: 'JetBrains Mono', monospace; font-size: .72rem;
    font-weight: 600; color: #1d4ed8; background: #eff6ff;
    padding: 3px 8px; border-radius: 5px; white-space: nowrap;
}
.badge-est {
    display: inline-flex; align-items: center; gap: .28rem;
    padding: 3px 9px; border-radius: 999px;
    font-size: .67rem; font-weight: 700;
}
.badge-est::before { content:''; width:5px; height:5px; border-radius:50%; background:currentColor; }
.est-consulta    { background:#f1f5f9; color:#475569; }
.est-pre_reserva { background:#fffbeb; color:#92400e; }
.est-confirmada  { background:#ecfdf5; color:#065f46; }
.est-mitad_pago  { background:#eff6ff; color:#1e40af; }
.est-pagado      { background:#f0fdf4; color:#15803d; }
.est-cancelada   { background:#fef2f2; color:#991b1b; }
.est-finalizada  { background:#f5f3ff; color:#5b21b6; }

.pct-wrap { display: flex; align-items: center; gap: .4rem; }
.pct-bar  { width: 48px; height: 4px; background: #e2e8f0; border-radius: 999px; overflow: hidden; }
.pct-fill { height: 100%; border-radius: 999px; }
.pct-num  { font-size: .7rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; }

.view-btn {
    display: inline-flex; align-items: center; gap: .25rem;
    font-size: .75rem; color: #1d4ed8; font-weight: 600;
    text-decoration: none; padding: 4px 10px;
    border: 1px solid #dbeafe; border-radius: 6px;
    transition: all .12s;
}
.view-btn:hover { background: #eff6ff; }

/* Welcome */
.welcome {
    background: linear-gradient(135deg, #0f1f3d 0%, #1e3160 100%);
    border-radius: 12px; padding: 1.5rem 1.75rem;
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 1.5rem;
}
.welcome h2 { color: white; font-size: 1.25rem; font-weight: 800; margin-bottom: .2rem; }
.welcome p  { color: rgba(255,255,255,.5); font-size: .82rem; }
.welcome-badge {
    background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.25);
    color: #f59e0b; padding: 6px 14px; border-radius: 999px;
    font-size: .75rem; font-weight: 700;
    display: flex; align-items: center; gap: .35rem;
}
</style>
@endpush

@section('contenido')

{{-- Bienvenida --}}
<div class="welcome">
    <div>
        <h2>Bienvenido, {{ Auth::user()->name }}</h2>
        <p>{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }} · Panel de administración</p>
    </div>
    <div class="welcome-badge">
        <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
        Sistema activo
    </div>
</div>

{{-- KPIs --}}
<div class="kpi-row dash-section">
    <div class="kpi k-amber">
        <div class="kpi-top">
            <div class="kpi-label">Reservas hoy</div>
            <div class="kpi-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-calendar-plus"></i></div>
        </div>
        <div class="kpi-value">{{ $reservasHoy }}</div>
        <div class="kpi-sub">creadas hoy</div>
    </div>
    <div class="kpi k-green">
        <div class="kpi-top">
            <div class="kpi-label">Confirmadas</div>
            <div class="kpi-icon" style="background:#ecfdf5;color:#059669;"><i class="bi bi-check-circle"></i></div>
        </div>
        <div class="kpi-value" style="color:#059669;">{{ $confirmadas }}</div>
        <div class="kpi-sub">este mes</div>
    </div>
    <div class="kpi k-blue">
        <div class="kpi-top">
            <div class="kpi-label">Clientes</div>
            <div class="kpi-icon" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-people"></i></div>
        </div>
        <div class="kpi-value" style="color:#1d4ed8;">{{ $totalClientes }}</div>
        <div class="kpi-sub">registrados</div>
    </div>
    <div class="kpi k-navy">
        <div class="kpi-top">
            <div class="kpi-label">Recientes</div>
            <div class="kpi-icon" style="background:#f0f4ff;color:#0f1f3d;"><i class="bi bi-collection"></i></div>
        </div>
        <div class="kpi-value">{{ $ultimasReservas->count() }}</div>
        <div class="kpi-sub">últimas registradas</div>
    </div>
</div>

{{-- Accesos + Estados --}}
<div class="grid-2 dash-section">

    {{-- Accesos rápidos: solo Nueva Reserva y Ver todas --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-lightning-charge" style="color:#f59e0b;"></i>
                Accesos rápidos
            </div>
        </div>
        <div class="panel-body">
            <div class="acc-list">
                <a href="{{ route('reservas.create') }}" class="acc-item acc-primary">
                    <div class="acc-ico" style="background:rgba(245,158,11,.2);color:#f59e0b;">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                    <div class="acc-text">
                        <div class="acc-label">Nueva Reserva</div>
                        <div class="acc-desc">Registrar reserva ahora</div>
                    </div>
                    <i class="bi bi-arrow-right acc-arrow" style="color:rgba(255,255,255,.3);"></i>
                </a>
                <a href="{{ route('reservas.index') }}" class="acc-item">
                    <div class="acc-ico" style="background:#ecfdf5;color:#059669;">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="acc-text">
                        <div class="acc-label">Ver todas las reservas</div>
                        <div class="acc-desc">Listado completo</div>
                    </div>
                    <i class="bi bi-arrow-right acc-arrow" style="color:#e2e8f0;"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Reservas por estado --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-title">
                <i class="bi bi-bar-chart-line" style="color:#1d4ed8;"></i>
                Reservas por estado
            </div>
        </div>
        <div class="est-list">
            @php $maxCnt = collect($estadosResumen)->max('cnt') ?: 1; @endphp
            @foreach($estadosResumen as $e)
            <div class="est-row">
                <div class="est-dot" style="background:{{ $e['color'] }};"></div>
                <div class="est-name">{{ $e['label'] }}</div>
                <div class="est-bar-wrap">
                    <div class="est-bar-fill"
                         style="width:{{ $maxCnt > 0 ? round($e['cnt'] / $maxCnt * 100) : 0 }}%;background:{{ $e['color'] }};"></div>
                </div>
                <div class="est-num">{{ $e['cnt'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Últimas reservas --}}
<div class="panel dash-section">
    <div class="panel-head">
        <div class="panel-head-title">
            <i class="bi bi-clock-history"></i>
            Últimas reservas
        </div>
        <a href="{{ route('reservas.index') }}" class="panel-link">Ver todas →</a>
    </div>
    <table class="res-tbl">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cliente</th>
                <th>Tour</th>
                <th>Estado</th>
                <th>Pago</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($ultimasReservas as $r)
            @php
                $slug   = str_replace(' ', '_', strtolower($r->estado->nombre ?? 'consulta'));
                $total  = $r->precio_total ?? 0;
                $pagado = $r->monto_pagado ?? 0;
                $pct    = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
                $pc     = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#1d4ed8' : '#d97706');
            @endphp
            <tr>
                <td><span class="cod">{{ $r->codigo_reserva }}</span></td>
                <td style="font-weight:600;color:#0f172a;">{{ $r->cliente->nombre_completo ?? '—' }}</td>
                <td style="color:#64748b;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $r->fechaTour->tour->nombre ?? ($r->nombre_tour ?? '—') }}
                </td>
                <td>
                    <span class="badge-est est-{{ $slug }}">
                        {{ ucfirst(str_replace('_', ' ', $r->estado->nombre ?? '')) }}
                    </span>
                </td>
                <td>
                    <div class="pct-wrap">
                        <div class="pct-bar">
                            <div class="pct-fill" style="width:{{ $pct }}%;background:{{ $pc }};"></div>
                        </div>
                        <span class="pct-num" style="color:{{ $pc }};">{{ $pct }}%</span>
                    </div>
                </td>
                <td>
                    <a href="{{ route('reservas.show', $r) }}" class="view-btn">
                        <i class="bi bi-eye" style="font-size:.7rem;"></i> Ver
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:3rem;color:#94a3b8;">
                    <i class="bi bi-calendar-x" style="font-size:1.5rem;display:block;margin-bottom:.5rem;"></i>
                    Sin reservas registradas aún
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection