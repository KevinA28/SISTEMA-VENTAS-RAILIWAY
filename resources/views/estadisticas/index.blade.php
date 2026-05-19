@extends('layouts.app')
@section('titulo', 'Estadísticas')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --ink:#0d1117; --ink-2:#1f2937; --ink-3:#6b7280; --ink-4:#9ca3af;
    --line:#e5e7eb; --line-2:#f3f4f6; --surface:#f9fafb;
    --blue:#1d4ed8; --blue-l:#eff6ff; --blue-m:#bfdbfe;
    --green:#059669; --green-l:#ecfdf5; --green-m:#6ee7b7;
    --amber:#d97706; --amber-l:#fffbeb; --amber-m:#fcd34d;
    --red:#dc2626; --red-l:#fef2f2; --red-m:#fca5a5;
    --purple:#7c3aed; --purple-l:#f5f3ff; --purple-m:#c4b5fd;
    --teal:#0d9488; --teal-l:#f0fdfa;
    --radius-sm:8px; --radius-md:14px; --radius-lg:20px;
    --shadow-sm:0 1px 3px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
    --shadow-md:0 4px 12px rgba(0,0,0,.08),0 2px 4px rgba(0,0,0,.04);
}
*, *::before, *::after { box-sizing: border-box; }
body { font-family:'DM Sans',sans-serif; background:var(--surface); color:var(--ink); }

/* ── PAGE HEADER ── */
.st-header {
    display:flex; align-items:flex-start; justify-content:space-between;
    flex-wrap:wrap; gap:1rem; margin-bottom:1.75rem;
    padding-bottom:1.25rem; border-bottom:1.5px solid var(--line);
}
.st-title {
    font-family:'Syne',sans-serif;
    font-size:1.5rem; font-weight:800; color:var(--ink);
    display:flex; align-items:center; gap:.6rem; letter-spacing:-.02em;
}
.st-title-icon {
    width:38px; height:38px; border-radius:10px;
    background:var(--blue); color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-size:1rem;
}
.st-subtitle { font-size:.8rem; color:var(--ink-4); margin-top:.25rem; }
.st-timestamp {
    display:flex; align-items:center; gap:.35rem;
    font-size:.75rem; color:var(--ink-4);
    background:#fff; border:1px solid var(--line);
    padding:.4rem .8rem; border-radius:999px;
}

/* ── PERIOD FILTER ── */
.period-bar {
    display:flex; align-items:center; gap:.5rem;
    margin-bottom:1.5rem; flex-wrap:wrap;
}
.period-label { font-size:.75rem; font-weight:600; color:var(--ink-3); text-transform:uppercase; letter-spacing:.06em; }
.period-btn {
    padding:.35rem .85rem; border-radius:999px;
    border:1.5px solid var(--line); background:#fff;
    font-size:.78rem; font-weight:600; color:var(--ink-3);
    cursor:pointer; transition:all .15s; font-family:'DM Sans',sans-serif;
}
.period-btn:hover { border-color:var(--blue); color:var(--blue); }
.period-btn.active {
    background:var(--blue); border-color:var(--blue);
    color:#fff; box-shadow:0 2px 8px rgba(29,78,216,.25);
}

/* ── METRICS GRID ── */
.metrics-grid {
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:.875rem; margin-bottom:1.5rem;
}
@media(max-width:1200px){ .metrics-grid{ grid-template-columns:repeat(3,1fr); } }
@media(max-width:768px){ .metrics-grid{ grid-template-columns:repeat(2,1fr); } }

.metric-card {
    background:#fff; border:1px solid var(--line);
    border-radius:var(--radius-md); padding:1.1rem 1.25rem;
    position:relative; overflow:hidden;
    box-shadow:var(--shadow-sm);
    transition:transform .15s, box-shadow .15s;
}
.metric-card:hover { transform:translateY(-2px); box-shadow:var(--shadow-md); }
.metric-accent {
    position:absolute; top:0; left:0; right:0; height:3px;
    border-radius:var(--radius-md) var(--radius-md) 0 0;
}
.metric-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:.875rem; }
.metric-ico {
    width:36px; height:36px; border-radius:9px;
    display:flex; align-items:center; justify-content:center; font-size:.95rem;
}
.metric-trend {
    display:inline-flex; align-items:center; gap:.2rem;
    font-size:.68rem; font-weight:700; padding:2px 7px;
    border-radius:999px;
}
.trend-up   { background:var(--green-l); color:var(--green); }
.trend-down { background:var(--red-l);   color:var(--red);   }
.trend-neutral { background:var(--line-2); color:var(--ink-4); }

.metric-val {
    font-family:'DM Mono',monospace;
    font-size:1.7rem; font-weight:500; color:var(--ink); line-height:1;
}
.metric-lbl { font-size:.72rem; color:var(--ink-3); margin-top:.3rem; font-weight:500; }
.metric-sub { font-size:.68rem; color:var(--ink-4); margin-top:.4rem; display:flex; align-items:center; gap:.25rem; }

/* ── SECTION LABELS ── */
.section-label {
    font-family:'Syne',sans-serif;
    font-size:.65rem; font-weight:700; letter-spacing:.12em;
    text-transform:uppercase; color:var(--ink-4);
    display:flex; align-items:center; gap:.5rem;
    margin-bottom:.875rem;
}
.section-label::after {
    content:''; flex:1; height:1px; background:var(--line);
}

/* ── CHART CARDS ── */
.chart-card {
    background:#fff; border:1px solid var(--line);
    border-radius:var(--radius-md); padding:1.25rem;
    box-shadow:var(--shadow-sm);
}
.chart-head {
    display:flex; align-items:flex-start; justify-content:space-between;
    margin-bottom:1.1rem; flex-wrap:wrap; gap:.5rem;
}
.chart-title {
    font-family:'Syne',sans-serif;
    font-size:.92rem; font-weight:700; color:var(--ink);
    display:flex; align-items:center; gap:.45rem;
}
.chart-title-icon {
    width:26px; height:26px; border-radius:7px;
    display:flex; align-items:center; justify-content:center;
    font-size:.72rem;
}
.chart-desc { font-size:.72rem; color:var(--ink-4); margin-top:2px; }
.chart-badge {
    font-size:.68rem; font-weight:700; padding:3px 10px;
    border-radius:999px; background:var(--line-2); color:var(--ink-3);
    white-space:nowrap;
}

/* ── GRIDS ── */
.grid-2   { display:grid; grid-template-columns:1fr 1fr;      gap:1rem; margin-bottom:1rem; }
.grid-3   { display:grid; grid-template-columns:1fr 1fr 1fr;  gap:1rem; margin-bottom:1rem; }
.grid-6-4 { display:grid; grid-template-columns:6fr 4fr;      gap:1rem; margin-bottom:1rem; }
.col-full { grid-column:1/-1; }
@media(max-width:960px){ .grid-2,.grid-3,.grid-6-4{ grid-template-columns:1fr; } }

/* ── HORIZONTAL BAR LIST ── */
.hbar-list { display:flex; flex-direction:column; gap:.65rem; }
.hbar-item {}
.hbar-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:.3rem; }
.hbar-name {
    font-size:.8rem; font-weight:500; color:var(--ink-2);
    display:flex; align-items:center; gap:.4rem;
    max-width:70%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.hbar-rank {
    display:inline-flex; align-items:center; justify-content:center;
    width:18px; height:18px; border-radius:50%;
    font-size:.6rem; font-weight:800; flex-shrink:0;
}
.hbar-meta { display:flex; align-items:center; gap:.5rem; }
.hbar-val { font-size:.75rem; font-weight:700; color:var(--ink-2); font-family:'DM Mono',monospace; }
.hbar-pct { font-size:.68rem; color:var(--ink-4); font-family:'DM Mono',monospace; }
.hbar-track { height:6px; background:var(--line-2); border-radius:999px; overflow:hidden; }
.hbar-fill  { height:100%; border-radius:999px; transition:width .7s cubic-bezier(.4,0,.2,1); }

/* ── DONUT WRAP ── */
.donut-container { display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; }
.donut-legend { display:flex; flex-direction:column; gap:.55rem; flex:1; min-width:110px; }
.legend-row { display:flex; align-items:center; gap:.5rem; cursor:default; }
.legend-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; }
.legend-name { font-size:.77rem; font-weight:500; color:var(--ink-2); flex:1; }
.legend-count { font-size:.72rem; font-family:'DM Mono',monospace; color:var(--ink-3); }
.legend-pct { font-size:.68rem; color:var(--ink-4); }

/* ── VIP LIST ── */
.vip-list { display:flex; flex-direction:column; gap:.5rem; }
.vip-item {
    display:flex; align-items:center; gap:.75rem;
    padding:.6rem .75rem; background:var(--surface);
    border:1px solid var(--line); border-radius:10px;
    transition:background .12s;
}
.vip-item:hover { background:var(--purple-l); border-color:var(--purple-m); }
.vip-avatar {
    width:32px; height:32px; border-radius:50%;
    background:var(--purple-l); color:var(--purple);
    display:flex; align-items:center; justify-content:center;
    font-size:.68rem; font-weight:800; flex-shrink:0;
}
.vip-info { flex:1; min-width:0; }
.vip-name { font-size:.82rem; font-weight:600; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.vip-phone { font-size:.7rem; color:var(--ink-4); margin-top:1px; }
.vip-badge {
    background:var(--purple-l); color:var(--purple);
    border:1px solid var(--purple-m);
    border-radius:999px; padding:2px 9px;
    font-size:.68rem; font-weight:700; flex-shrink:0;
    display:flex; align-items:center; gap:.25rem;
}

/* ── WEEK COMPARE ── */
.week-boxes { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-bottom:1.1rem; }
.week-box {
    text-align:center; padding:.875rem .5rem;
    background:var(--surface); border:1px solid var(--line);
    border-radius:10px;
}
.week-num {
    font-family:'DM Mono',monospace;
    font-size:1.9rem; font-weight:500; line-height:1;
}
.week-lbl { font-size:.7rem; color:var(--ink-4); margin-top:.3rem; }
.week-delta {
    display:inline-flex; align-items:center; gap:.25rem;
    font-size:.7rem; font-weight:700; margin-top:.4rem;
    padding:2px 7px; border-radius:999px;
}

/* ── TOP TABLE ── */
.top-table { width:100%; border-collapse:collapse; }
.top-table th {
    padding:.5rem .75rem; font-size:.65rem; font-weight:700;
    text-transform:uppercase; letter-spacing:.07em; color:var(--ink-4);
    border-bottom:1.5px solid var(--line); text-align:left; white-space:nowrap;
}
.top-table td { padding:.65rem .75rem; font-size:.81rem; color:var(--ink-2); border-bottom:1px solid var(--line-2); vertical-align:middle; }
.top-table tr:last-child td { border-bottom:none; }
.top-table tr:hover td { background:var(--blue-l); }
.rank-badge {
    display:inline-flex; align-items:center; justify-content:center;
    width:22px; height:22px; border-radius:50%;
    font-size:.62rem; font-weight:800;
}
.rank-1 { background:#fef3c7; color:#92400e; }
.rank-2 { background:#f1f5f9; color:#475569; }
.rank-3 { background:#fde8d4; color:#c2410c; }
.rank-n { background:var(--line-2); color:var(--ink-4); }
.mini-bar-track { height:5px; background:var(--line-2); border-radius:999px; overflow:hidden; width:60px; display:inline-block; vertical-align:middle; margin-right:.4rem; }
.mini-bar-fill  { height:100%; border-radius:999px; }

/* ── CANAL ICON ── */
.canal-chip {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:3px 10px; border-radius:999px;
    font-size:.72rem; font-weight:600; border:1px solid transparent;
}

/* ── EMPTY ── */
.empty-state {
    text-align:center; padding:2.5rem 1rem;
    color:var(--ink-4);
}
.empty-state i { font-size:1.75rem; display:block; margin-bottom:.5rem; opacity:.25; }
.empty-state p { font-size:.8rem; }

/* ── CHART TOOLTIP CUSTOM ── */
canvas { max-width:100%; }
</style>
@endpush

@section('contenido')

{{-- ── HEADER ── --}}
<div class="st-header">
    <div>
        <div class="st-title">
            <div class="st-title-icon"><i class="bi bi-graph-up-arrow"></i></div>
            Estadísticas
        </div>
        <div class="st-subtitle">Panel de análisis · Visión completa del negocio</div>
    </div>
    <div class="st-timestamp">
        <i class="bi bi-arrow-clockwise"></i>
        Actualizado {{ now()->format('d/m/Y · H:i') }}
    </div>
</div>

{{-- ── FILTRO PERÍODO ── --}}
<div class="period-bar">
    <span class="period-label"><i class="bi bi-funnel-fill" style="font-size:.7rem"></i> Período</span>
    <button class="period-btn" data-period="7">7 días</button>
    <button class="period-btn" data-period="30">Este mes</button>
    <button class="period-btn active" data-period="90">3 meses</button>
    <button class="period-btn" data-period="180">6 meses</button>
    <button class="period-btn" data-period="365">Este año</button>
    <button class="period-btn" data-period="0">Todo</button>
</div>

{{-- ── MÉTRICAS PRINCIPALES ── --}}
<div class="section-label"><i class="bi bi-lightning-fill" style="color:var(--amber)"></i> Resumen general</div>
<div class="metrics-grid">

    <div class="metric-card">
        <div class="metric-accent" style="background:var(--blue)"></div>
        <div class="metric-head">
            <div class="metric-ico" style="background:var(--blue-l);color:var(--blue)"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="metric-trend {{ $estaSemana >= $semanaPasada ? 'trend-up' : 'trend-down' }}">
                <i class="bi bi-arrow-{{ $estaSemana >= $semanaPasada ? 'up' : 'down' }}-short"></i>
                vs semana ant.
            </div>
        </div>
        <div class="metric-val">{{ number_format($totalReservas) }}</div>
        <div class="metric-lbl">Total de reservas</div>
        <div class="metric-sub"><i class="bi bi-calendar-week"></i> {{ $estaSemana }} esta semana · {{ $semanaPasada }} anterior</div>
    </div>

    <div class="metric-card">
        <div class="metric-accent" style="background:var(--green)"></div>
        <div class="metric-head">
            <div class="metric-ico" style="background:var(--green-l);color:var(--green)"><i class="bi bi-cash-stack"></i></div>
            <div class="metric-trend trend-neutral"><i class="bi bi-receipt"></i> Total</div>
        </div>
        <div class="metric-val">S/ {{ number_format($totalIngresos, 0) }}</div>
        <div class="metric-lbl">Ingresos brutos</div>
        <div class="metric-sub"><i class="bi bi-info-circle"></i> Precio total de reservas</div>
    </div>

    <div class="metric-card">
        <div class="metric-accent" style="background:var(--teal)"></div>
        <div class="metric-head">
            <div class="metric-ico" style="background:var(--teal-l);color:var(--teal)"><i class="bi bi-check-circle-fill"></i></div>
            @if($totalIngresos > 0)
            <div class="metric-trend trend-up">
                <i class="bi bi-percent"></i>
                {{ round($totalCobrado / $totalIngresos * 100) }}%
            </div>
            @endif
        </div>
        <div class="metric-val">S/ {{ number_format($totalCobrado, 0) }}</div>
        <div class="metric-lbl">Total cobrado</div>
        <div class="metric-sub"><i class="bi bi-check2-circle"></i> Pagos confirmados</div>
    </div>

    <div class="metric-card">
        <div class="metric-accent" style="background:var(--red)"></div>
        <div class="metric-head">
            <div class="metric-ico" style="background:var(--red-l);color:var(--red)"><i class="bi bi-hourglass-split"></i></div>
            <div class="metric-trend trend-neutral"><i class="bi bi-clock"></i> Pendiente</div>
        </div>
        <div class="metric-val">S/ {{ number_format($totalPendiente, 0) }}</div>
        <div class="metric-lbl">Saldo pendiente</div>
        <div class="metric-sub"><i class="bi bi-arrow-right-circle"></i> Por cobrar</div>
    </div>

    <div class="metric-card">
        <div class="metric-accent" style="background:var(--purple)"></div>
        <div class="metric-head">
            <div class="metric-ico" style="background:var(--purple-l);color:var(--purple)"><i class="bi bi-people-fill"></i></div>
            <div class="metric-trend trend-up"><i class="bi bi-gem"></i> {{ $clientesVip->count() }} VIP</div>
        </div>
        <div class="metric-val">{{ number_format($totalClientes) }}</div>
        <div class="metric-lbl">Total de clientes</div>
        <div class="metric-sub"><i class="bi bi-person-check"></i> Clientes registrados</div>
    </div>
</div>

{{-- ── GRÁFICO MESES (full) ── --}}
<div class="section-label"><i class="bi bi-bar-chart-fill" style="color:var(--blue)"></i> Evolución temporal</div>
<div class="chart-card col-full" style="margin-bottom:1rem">
    <div class="chart-head">
        <div>
            <div class="chart-title">
                <div class="chart-title-icon" style="background:var(--blue-l);color:var(--blue)"><i class="bi bi-bar-chart-line-fill"></i></div>
                Reservas e ingresos por mes
            </div>
            <div class="chart-desc">Comparativa de volumen de reservas vs ingresos generados</div>
        </div>
        <span class="chart-badge"><i class="bi bi-clock-history"></i> Últimos 12 meses</span>
    </div>
    @if($reservasPorMes->isEmpty())
    <div class="empty-state"><i class="bi bi-bar-chart"></i><p>Sin datos registrados aún</p></div>
    @else
    <canvas id="chart-meses" height="110"></canvas>
    @endif
</div>

{{-- ── ESTADOS + CANAL ── --}}
<div class="section-label"><i class="bi bi-pie-chart-fill" style="color:var(--amber)"></i> Distribución</div>
<div class="grid-2">

    {{-- Dona estados --}}
    <div class="chart-card">
        <div class="chart-head">
            <div>
                <div class="chart-title">
                    <div class="chart-title-icon" style="background:var(--amber-l);color:var(--amber)"><i class="bi bi-pie-chart-fill"></i></div>
                    Estado de reservas
                </div>
                <div class="chart-desc">Proporción por estado actual</div>
            </div>
        </div>
        @if($reservasPorEstado->isEmpty())
        <div class="empty-state"><i class="bi bi-pie-chart"></i><p>Sin datos</p></div>
        @else
        <div class="donut-container">
            <canvas id="chart-estados" width="170" height="170" style="flex-shrink:0;max-width:170px"></canvas>
            <div class="donut-legend">
                @php $totalEst = $reservasPorEstado->sum('total'); @endphp
                @foreach($reservasPorEstado as $est)
                <div class="legend-row">
                    <div class="legend-dot" style="background:{{ $est['color'] }}"></div>
                    <span class="legend-name">{{ ucfirst(str_replace('_',' ',$est['nombre'])) }}</span>
                    <span class="legend-count">{{ $est['total'] }}</span>
                    <span class="legend-pct">&nbsp;({{ $totalEst > 0 ? round($est['total']/$totalEst*100) : 0 }}%)</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Canal de contacto --}}
    <div class="chart-card">
        <div class="chart-head">
            <div>
                <div class="chart-title">
                    <div class="chart-title-icon" style="background:#f0fdf4;color:#059669"><i class="bi bi-broadcast-pin"></i></div>
                    Canal de captación
                </div>
                <div class="chart-desc">Cómo nos contactan los clientes</div>
            </div>
        </div>
        @if($reservasPorCanal->isEmpty())
        <div class="empty-state"><i class="bi bi-broadcast"></i><p>Sin datos</p></div>
        @else
        @php
        $canalesIconos  = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2','referido'=>'bi-people-fill'];
        $canalesColores = ['whatsapp'=>'#25d366','presencial'=>'#1d4ed8','llamada'=>'#7c3aed','redes_sociales'=>'#e1306c','web'=>'#0ea5e9','referido'=>'#d97706'];
        $canalesBg      = ['whatsapp'=>'#f0fdf4','presencial'=>'#eff6ff','llamada'=>'#f5f3ff','redes_sociales'=>'#fdf2f8','web'=>'#f0f9ff','referido'=>'#fffbeb'];
        $maxCanal = $reservasPorCanal->max('total');
        $totalCanal = $reservasPorCanal->sum('total');
        @endphp
        <div class="hbar-list">
            @foreach($reservasPorCanal as $canal)
            @php
                $color = $canalesColores[$canal->canal_contacto] ?? '#6b7280';
                $bg    = $canalesBg[$canal->canal_contacto] ?? '#f3f4f6';
                $icon  = $canalesIconos[$canal->canal_contacto] ?? 'bi-chat';
                $pct   = $maxCanal > 0 ? round($canal->total/$maxCanal*100) : 0;
                $pctTotal = $totalCanal > 0 ? round($canal->total/$totalCanal*100) : 0;
            @endphp
            <div class="hbar-item">
                <div class="hbar-top">
                    <span class="hbar-name">
                        <span class="canal-chip" style="background:{{ $bg }};color:{{ $color }};border-color:{{ $color }}20">
                            <i class="bi {{ $icon }}"></i>
                            {{ ucfirst(str_replace('_',' ',$canal->canal_contacto)) }}
                        </span>
                    </span>
                    <span class="hbar-meta">
                        <span class="hbar-val">{{ $canal->total }}</span>
                        <span class="hbar-pct">{{ $pctTotal }}%</span>
                    </span>
                </div>
                <div class="hbar-track">
                    <div class="hbar-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ── TOP TOURS ── --}}
<div class="section-label"><i class="bi bi-trophy-fill" style="color:#d97706"></i> Rendimiento de tours</div>
<div class="chart-card" style="margin-bottom:1rem">
    <div class="chart-head">
        <div>
            <div class="chart-title">
                <div class="chart-title-icon" style="background:#fffbeb;color:#d97706"><i class="bi bi-trophy-fill"></i></div>
                Top tours más reservados
            </div>
            <div class="chart-desc">Ranking por volumen de reservas e ingresos generados</div>
        </div>
        @if(!$topTours->isEmpty())
        <span class="chart-badge">{{ $topTours->count() }} tours</span>
        @endif
    </div>
    @if($topTours->isEmpty())
    <div class="empty-state"><i class="bi bi-map"></i><p>Sin datos de tours aún</p></div>
    @else
    <canvas id="chart-tours" height="80"></canvas>
    @endif
</div>

{{-- ── PROCEDENCIA + DESTINOS + VIP ── --}}
<div class="section-label"><i class="bi bi-pin-map-fill" style="color:var(--green)"></i> Geografía y clientes</div>
<div class="grid-3">

    {{-- Procedencia --}}
    <div class="chart-card">
        <div class="chart-head">
            <div>
                <div class="chart-title">
                    <div class="chart-title-icon" style="background:var(--blue-l);color:var(--blue)"><i class="bi bi-pin-map-fill"></i></div>
                    Procedencia de clientes
                </div>
                <div class="chart-desc">Ciudades de origen</div>
            </div>
        </div>
        @if($clientesPorProcedencia->isEmpty())
        <div class="empty-state"><i class="bi bi-map"></i><p>Sin datos</p></div>
        @else
        @php $maxProc = $clientesPorProcedencia->max('total'); $totalProc = $clientesPorProcedencia->sum('total'); @endphp
        <div class="hbar-list">
            @foreach($clientesPorProcedencia as $i => $proc)
            @php $pct = $maxProc > 0 ? round($proc->total/$maxProc*100) : 0; @endphp
            <div class="hbar-item">
                <div class="hbar-top">
                    <span class="hbar-name">
                        <span class="hbar-rank {{ $i==0 ? 'rank-1' : ($i==1 ? 'rank-2' : ($i==2 ? 'rank-3' : 'rank-n')) }}">{{ $i+1 }}</span>
                        {{ $proc->ciudad_procedencia ?? 'Sin ciudad' }}
                    </span>
                    <span class="hbar-meta">
                        <span class="hbar-val">{{ $proc->total }}</span>
                        <span class="hbar-pct">{{ $totalProc > 0 ? round($proc->total/$totalProc*100) : 0 }}%</span>
                    </span>
                </div>
                <div class="hbar-track">
                    <div class="hbar-fill" style="width:{{ $pct }}%;background:var(--blue)"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Destinos --}}
    <div class="chart-card">
        <div class="chart-head">
            <div>
                <div class="chart-title">
                    <div class="chart-title-icon" style="background:var(--green-l);color:var(--green)"><i class="bi bi-geo-alt-fill"></i></div>
                    Top destinos
                </div>
                <div class="chart-desc">Departamentos más visitados</div>
            </div>
        </div>
        @if($topDestinos->isEmpty())
        <div class="empty-state"><i class="bi bi-compass"></i><p>Sin datos</p></div>
        @else
        @php $maxDest = $topDestinos->max('total'); $totalDest = $topDestinos->sum('total'); @endphp
        <div class="hbar-list">
            @foreach($topDestinos as $i => $dest)
            @php $pct = $maxDest > 0 ? round($dest->total/$maxDest*100) : 0; @endphp
            <div class="hbar-item">
                <div class="hbar-top">
                    <span class="hbar-name">
                        <span class="hbar-rank {{ $i==0 ? 'rank-1' : ($i==1 ? 'rank-2' : ($i==2 ? 'rank-3' : 'rank-n')) }}">{{ $i+1 }}</span>
                        {{ $dest->departamento_destino ?? 'Sin departamento' }}
                    </span>
                    <span class="hbar-meta">
                        <span class="hbar-val">{{ $dest->total }}</span>
                        <span class="hbar-pct">{{ $totalDest > 0 ? round($dest->total/$totalDest*100) : 0 }}%</span>
                    </span>
                </div>
                <div class="hbar-track">
                    <div class="hbar-fill" style="width:{{ $pct }}%;background:var(--green)"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- VIP + Semana --}}
    <div class="chart-card">
        <div class="chart-head">
            <div>
                <div class="chart-title">
                    <div class="chart-title-icon" style="background:var(--purple-l);color:var(--purple)"><i class="bi bi-gem"></i></div>
                    Clientes VIP
                </div>
                <div class="chart-desc">Más de 3 reservas activas</div>
            </div>
        </div>

        {{-- Comparativo semana --}}
        <div class="week-boxes">
            <div class="week-box">
                <div class="week-num" style="color:var(--blue)">{{ $estaSemana }}</div>
                <div class="week-lbl">Esta semana</div>
                @php $diff = $estaSemana - $semanaPasada; @endphp
                @if($diff != 0)
                <div class="week-delta {{ $diff > 0 ? 'trend-up' : 'trend-down' }}">
                    <i class="bi bi-arrow-{{ $diff > 0 ? 'up' : 'down' }}-short"></i>
                    {{ abs($diff) }} vs ant.
                </div>
                @endif
            </div>
            <div class="week-box">
                <div class="week-num" style="color:var(--ink-4)">{{ $semanaPasada }}</div>
                <div class="week-lbl">Semana pasada</div>
            </div>
        </div>

        @if($clientesVip->isEmpty())
        <div class="empty-state" style="padding:1.25rem"><i class="bi bi-star"></i><p>Sin clientes VIP aún</p></div>
        @else
        <div class="vip-list">
            @foreach($clientesVip as $vip)
            <div class="vip-item">
                <div class="vip-avatar">{{ strtoupper(substr($vip->nombre_completo ?? '?', 0, 2)) }}</div>
                <div class="vip-info">
                    <div class="vip-name">{{ $vip->nombre_completo }}</div>
                    <div class="vip-phone"><i class="bi bi-phone" style="font-size:.65rem"></i> {{ $vip->telefono ?? 'Sin teléfono' }}</div>
                </div>
                <div class="vip-badge"><i class="bi bi-gem"></i> {{ $vip->reservas_count }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Defaults globales ──
Chart.defaults.font.family  = "'DM Sans', sans-serif";
Chart.defaults.font.size    = 12;
Chart.defaults.color        = '#9ca3af';
Chart.defaults.plugins.tooltip.padding      = 10;
Chart.defaults.plugins.tooltip.cornerRadius = 8;
Chart.defaults.plugins.tooltip.displayColors = true;
Chart.defaults.plugins.tooltip.boxPadding   = 4;

// ── Tooltip theme oscuro ──
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(15,23,42,.92)';
Chart.defaults.plugins.tooltip.titleColor       = '#f9fafb';
Chart.defaults.plugins.tooltip.bodyColor        = '#d1d5db';
Chart.defaults.plugins.tooltip.borderColor      = 'rgba(255,255,255,.08)';
Chart.defaults.plugins.tooltip.borderWidth      = 1;

// ──────────────────────────────────────────────
// GRÁFICO 1: RESERVAS E INGRESOS POR MES
// ──────────────────────────────────────────────
@if(!$reservasPorMes->isEmpty())
const mesesData = @json($reservasPorMes);
const mesesLabels = mesesData.map(m => {
    const [y, mo] = m.mes.split('-');
    const nombres = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    return nombres[parseInt(mo)-1] + ' ' + y.slice(2);
});

new Chart(document.getElementById('chart-meses'), {
    type: 'bar',
    data: {
        labels: mesesLabels,
        datasets: [
            {
                label: 'Reservas',
                data: mesesData.map(m => m.total),
                backgroundColor: 'rgba(29,78,216,.15)',
                borderColor: '#1d4ed8',
                borderWidth: 2,
                borderRadius: 7,
                borderSkipped: false,
                yAxisID: 'yRes',
                order: 2,
            },
            {
                label: 'Ingresos (S/)',
                data: mesesData.map(m => parseFloat(m.ingresos || 0)),
                type: 'line',
                borderColor: '#059669',
                backgroundColor: 'rgba(5,150,105,.08)',
                borderWidth: 2.5,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#059669',
                pointBorderWidth: 2.5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.45,
                yAxisID: 'yIng',
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'top',
                align: 'end',
                labels: { boxWidth: 12, padding: 20, font: { size: 12 } }
            },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        if (ctx.datasetIndex === 0) return '  Reservas: ' + ctx.parsed.y;
                        return '  Ingresos: S/ ' + ctx.parsed.y.toLocaleString('es-PE');
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            yRes: {
                position: 'left',
                grid: { color: '#f3f4f6' },
                ticks: { stepSize: 1, font: { size: 11 } },
                title: { display: true, text: 'Reservas', font: { size: 11 }, color: '#1d4ed8' }
            },
            yIng: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { callback: v => 'S/' + v.toLocaleString('es-PE'), font: { size: 11 } },
                title: { display: true, text: 'Ingresos', font: { size: 11 }, color: '#059669' }
            }
        }
    }
});
@endif

// ──────────────────────────────────────────────
// GRÁFICO 2: DONA — ESTADOS
// ──────────────────────────────────────────────
@if(!$reservasPorEstado->isEmpty())
const estadosData = @json($reservasPorEstado);
new Chart(document.getElementById('chart-estados'), {
    type: 'doughnut',
    data: {
        labels: estadosData.map(e => e.nombre.replace('_', ' ')),
        datasets: [{
            data: estadosData.map(e => e.total),
            backgroundColor: estadosData.map(e => e.color),
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: false,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    title: ctx => ctx[0].label.replace(/_/g,' '),
                    label: ctx => {
                        const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                        const pct   = total > 0 ? Math.round(ctx.parsed/total*100) : 0;
                        return '  ' + ctx.parsed + ' reservas (' + pct + '%)';
                    }
                }
            }
        }
    }
});
@endif

// ──────────────────────────────────────────────
// GRÁFICO 3: TOURS — BARRAS HORIZONTALES
// ──────────────────────────────────────────────
@if(!$topTours->isEmpty())
const toursData = @json($topTours);
const toursLabels = toursData.map(t => t.nombre_tour.length > 28 ? t.nombre_tour.slice(0,28)+'…' : t.nombre_tour);

new Chart(document.getElementById('chart-tours'), {
    type: 'bar',
    data: {
        labels: toursLabels,
        datasets: [
            {
                label: 'Reservas',
                data: toursData.map(t => t.total),
                backgroundColor: 'rgba(29,78,216,.18)',
                borderColor: '#1d4ed8',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
                yAxisID: 'y',
            },
            {
                label: 'Ingresos (S/)',
                data: toursData.map(t => parseFloat(t.ingresos || 0)),
                type: 'line',
                borderColor: '#d97706',
                backgroundColor: 'rgba(217,119,6,.07)',
                borderWidth: 2.5,
                pointRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#d97706',
                pointBorderWidth: 2.5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4,
                yAxisID: 'yIng',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'top', align: 'end',
                labels: { boxWidth: 12, padding: 20, font: { size: 12 } }
            },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        if (ctx.datasetIndex === 0) return '  Reservas: ' + ctx.parsed.y;
                        return '  Ingresos: S/ ' + ctx.parsed.y.toLocaleString('es-PE');
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 }, maxRotation: 20 } },
            y: {
                position: 'left',
                grid: { color: '#f3f4f6' },
                ticks: { stepSize: 1, font: { size: 11 } },
                title: { display: true, text: 'Reservas', font: { size: 11 }, color: '#1d4ed8' }
            },
            yIng: {
                position: 'right',
                grid: { drawOnChartArea: false },
                ticks: { callback: v => 'S/'+v.toLocaleString('es-PE'), font: { size: 11 } },
                title: { display: true, text: 'Ingresos', font: { size: 11 }, color: '#d97706' }
            }
        }
    }
});
@endif

// ──────────────────────────────────────────────
// FILTRO PERÍODO — visual (los botones aplican
// recarga con ?periodo=N si el controller lo soporta)
// ──────────────────────────────────────────────
document.querySelectorAll('.period-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const dias = this.dataset.period;
        const url  = new URL(window.location.href);
        url.searchParams.set('periodo', dias);
        window.location.href = url.toString();
    });
});

// Marcar el botón activo según URL actual
const urlParams = new URLSearchParams(window.location.search);
const periodoActual = urlParams.get('periodo');
if (periodoActual) {
    document.querySelectorAll('.period-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.period === periodoActual);
    });
}
</script>
@endpush