{{-- =====================================================================
     ARCHIVO: dashboard.blade.php
     UBICACIÓN: resources/views/dashboard.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --ink:    #0d1117; --ink-2: #374151; --ink-3: #6b7280; --ink-4: #9ca3af;
    --line:   #e5e7eb; --line-2: #f3f4f6;
    --blue:   #1d4ed8; --blue-l: #eff6ff; --blue-m: #dbeafe;
    --green:  #059669; --green-l: #ecfdf5; --green-m: #a7f3d0;
    --amber:  #d97706; --amber-l: #fffbeb;
    --red:    #dc2626; --red-l: #fef2f2;
    --purple: #7c3aed; --purple-l: #f5f3ff;
}
body { font-family: 'DM Sans', sans-serif; -webkit-font-smoothing: antialiased; }

/* ── KPI CARDS ── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
@media(max-width:768px){ .kpi-grid { grid-template-columns: 1fr 1fr; } }
@media(max-width:500px){ .kpi-grid { grid-template-columns: 1fr; } }

.kpi-card {
    background: white;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 1.25rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
    transition: box-shadow .15s, transform .15s;
}
.kpi-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); transform: translateY(-1px); }
.kpi-icon {
    width: 44px; height: 44px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.kpi-icon.blue   { background: var(--blue-m);   color: var(--blue); }
.kpi-icon.green  { background: var(--green-m);  color: var(--green); }
.kpi-icon.amber  { background: #fde68a;          color: var(--amber); }
.kpi-icon.purple { background: #ede9fe;          color: var(--purple); }
.kpi-icon.red    { background: var(--red-l);     color: var(--red); }
.kpi-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .09em; color: var(--ink-4); margin-bottom: .18rem; }
.kpi-val   { font-size: 1.65rem; font-weight: 700; color: var(--ink); font-family: 'DM Mono', monospace; line-height: 1; }
.kpi-sub   { font-size: .72rem; color: var(--ink-4); margin-top: .2rem; }

/* ── MAIN GRID ── */
.dash-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1rem;
    align-items: start;
}
@media(max-width:900px){ .dash-grid { grid-template-columns: 1fr; } }

/* ── CARD BASE ── */
.d-card {
    background: white;
    border: 1px solid var(--line);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
}
.d-card-head {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid var(--line);
    display: flex; justify-content: space-between; align-items: center;
    background: #fafbfc;
}
.d-card-head .ttl {
    font-size: .82rem; font-weight: 700; color: var(--ink);
    display: flex; align-items: center; gap: .45rem;
}
.d-card-head .ttl i { color: var(--blue); }
.d-card-body { padding: 1.25rem; }

/* ── TABLA ÚLTIMAS RESERVAS ── */
.dash-table { width: 100%; border-collapse: collapse; }
.dash-table thead th {
    padding: .55rem 1rem;
    font-size: .67rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--ink-4);
    background: var(--line-2); border-bottom: 1px solid var(--line);
    white-space: nowrap;
}
.dash-table thead th:first-child { padding-left: 1.25rem; }
.dash-table tbody td {
    padding: .8rem 1rem; border-bottom: 1px solid var(--line);
    font-size: .84rem; color: var(--ink-2); vertical-align: middle;
}
.dash-table tbody td:first-child { padding-left: 1.25rem; }
.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover td { background: #fafbff; }
.dash-table .empty-cell { text-align: center; padding: 3rem 1rem; color: var(--ink-4); }
.dash-table .empty-cell i { font-size: 2rem; display: block; margin-bottom: .6rem; }

.codigo-chip {
    font-family: 'DM Mono', monospace; font-size: .76rem; font-weight: 600;
    color: var(--blue); background: var(--blue-l);
    padding: 2px 8px; border-radius: 6px; white-space: nowrap;
    text-decoration: none;
}
.codigo-chip:hover { background: var(--blue-m); color: var(--blue); }

.badge-est {
    display: inline-flex; align-items: center; gap: .28rem;
    padding: 3px 9px; border-radius: 999px;
    font-size: .71rem; font-weight: 700; white-space: nowrap;
}
.badge-est::before { content:''; width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0; }
.est-confirmada { background:var(--green-l); color:#065f46; }
.est-mitad_pago { background:var(--blue-l);  color:#1e40af; }
.est-pagado     { background:#f0fdf4;          color:#15803d; }
.est-cancelada  { background:var(--red-l);    color:#991b1b; }
.est-consulta   { background:var(--line-2);   color:#475569; }
.est-pre_reserva{ background:var(--amber-l);  color:#92400e; }
.est-finalizada { background:var(--purple-l); color:#5b21b6; }

/* ── SIDEBAR ── */
.sb-section { margin-bottom: 1rem; }
.sb-section:last-child { margin-bottom: 0; }

/* ── ACCIONES RÁPIDAS ── */
.quick-actions { display: flex; flex-direction: column; gap: .5rem; }
.qa-btn {
    display: flex; align-items: center; gap: .75rem;
    padding: .7rem 1rem;
    border: 1.5px solid var(--line);
    border-radius: 8px;
    text-decoration: none;
    color: var(--ink-2);
    font-size: .84rem; font-weight: 500;
    transition: all .15s;
    background: white;
}
.qa-btn:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }
.qa-btn .qa-ico {
    width: 30px; height: 30px; border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; flex-shrink: 0;
}
.qa-btn.primary { background: var(--blue); color: white; border-color: var(--blue); }
.qa-btn.primary .qa-ico { background: rgba(255,255,255,.15); }
.qa-btn.primary:hover { background: #1e40af; border-color: #1e40af; color: white; }

/* ── ESTADO CHIPS ── */
.estado-list { display: flex; flex-direction: column; gap: .4rem; }
.estado-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: .45rem .65rem; border-radius: 7px; background: var(--line-2);
    font-size: .79rem;
}
.estado-row .er-name { font-weight: 600; color: var(--ink-2); display: flex; align-items: center; gap: .45rem; }
.estado-row .er-dot  { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.estado-row .er-cnt  { font-family: 'DM Mono', monospace; font-size: .78rem; font-weight: 700; color: var(--ink-3); }

.ver-todo {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .78rem; font-weight: 600; color: var(--blue);
    text-decoration: none; padding: 4px 10px;
    border: 1.5px solid var(--blue-m); border-radius: 6px;
    transition: all .15s; background: var(--blue-l);
}
.ver-todo:hover { background: var(--blue-m); }
</style>
@endpush

@section('contenido')

{{-- ── KPI CARDS ── --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon blue"><i class="bi bi-calendar-check"></i></div>
        <div>
            <div class="kpi-label">Reservas hoy</div>
            <div class="kpi-val">{{ $reservasHoy ?? 0 }}</div>
            <div class="kpi-sub">nuevas reservas</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon green"><i class="bi bi-check-circle"></i></div>
        <div>
            <div class="kpi-label">Confirmadas</div>
            <div class="kpi-val">{{ $confirmadas ?? 0 }}</div>
            <div class="kpi-sub">este mes</div>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon amber"><i class="bi bi-people"></i></div>
        <div>
            <div class="kpi-label">Clientes</div>
            <div class="kpi-val">{{ $totalClientes ?? 0 }}</div>
            <div class="kpi-sub">registrados</div>
        </div>
    </div>
</div>

{{-- ── MAIN GRID ── --}}
<div class="dash-grid">

    {{-- Columna principal: últimas reservas --}}
    <div class="d-card">
        <div class="d-card-head">
            <div class="ttl"><i class="bi bi-clock-history"></i> Últimas reservas</div>
            <a href="{{ route('reservas.index') }}" class="ver-todo">
                <i class="bi bi-arrow-right"></i> Ver todas
            </a>
        </div>
        <table class="dash-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cliente</th>
                    <th>Tour</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasReservas ?? [] as $r)
                @php
                    $slug = str_replace(' ','_',strtolower($r->estado->nombre ?? 'consulta'));
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('reservas.show', $r) }}" class="codigo-chip">
                            {{ $r->codigo_reserva }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:.83rem;color:var(--ink);">{{ $r->cliente->nombre_completo }}</div>
                        <div style="font-size:.71rem;color:var(--ink-4);">+51 {{ $r->cliente->telefono_whatsapp ?? '—' }}</div>
                    </td>
                    <td style="max-width:160px;">
                        <div style="font-size:.83rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $r->nombre_tour ?? ($r->fechaTour->tour->nombre ?? '—') }}
                        </div>
                    </td>
                    <td style="white-space:nowrap;font-family:'DM Mono',monospace;font-size:.78rem;">
                        {{ optional($r->fecha_tour)->format('d/m/Y') ?? optional($r->fechaTour?->fecha)->format('d/m/Y') ?? '—' }}
                    </td>
                    <td>
                        <span class="badge-est est-{{ $slug }}">
                            {{ ucfirst(str_replace('_',' ',$r->estado->nombre ?? 'consulta')) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-cell">
                        <i class="bi bi-calendar-x"></i>
                        No hay reservas registradas aún
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Columna derecha: acciones + estados --}}
    <div>
        <div class="d-card sb-section">
            <div class="d-card-head">
                <div class="ttl"><i class="bi bi-lightning-charge"></i> Acciones rápidas</div>
            </div>
            <div class="d-card-body">
                <div class="quick-actions">
                    <a href="{{ route('reservas.create') }}" class="qa-btn primary">
                        <div class="qa-ico"><i class="bi bi-plus-lg"></i></div>
                        <span>Nueva reserva</span>
                    </a>
                    <a href="{{ route('reservas.index') }}" class="qa-btn">
                        <div class="qa-ico" style="background:var(--blue-l);color:var(--blue)"><i class="bi bi-list-ul"></i></div>
                        <span>Ver todas las reservas</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="d-card sb-section">
            <div class="d-card-head">
                <div class="ttl"><i class="bi bi-bar-chart"></i> Reservas por estado</div>
            </div>
            <div class="d-card-body">
                <div class="estado-list">
                    @php
                        $estadosResumen = $estadosResumen ?? [
                            ['nombre'=>'confirmada',  'color'=>'#059669', 'label'=>'Confirmadas',  'cnt'=>0],
                            ['nombre'=>'mitad_pago',  'color'=>'#1d4ed8', 'label'=>'50% Pagado',   'cnt'=>0],
                            ['nombre'=>'pagado',      'color'=>'#15803d', 'label'=>'Pagado',        'cnt'=>0],
                            ['nombre'=>'cancelada',   'color'=>'#dc2626', 'label'=>'Canceladas',    'cnt'=>0],
                        ];
                    @endphp
                    @foreach($estadosResumen as $er)
                    <div class="estado-row">
                        <div class="er-name">
                            <span class="er-dot" style="background:{{ $er['color'] }}"></span>
                            {{ $er['label'] ?? ucfirst(str_replace('_',' ',$er['nombre'])) }}
                        </div>
                        <span class="er-cnt">{{ $er['cnt'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@endsection