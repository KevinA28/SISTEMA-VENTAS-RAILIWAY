@extends('layouts.app')
@section('titulo', 'Estadísticas')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --ink:#0d1117;--ink-2:#374151;--ink-3:#6b7280;--ink-4:#9ca3af;
    --line:#e5e7eb;--line-2:#f3f4f6;
    --blue:#1d4ed8;--blue-l:#eff6ff;--blue-m:#dbeafe;
    --green:#059669;--green-l:#ecfdf5;--green-m:#6ee7b7;
    --amber:#d97706;--amber-l:#fffbeb;
    --red:#dc2626;--red-l:#fef2f2;
    --purple:#7c3aed;--purple-l:#f5f3ff;
}
body { font-family:'DM Sans',sans-serif; }

.page-topbar { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem; }
.page-title { font-size:1.35rem;font-weight:700;color:var(--ink);display:flex;align-items:center;gap:.5rem; }
.page-title i { color:var(--blue); }
.page-subtitle { font-size:.82rem;color:var(--ink-4);margin-top:2px; }

/* Métricas principales */
.metrics-grid { display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem; }
.metric-card {
    background:white;border:1px solid var(--line);border-radius:14px;
    padding:1.25rem;position:relative;overflow:hidden;
}
.metric-card::before {
    content:'';position:absolute;top:0;left:0;right:0;height:3px;border-radius:14px 14px 0 0;
}
.metric-card.blue::before { background:var(--blue); }
.metric-card.green::before { background:var(--green); }
.metric-card.amber::before { background:var(--amber); }
.metric-card.red::before { background:var(--red); }
.metric-card.purple::before { background:var(--purple); }

.metric-ico {
    width:40px;height:40px;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;margin-bottom:.875rem;
}
.metric-ico.blue { background:var(--blue-l);color:var(--blue); }
.metric-ico.green { background:var(--green-l);color:var(--green); }
.metric-ico.amber { background:var(--amber-l);color:var(--amber); }
.metric-ico.red { background:var(--red-l);color:var(--red); }
.metric-ico.purple { background:var(--purple-l);color:var(--purple); }

.metric-val { font-size:1.75rem;font-weight:700;color:var(--ink);line-height:1;font-family:'DM Mono',monospace; }
.metric-lbl { font-size:.78rem;color:var(--ink-4);margin-top:.3rem;font-weight:500; }
.metric-delta {
    display:inline-flex;align-items:center;gap:.25rem;
    font-size:.72rem;font-weight:700;padding:2px 8px;border-radius:999px;margin-top:.5rem;
}
.delta-up { background:var(--green-l);color:var(--green); }
.delta-down { background:var(--red-l);color:var(--red); }
.delta-neutral { background:var(--line-2);color:var(--ink-4); }

/* Grid de gráficos */
.charts-grid { display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem; }
.charts-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem; }
@media(max-width:900px) { .charts-grid,.charts-grid-3 { grid-template-columns:1fr; } }

.chart-card {
    background:white;border:1px solid var(--line);border-radius:14px;
    padding:1.25rem;
}
.chart-card.full { grid-column:1/-1; }
.chart-title {
    font-size:.9rem;font-weight:700;color:var(--ink);
    display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;
}
.chart-title i { color:var(--blue);font-size:.85rem; }
.chart-subtitle { font-size:.72rem;color:var(--ink-4);font-weight:400;margin-left:auto; }

/* Barras horizontales custom */
.bar-list { display:flex;flex-direction:column;gap:.6rem; }
.bar-item { display:flex;flex-direction:column;gap:.2rem; }
.bar-label { display:flex;justify-content:space-between;align-items:center; }
.bar-name { font-size:.8rem;font-weight:500;color:var(--ink-2);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:65%; }
.bar-val { font-size:.75rem;font-weight:700;color:var(--ink-3);font-family:'DM Mono',monospace; }
.bar-track { height:7px;background:var(--line);border-radius:999px;overflow:hidden; }
.bar-fill { height:100%;border-radius:999px;transition:width .6s ease; }

/* Dona custom */
.donut-wrap { display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap; }
.donut-legend { display:flex;flex-direction:column;gap:.5rem;flex:1;min-width:120px; }
.legend-item { display:flex;align-items:center;gap:.5rem;font-size:.78rem; }
.legend-dot { width:10px;height:10px;border-radius:50%;flex-shrink:0; }
.legend-name { color:var(--ink-2);font-weight:500;flex:1; }
.legend-pct { color:var(--ink-4);font-family:'DM Mono',monospace;font-size:.72rem; }

/* Tabla top */
.top-table { width:100%;border-collapse:collapse; }
.top-table th {
    padding:.5rem .75rem;font-size:.68rem;font-weight:700;
    text-transform:uppercase;letter-spacing:.06em;color:var(--ink-4);
    background:var(--line-2);border-bottom:1px solid var(--line);text-align:left;
}
.top-table td { padding:.65rem .75rem;font-size:.82rem;color:var(--ink-2);border-bottom:1px solid var(--line); }
.top-table tr:last-child td { border-bottom:none; }
.top-table tr:hover td { background:#fafbfc; }
.rank-badge {
    display:inline-flex;align-items:center;justify-content:center;
    width:22px;height:22px;border-radius:50%;font-size:.65rem;font-weight:800;
}
.rank-1 { background:#fef3c7;color:#92400e; }
.rank-2 { background:#f1f5f9;color:#475569; }
.rank-3 { background:#fef3c7;color:#d97706; }
.rank-n { background:var(--line-2);color:var(--ink-4); }

/* VIP */
.vip-list { display:flex;flex-direction:column;gap:.6rem; }
.vip-item {
    display:flex;align-items:center;gap:.75rem;
    padding:.6rem .75rem;background:var(--line-2);border-radius:10px;
}
.vip-avatar {
    width:34px;height:34px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.72rem;font-weight:800;flex-shrink:0;
    background:var(--purple-l);color:var(--purple);
}
.vip-name { font-size:.84rem;font-weight:600;color:var(--ink); }
.vip-count { font-size:.72rem;color:var(--ink-4);margin-top:1px; }
.vip-badge {
    margin-left:auto;background:var(--purple-l);color:var(--purple);
    border-radius:999px;padding:2px 10px;font-size:.68rem;font-weight:700;
    display:flex;align-items:center;gap:.25rem;
}

/* Semana comparativo */
.week-compare { display:grid;grid-template-columns:1fr 1fr;gap:1rem; }
.week-box { text-align:center;padding:.875rem;background:var(--line-2);border-radius:10px; }
.week-val { font-size:2rem;font-weight:700;color:var(--ink);font-family:'DM Mono',monospace; }
.week-lbl { font-size:.72rem;color:var(--ink-4);margin-top:.2rem; }

.empty-chart { text-align:center;padding:2.5rem 1rem;color:var(--ink-4); }
.empty-chart i { font-size:2rem;display:block;margin-bottom:.5rem;opacity:.3; }
.empty-chart p { font-size:.82rem; }
</style>
@endpush

@section('contenido')

<div class="page-topbar">
    <div>
        <div class="page-title"><i class="bi bi-graph-up-arrow"></i> Estadísticas</div>
        <div class="page-subtitle">Análisis general del negocio · Datos en tiempo real</div>
    </div>
    <div style="font-size:.78rem;color:var(--ink-4);display:flex;align-items:center;gap:.35rem;">
        <i class="bi bi-clock"></i> Actualizado {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

{{-- ── MÉTRICAS PRINCIPALES ── --}}
<div class="metrics-grid">
    <div class="metric-card blue">
        <div class="metric-ico blue"><i class="bi bi-calendar-check-fill"></i></div>
        <div class="metric-val">{{ number_format($totalReservas) }}</div>
        <div class="metric-lbl">Total de reservas</div>
        <div class="metric-delta {{ $estaSemana >= $semanaPasada ? 'delta-up' : 'delta-down' }}">
            <i class="bi bi-arrow-{{ $estaSemana >= $semanaPasada ? 'up' : 'down' }}-short"></i>
            {{ $estaSemana }} esta semana
        </div>
    </div>
    <div class="metric-card green">
        <div class="metric-ico green"><i class="bi bi-cash-stack"></i></div>
        <div class="metric-val">S/ {{ number_format($totalIngresos, 0) }}</div>
        <div class="metric-lbl">Ingresos totales</div>
        <div class="metric-delta delta-neutral">
            <i class="bi bi-info-circle"></i> Precio total reservas
        </div>
    </div>
    <div class="metric-card amber">
        <div class="metric-ico amber"><i class="bi bi-check-circle-fill"></i></div>
        <div class="metric-val">S/ {{ number_format($totalCobrado, 0) }}</div>
        <div class="metric-lbl">Total cobrado</div>
        @if($totalIngresos > 0)
        <div class="metric-delta delta-up">
            <i class="bi bi-percent"></i>
            {{ round($totalCobrado / $totalIngresos * 100) }}% del total
        </div>
        @endif
    </div>
    <div class="metric-card red">
        <div class="metric-ico red"><i class="bi bi-hourglass-split"></i></div>
        <div class="metric-val">S/ {{ number_format($totalPendiente, 0) }}</div>
        <div class="metric-lbl">Saldo pendiente</div>
        <div class="metric-delta delta-neutral"><i class="bi bi-clock"></i> Por cobrar</div>
    </div>
    <div class="metric-card purple">
        <div class="metric-ico purple"><i class="bi bi-people-fill"></i></div>
        <div class="metric-val">{{ number_format($totalClientes) }}</div>
        <div class="metric-lbl">Total clientes</div>
        <div class="metric-delta delta-up">
            <i class="bi bi-gem"></i>
            {{ $clientesVip->count() }} VIP
        </div>
    </div>
</div>

{{-- ── GRÁFICO LÍNEA + DONA ESTADOS ── --}}
<div class="charts-grid">
    {{-- Ingresos por mes --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-bar-chart-line-fill"></i>
            Reservas e ingresos por mes
            <span class="chart-subtitle">Últimos 12 meses</span>
        </div>
        @if($reservasPorMes->isEmpty())
        <div class="empty-chart"><i class="bi bi-bar-chart"></i><p>Sin datos aún</p></div>
        @else
        <canvas id="chart-meses" height="200"></canvas>
        @endif
    </div>

    {{-- Estados --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-pie-chart-fill"></i>
            Reservas por estado
        </div>
        @if($reservasPorEstado->isEmpty())
        <div class="empty-chart"><i class="bi bi-pie-chart"></i><p>Sin datos aún</p></div>
        @else
        <div class="donut-wrap">
            <canvas id="chart-estados" width="160" height="160" style="flex-shrink:0;max-width:160px"></canvas>
            <div class="donut-legend">
                @foreach($reservasPorEstado as $est)
                <div class="legend-item">
                    <div class="legend-dot" style="background:{{ $est['color'] }}"></div>
                    <span class="legend-name">{{ ucfirst(str_replace('_',' ',$est['nombre'])) }}</span>
                    <span class="legend-pct">{{ $est['total'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ── TOP TOURS + CANAL ── --}}
<div class="charts-grid">
    {{-- Top tours --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-trophy-fill"></i>
            Top tours más reservados
        </div>
        @if($topTours->isEmpty())
        <div class="empty-chart"><i class="bi bi-map"></i><p>Sin datos aún</p></div>
        @else
        <div style="overflow-x:auto">
        <table class="top-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tour</th>
                    <th>Reservas</th>
                    <th>Ingresos</th>
                </tr>
            </thead>
            <tbody>
            @foreach($topTours as $i => $tour)
            <tr>
                <td>
                    <span class="rank-badge {{ $i==0 ? 'rank-1' : ($i==1 ? 'rank-2' : ($i==2 ? 'rank-3' : 'rank-n')) }}">
                        {{ $i+1 }}
                    </span>
                </td>
                <td style="font-weight:500;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $tour->nombre_tour }}
                </td>
                <td>
                    <div class="bar-track" style="width:80px;display:inline-block;vertical-align:middle;margin-right:.4rem">
                        <div class="bar-fill" style="width:{{ $topTours->max('total') > 0 ? round($tour->total/$topTours->max('total')*100) : 0 }}%;background:var(--blue)"></div>
                    </div>
                    <span style="font-family:'DM Mono',monospace;font-size:.78rem;font-weight:600">{{ $tour->total }}</span>
                </td>
                <td style="font-family:'DM Mono',monospace;font-size:.78rem;color:var(--green);font-weight:600">
                    S/ {{ number_format($tour->ingresos, 0) }}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

    {{-- Canal de contacto --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-broadcast-pin"></i>
            Canal de contacto
        </div>
        @if($reservasPorCanal->isEmpty())
        <div class="empty-chart"><i class="bi bi-broadcast"></i><p>Sin datos aún</p></div>
        @else
        @php
        $canalesIconos = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2','referido'=>'bi-people'];
        $canalesColores = ['whatsapp'=>'#25d366','presencial'=>'#1d4ed8','llamada'=>'#7c3aed','redes_sociales'=>'#e1306c','web'=>'#0ea5e9','referido'=>'#d97706'];
        $maxCanal = $reservasPorCanal->max('total');
        @endphp
        <div class="bar-list">
            @foreach($reservasPorCanal as $canal)
            @php
                $color = $canalesColores[$canal->canal_contacto] ?? '#6b7280';
                $icon  = $canalesIconos[$canal->canal_contacto] ?? 'bi-chat';
                $pct   = $maxCanal > 0 ? round($canal->total/$maxCanal*100) : 0;
            @endphp
            <div class="bar-item">
                <div class="bar-label">
                    <span class="bar-name">
                        <i class="bi {{ $icon }}" style="color:{{ $color }};margin-right:.3rem"></i>
                        {{ ucfirst(str_replace('_',' ',$canal->canal_contacto)) }}
                    </span>
                    <span class="bar-val">{{ $canal->total }} · {{ $pct }}%</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

{{-- ── PROCEDENCIA + DESTINOS + VIP ── --}}
<div class="charts-grid-3">
    {{-- Procedencia --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-pin-map-fill"></i>
            Procedencia de clientes
        </div>
        @if($clientesPorProcedencia->isEmpty())
        <div class="empty-chart"><i class="bi bi-map"></i><p>Sin datos aún</p></div>
        @else
        @php $maxProc = $clientesPorProcedencia->max('total'); @endphp
        <div class="bar-list">
            @foreach($clientesPorProcedencia as $proc)
            <div class="bar-item">
                <div class="bar-label">
                    <span class="bar-name">{{ $proc->ciudad_procedencia }}</span>
                    <span class="bar-val">{{ $proc->total }}</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $maxProc > 0 ? round($proc->total/$maxProc*100) : 0 }}%;background:var(--blue)"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Destinos --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-geo-alt-fill"></i>
            Top destinos
        </div>
        @if($topDestinos->isEmpty())
        <div class="empty-chart"><i class="bi bi-compass"></i><p>Sin datos aún</p></div>
        @else
        @php $maxDest = $topDestinos->max('total'); @endphp
        <div class="bar-list">
            @foreach($topDestinos as $dest)
            <div class="bar-item">
                <div class="bar-label">
                    <span class="bar-name">{{ $dest->departamento_destino ?? 'Sin departamento' }}</span>
                    <span class="bar-val">{{ $dest->total }}</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $maxDest > 0 ? round($dest->total/$maxDest*100) : 0 }}%;background:var(--green)"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Clientes VIP + semana --}}
    <div class="chart-card">
        <div class="chart-title">
            <i class="bi bi-gem"></i>
            Clientes VIP
        </div>

        {{-- Comparativo semana --}}
        <div class="week-compare" style="margin-bottom:1rem">
            <div class="week-box">
                <div class="week-val" style="color:var(--blue)">{{ $estaSemana }}</div>
                <div class="week-lbl">Esta semana</div>
            </div>
            <div class="week-box">
                <div class="week-val" style="color:var(--ink-4)">{{ $semanaPasada }}</div>
                <div class="week-lbl">Semana pasada</div>
            </div>
        </div>

        @if($clientesVip->isEmpty())
        <div class="empty-chart" style="padding:1.5rem"><i class="bi bi-star"></i><p>Sin clientes VIP aún</p></div>
        @else
        <div class="vip-list">
            @foreach($clientesVip as $vip)
            <div class="vip-item">
                <div class="vip-avatar">{{ strtoupper(substr($vip->nombre_completo ?? '?', 0, 2)) }}</div>
                <div>
                    <div class="vip-name">{{ $vip->nombre_completo }}</div>
                    <div class="vip-count">{{ $vip->telefono ?? 'Sin teléfono' }}</div>
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
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.color = '#6b7280';

@if(!$reservasPorMes->isEmpty())
// Gráfico de meses
const mesesData = @json($reservasPorMes);
const mesesLabels = mesesData.map(m => {
    const [y, mo] = m.mes.split('-');
    const meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    return meses[parseInt(mo)-1] + ' ' + y.slice(2);
});

new Chart(document.getElementById('chart-meses'), {
    type: 'bar',
    data: {
        labels: mesesLabels,
        datasets: [
            {
                label: 'Reservas',
                data: mesesData.map(m => m.total),
                backgroundColor: '#dbeafe',
                borderColor: '#1d4ed8',
                borderWidth: 2,
                borderRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'Ingresos (S/)',
                data: mesesData.map(m => parseFloat(m.ingresos || 0)),
                type: 'line',
                borderColor: '#059669',
                backgroundColor: 'rgba(5,150,105,.08)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#059669',
                fill: true,
                tension: 0.4,
                yAxisID: 'y2',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, padding: 16, font: { size: 12 } } } },
        scales: {
            y:  { position: 'left',  grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } },
            y2: { position: 'right', grid: { drawOnChartArea: false },
                  ticks: { callback: v => 'S/'+v.toLocaleString() } }
        }
    }
});
@endif

@if(!$reservasPorEstado->isEmpty())
// Dona estados
const estadosData = @json($reservasPorEstado);
new Chart(document.getElementById('chart-estados'), {
    type: 'doughnut',
    data: {
        labels: estadosData.map(e => e.nombre),
        datasets: [{
            data: estadosData.map(e => e.total),
            backgroundColor: estadosData.map(e => e.color),
            borderWidth: 2,
            borderColor: '#fff',
            hoverOffset: 4,
        }]
    },
    options: {
        responsive: false,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ' '+ctx.label+': '+ctx.parsed } }
        }
    }
});
@endif
</script>
@endpush
