@extends('layouts.app')
@section('titulo', 'Clientes')

@push('styles')
<style>
:root {
    --ink:#0d1117;--ink-2:#374151;--ink-3:#6b7280;--ink-4:#9ca3af;
    --line:#e5e7eb;--line-2:#f3f4f6;
    --blue:#1d4ed8;--blue-l:#eff6ff;--blue-m:#dbeafe;
    --green:#059669;--green-l:#ecfdf5;
    --amber:#d97706;--amber-l:#fffbeb;
    --red:#dc2626;--red-l:#fef2f2;
    --purple:#7c3aed;--purple-l:#f5f3ff;
}
body { font-family:'DM Sans',sans-serif; }

.page-topbar {
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;
}
.page-title { font-size:1.35rem;font-weight:700;color:var(--ink);display:flex;align-items:center;gap:.5rem; }
.page-title i { color:var(--blue); }
.page-subtitle { font-size:.82rem;color:var(--ink-4);margin-top:2px; }

.filtros-card {
    background:white;border:1px solid var(--line);border-radius:14px;
    padding:1rem 1.25rem;margin-bottom:1rem;
    display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;
}
.search-wrap { position:relative;flex:1;min-width:220px; }
.search-ico { position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--ink-4);font-size:.85rem;pointer-events:none; }
.input-search {
    width:100%;padding:9px 36px 9px 34px;border:1.5px solid var(--line);border-radius:10px;
    font-size:.85rem;color:var(--ink);background:white;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
}
.input-search:focus { border-color:var(--blue);box-shadow:0 0 0 3px #dbeafe; }
.input-search::placeholder { color:var(--ink-4); }

.stats-row { display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;margin-bottom:1.25rem; }
.stat-card {
    background:white;border:1px solid var(--line);border-radius:12px;
    padding:.875rem 1rem;
}
.stat-val { font-size:1.6rem;font-weight:700;color:var(--ink);line-height:1; }
.stat-lbl { font-size:.75rem;color:var(--ink-4);margin-top:.25rem; }
.stat-icon { font-size:1.2rem;margin-bottom:.4rem; }

.clientes-card { background:white;border:1px solid var(--line);border-radius:14px;overflow:hidden; }
.clientes-header {
    padding:.9rem 1.25rem;border-bottom:1px solid var(--line);
    display:flex;justify-content:space-between;align-items:center;
}
.clientes-header .title { font-size:.9rem;font-weight:700;color:var(--ink);display:flex;align-items:center;gap:.5rem; }
.count { background:var(--line-2);border-radius:999px;padding:2px 10px;font-size:.72rem;font-weight:700;color:var(--ink-3); }

.clientes-table { width:100%;border-collapse:collapse; }
.clientes-table th {
    padding:.65rem 1rem;font-size:.7rem;font-weight:700;
    text-transform:uppercase;letter-spacing:.06em;color:var(--ink-4);
    background:var(--line-2);border-bottom:1px solid var(--line);
    text-align:left;white-space:nowrap;
}
.clientes-table td {
    padding:.75rem 1rem;font-size:.84rem;color:var(--ink-2);
    border-bottom:1px solid var(--line);vertical-align:middle;
}
.clientes-table tr:last-child td { border-bottom:none; }
.clientes-table tr:hover td { background:#fafbfc; }

.cliente-avatar {
    width:36px;height:36px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.75rem;font-weight:800;flex-shrink:0;
}
.cliente-nombre { font-weight:600;color:var(--ink);font-size:.86rem; }
.cliente-doc { font-size:.72rem;color:var(--ink-4);margin-top:1px; }

/* Insignias */
.insignia {
    display:inline-flex;align-items:center;gap:.25rem;
    padding:3px 8px;border-radius:999px;font-size:.68rem;font-weight:700;
    white-space:nowrap;
}
.ins-nuevo    { background:#f0fdf4;color:#15803d;border:1px solid #86efac; }
.ins-regular  { background:var(--blue-l);color:var(--blue);border:1px solid var(--blue-m); }
.ins-frecuente{ background:var(--amber-l);color:var(--amber);border:1px solid #fcd34d; }
.ins-vip      { background:var(--purple-l);color:var(--purple);border:1px solid #c4b5fd; }

.badge-reservas {
    display:inline-flex;align-items:center;justify-content:center;
    min-width:24px;height:24px;border-radius:999px;
    font-size:.72rem;font-weight:700;padding:0 6px;
}
.br-1 { background:var(--line-2);color:var(--ink-3); }
.br-2 { background:var(--blue-l);color:var(--blue); }
.br-3 { background:var(--amber-l);color:var(--amber); }
.br-4 { background:var(--purple-l);color:var(--purple); }

.monto-total { font-family:'DM Mono',monospace;font-size:.8rem;font-weight:600;color:var(--green); }

.btn-ver {
    padding:5px 12px;border-radius:8px;font-size:.76rem;font-weight:600;
    border:1.5px solid var(--line);color:var(--ink-2);background:white;
    text-decoration:none;transition:all .15s;display:inline-flex;align-items:center;gap:.3rem;
}
.btn-ver:hover { border-color:var(--blue);color:var(--blue);background:var(--blue-l); }

.empty-state { text-align:center;padding:4rem 1rem;color:var(--ink-4); }
.empty-state i { font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.4; }

.pag-footer {
    padding:.875rem 1.25rem;border-top:1px solid var(--line);
    display:flex;justify-content:space-between;align-items:center;
    flex-wrap:wrap;gap:.5rem;background:var(--line-2);
}
.pag-info { font-size:.78rem;color:var(--ink-4); }

@media(max-width:768px) {
    .clientes-table thead { display:none; }
    .clientes-table tr { display:block;padding:.75rem 1rem;border-bottom:1px solid var(--line); }
    .clientes-table td { display:flex;justify-content:space-between;align-items:center;padding:.2rem 0;border:none; }
    .clientes-table td::before { content:attr(data-label);font-size:.7rem;font-weight:700;color:var(--ink-4);text-transform:uppercase; }
}
</style>
@endpush

@section('contenido')

<div class="page-topbar">
    <div>
        <div class="page-title"><i class="bi bi-people-fill"></i> Clientes</div>
        <div class="page-subtitle">Base de clientes registrados en el sistema</div>
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--blue)"><i class="bi bi-people"></i></div>
        <div class="stat-val">{{ $clientes->total() }}</div>
        <div class="stat-lbl">Total clientes</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--purple)"><i class="bi bi-star-fill"></i></div>
        <div class="stat-val">{{ $clientes->filter(fn($c) => $c->reservas_count >= 3)->count() }}</div>
        <div class="stat-lbl">Clientes VIP (3+ reservas)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--amber)"><i class="bi bi-award-fill"></i></div>
        <div class="stat-val">{{ $clientes->filter(fn($c) => $c->reservas_count == 2)->count() }}</div>
        <div class="stat-lbl">Clientes frecuentes (2 reservas)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="color:var(--green)"><i class="bi bi-person-check"></i></div>
        <div class="stat-val">{{ $clientes->filter(fn($c) => $c->reservas_count == 1)->count() }}</div>
        <div class="stat-lbl">Clientes nuevos (1 reserva)</div>
    </div>
</div>

{{-- Buscador --}}
<form method="GET" action="{{ route('clientes.index') }}" class="filtros-card">
    <div class="search-wrap">
        <i class="bi bi-search search-ico"></i>
        <input type="text" name="buscar" value="{{ request('buscar') }}"
               class="input-search" placeholder="Buscar por nombre, DNI, teléfono o email..."
               autocomplete="off">
    </div>
    @if(request('buscar'))
    <a href="{{ route('clientes.index') }}" style="
        padding:8px 12px;border-radius:10px;font-size:.84rem;cursor:pointer;
        color:var(--ink-4);border:1.5px solid var(--line);background:white;
        text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;
    "><i class="bi bi-x-lg"></i></a>
    @endif
</form>

{{-- Tabla --}}
<div class="clientes-card">
    <div class="clientes-header">
        <div class="title">
            <i class="bi bi-table" style="color:var(--blue)"></i>
            Lista de Clientes
            <span class="count">{{ $clientes->total() }}</span>
        </div>
    </div>

    @if($clientes->isEmpty())
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <p>No se encontraron clientes.</p>
    </div>
    @else
    <div style="overflow-x:auto">
    <table class="clientes-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Documento</th>
                <th>Reservas</th>
                <th>Total gastado</th>
                <th>Insignia</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($clientes as $i => $cliente)
        @php
            $n     = $clientes->firstItem() + $i;
            $count = $cliente->reservas_count;
            $total = $cliente->reservas_sum_precio_total ?? 0;
            $ini   = strtoupper(substr($cliente->nombre_completo ?? $cliente->razon_social ?? '?', 0, 2));

            // Color avatar según rango
            if ($count >= 5)      { $avatarBg = '#f5f3ff'; $avatarColor = '#7c3aed'; }
            elseif ($count >= 3)  { $avatarBg = '#fffbeb'; $avatarColor = '#d97706'; }
            elseif ($count >= 2)  { $avatarBg = '#eff6ff'; $avatarColor = '#1d4ed8'; }
            else                   { $avatarBg = '#f0fdf4'; $avatarColor = '#059669'; }

            // Badge reservas
            if ($count >= 5)      $brClass = 'br-4';
            elseif ($count >= 3)  $brClass = 'br-3';
            elseif ($count >= 2)  $brClass = 'br-2';
            else                   $brClass = 'br-1';

            // Insignia
            if ($count >= 5) {
                $insignia = '<span class="insignia ins-vip"><i class="bi bi-gem"></i> VIP</span>';
            } elseif ($count >= 3) {
                $insignia = '<span class="insignia ins-frecuente"><i class="bi bi-star-fill"></i> Frecuente</span>';
            } elseif ($count >= 2) {
                $insignia = '<span class="insignia ins-regular"><i class="bi bi-arrow-repeat"></i> Regular</span>';
            } else {
                $insignia = '<span class="insignia ins-nuevo"><i class="bi bi-person-plus"></i> Nuevo</span>';
            }
        @endphp
        <tr>
            <td data-label="#">
                <span style="font-size:.75rem;color:var(--ink-4);font-family:'DM Mono',monospace;">{{ $n }}</span>
            </td>
            <td data-label="Cliente">
                <div style="display:flex;align-items:center;gap:.65rem;">
                    <div class="cliente-avatar" style="background:{{ $avatarBg }};color:{{ $avatarColor }}">
                        {{ $ini }}
                    </div>
                    <div>
                        <div class="cliente-nombre">{{ $cliente->nombre_completo ?? $cliente->razon_social }}</div>
                        @if($cliente->email)
                        <div class="cliente-doc"><i class="bi bi-envelope" style="font-size:.65rem"></i> {{ $cliente->email }}</div>
                        @endif
                    </div>
                </div>
            </td>
            <td data-label="Contacto">
                @if($cliente->telefono)
                <div style="font-size:.82rem;color:var(--ink-2);display:flex;align-items:center;gap:.3rem;">
                    <i class="bi bi-telephone-fill" style="font-size:.7rem;color:var(--ink-4)"></i>
                    {{ $cliente->telefono }}
                </div>
                @else
                <span style="color:var(--ink-4);font-size:.78rem;">—</span>
                @endif
            </td>
            <td data-label="Documento">
                @if($cliente->numero_documento)
                <span style="font-family:'DM Mono',monospace;font-size:.78rem;color:var(--ink-3);">
                    {{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}
                </span>
                @else
                <span style="color:var(--ink-4);font-size:.78rem;">—</span>
                @endif
            </td>
            <td data-label="Reservas">
                <span class="badge-reservas {{ $brClass }}">{{ $count }}</span>
            </td>
            <td data-label="Total gastado">
                @if($total > 0)
                <span class="monto-total">S/ {{ number_format($total, 2) }}</span>
                @else
                <span style="color:var(--ink-4);font-size:.78rem;">—</span>
                @endif
            </td>
            <td data-label="Insignia">
                {!! $insignia !!}
            </td>
            <td>
                <a href="{{ route('clientes.show', $cliente) }}" class="btn-ver">
                    <i class="bi bi-eye"></i> Ver
                </a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>

    @if($clientes->hasPages())
    <div class="pag-footer">
        <div class="pag-info">
            Mostrando {{ $clientes->firstItem() }}–{{ $clientes->lastItem() }} de {{ $clientes->total() }} clientes
        </div>
        {{ $clientes->links() }}
    </div>
    @endif
    @endif
</div>

@endsection
