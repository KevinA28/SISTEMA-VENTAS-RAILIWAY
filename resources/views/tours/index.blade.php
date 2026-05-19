{{-- =====================================================================
     ARCHIVO: resources/views/tours/index.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Tours / Servicios')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap');

    .tours-wrap { font-family: 'DM Sans', sans-serif; }

    /* ── Header gradient bar ── */
    .page-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #185fa5 100%);
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .page-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .page-header h1 {
        color: #fff;
        font-size: 22px;
        font-weight: 600;
        letter-spacing: -0.3px;
        margin: 0 0 4px;
    }
    .page-header p { color: rgba(255,255,255,0.6); font-size: 13px; margin: 0; }

    /* ── Stats bar ── */
    .stats-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
    }
    .stat-icon.blue  { background: #e6f1fb; color: #185fa5; }
    .stat-icon.green { background: #eaf3de; color: #3b6d11; }
    .stat-icon.amber { background: #faeeda; color: #854f0b; }
    .stat-label { font-size: 12px; color: #64748b; font-weight: 500; letter-spacing: 0.3px; text-transform: uppercase; margin-bottom: 2px; }
    .stat-value { font-size: 22px; font-weight: 600; color: #0f172a; line-height: 1; }

    /* ── Filter panel ── */
    .filter-panel {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 160px; }
    .filter-group label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .filter-group input,
    .filter-group select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #378add;
        box-shadow: 0 0 0 3px rgba(55,138,221,0.1);
        background: #fff;
    }
    .btn-filter {
        background: #0f172a;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .15s;
        font-family: 'DM Sans', sans-serif;
        white-space: nowrap;
    }
    .btn-filter:hover { background: #1e293b; }
    .btn-clear { font-size: 12px; color: #94a3b8; text-decoration: none; padding: 9px 4px; white-space: nowrap; }
    .btn-clear:hover { color: #475569; }

    /* ── Table card ── */
    .table-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table-card thead tr { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .table-card thead th {
        padding: 11px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        white-space: nowrap;
    }
    .table-card thead th.center { text-align: center; }
    .table-card thead th.right  { text-align: right; }
    .table-card tbody tr { border-bottom: 0.5px solid #f1f5f9; transition: background .12s; }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #fafbff; }
    .table-card tbody tr.inactive { opacity: 0.55; }
    .table-card td { padding: 14px 16px; vertical-align: middle; }
    .table-card td.center { text-align: center; }
    .table-card td.right  { text-align: right; }

    /* Tour name cell */
    .tour-name { font-weight: 600; color: #0f172a; font-size: 13.5px; }
    .tour-desc { font-size: 12px; color: #94a3b8; margin-top: 2px; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tour-duration { font-size: 11.5px; color: #378add; margin-top: 3px; display: flex; align-items: center; gap: 3px; }

    /* Category badge */
    .badge-cat {
        display: inline-block;
        background: #e6f1fb;
        color: #185fa5;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        letter-spacing: 0.2px;
    }

    /* Price */
    .price-val { font-weight: 600; color: #1e293b; font-family: 'DM Mono', monospace; font-size: 13px; }
    .price-empty { color: #cbd5e1; font-size: 14px; }

    /* Usage badge */
    .uses-badge {
        display: inline-flex; align-items: center; justify-content: center;
        background: #f1f5f9; color: #475569;
        font-size: 12px; font-weight: 600;
        min-width: 28px; height: 24px; padding: 0 8px;
        border-radius: 20px;
        font-family: 'DM Mono', monospace;
    }

    /* Toggle status button */
    .status-btn {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 11.5px; font-weight: 600;
        padding: 4px 10px; border-radius: 20px;
        border: none; cursor: pointer;
        transition: all .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .status-btn.active   { background: #eaf3de; color: #3b6d11; }
    .status-btn.active:hover { background: #c0dd97; }
    .status-btn.inactive { background: #f1f5f9; color: #64748b; }
    .status-btn.inactive:hover { background: #e2e8f0; }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .status-dot.active   { background: #639922; }
    .status-dot.inactive { background: #94a3b8; }

    /* Action buttons */
    .btn-edit {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f0f7ff; color: #185fa5;
        border: none; border-radius: 7px;
        padding: 6px 12px; font-size: 12px; font-weight: 600;
        text-decoration: none; cursor: pointer;
        transition: all .15s; white-space: nowrap;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-edit:hover { background: #b5d4f4; color: #0c447c; }
    .btn-delete {
        display: inline-flex; align-items: center; justify-content: center;
        background: #fff5f5; color: #a32d2d;
        border: none; border-radius: 7px;
        width: 30px; height: 30px; cursor: pointer;
        font-size: 14px;
        transition: all .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-delete:hover { background: #f7c1c1; color: #791f1f; }
    .actions-cell { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }

    /* Empty state */
    .empty-state { padding: 60px 20px; text-align: center; }
    .empty-icon { font-size: 36px; color: #cbd5e1; margin-bottom: 12px; }
    .empty-title { font-size: 15px; font-weight: 600; color: #475569; margin-bottom: 4px; }
    .empty-sub { font-size: 13px; color: #94a3b8; }
    .empty-sub a { color: #378add; text-decoration: none; }
    .empty-sub a:hover { text-decoration: underline; }

    /* Pagination */
    .pagination-wrap { margin-top: 16px; display: flex; justify-content: flex-end; }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-bar { grid-template-columns: 1fr; }
        .stat-hide { display: none; }
        .sm-hide { display: none; }
    }
    @media (max-width: 1024px) {
        .md-hide { display: none; }
        .lg-hide { display: none; }
    }
</style>
@endpush

@section('contenido')
<div class="tours-wrap max-w-6xl mx-auto px-4 py-6">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div class="flex items-center justify-between gap-4 relative z-10">
            <div>
                <h1><i class="bi bi-briefcase-fill me-2"></i> Tours &amp; Servicios</h1>
                <p>Gestiona los paquetes disponibles en el autocompletado de reservas</p>
            </div>
            <a href="{{ route('admin.tours.create') }}"
               style="background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:7px; backdrop-filter:blur(4px); transition:background .15s; white-space:nowrap;"
               onmouseover="this.style.background='rgba(255,255,255,0.25)'"
               onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <i class="bi bi-plus-lg"></i> Nuevo tour
            </a>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-briefcase"></i></div>
            <div>
                <div class="stat-label">Total tours</div>
                <div class="stat-value">{{ $totalTours }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-label">Activos</div>
                <div class="stat-value">{{ $totalActivos }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-bar-chart"></i></div>
            <div>
                <div class="stat-label">Usos totales</div>
                <div class="stat-value">{{ $totalUsos }}</div>
            </div>
        </div>
    </div>

    {{-- ── Filtros ── --}}
    <form method="GET" class="filter-panel">
        <div class="filter-group">
            <label>Buscar nombre</label>
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Arequipa, Cusco…">
        </div>
        <div class="filter-group" style="min-width:150px; flex:0;">
            <label>Categoría</label>
            <select name="categoria">
                <option value="">Todas</option>
                @foreach(\App\Models\Tour::CATEGORIAS as $key => $label)
                    <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group" style="min-width:120px; flex:0;">
            <label>Estado</label>
            <select name="activo">
                <option value="">Todos</option>
                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
            </select>
        </div>
        <button type="submit" class="btn-filter">
            <i class="bi bi-search"></i> Filtrar
        </button>
        @if(request()->hasAny(['buscar','categoria','activo']))
            <a href="{{ route('admin.tours.index') }}" class="btn-clear">✕ Limpiar</a>
        @endif
    </form>

    {{-- ── Tabla ── --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Nombre del tour</th>
                    <th class="sm-hide">Categoría</th>
                    <th class="center md-hide">Precio adulto</th>
                    <th class="center md-hide">Precio niño</th>
                    <th class="center lg-hide">Usos</th>
                    <th class="center">Estado</th>
                    <th class="right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tours as $tour)
                <tr class="{{ $tour->activo ? '' : 'inactive' }}">

                    {{-- Nombre --}}
                    <td>
                        <div class="tour-name">{{ $tour->nombre }}</div>
                        @if($tour->descripcion)
                            <div class="tour-desc">{{ $tour->descripcion }}</div>
                        @endif
                        @if($tour->duracion_horas)
                            <div class="tour-duration">
                                <i class="bi bi-clock"></i> {{ $tour->duracion_horas }}h
                            </div>
                        @endif
                    </td>

                    {{-- Categoría --}}
                    <td class="sm-hide">
                        <span class="badge-cat">
                            {{ \App\Models\Tour::CATEGORIAS[$tour->categoria] ?? $tour->categoria }}
                        </span>
                    </td>

                    {{-- Precio adulto --}}
                    <td class="center md-hide">
                        @if($tour->precio_adulto)
                            <span class="price-val">S/ {{ number_format($tour->precio_adulto, 2) }}</span>
                        @else
                            <span class="price-empty">—</span>
                        @endif
                    </td>

                    {{-- Precio niño --}}
                    <td class="center md-hide">
                        @if($tour->precio_nino)
                            <span class="price-val">S/ {{ number_format($tour->precio_nino, 2) }}</span>
                        @else
                            <span class="price-empty">—</span>
                        @endif
                    </td>

                    {{-- Usos --}}
                    <td class="center lg-hide">
                        <span class="uses-badge">{{ $tour->veces_usado }}</span>
                    </td>

                    {{-- Estado --}}
                    <td class="center">
                        <form method="POST" action="{{ route('admin.tours.toggleActivo', $tour) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="status-btn {{ $tour->activo ? 'active' : 'inactive' }}">
                                <span class="status-dot {{ $tour->activo ? 'active' : 'inactive' }}"></span>
                                {{ $tour->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                        </form>
                    </td>

                    {{-- Acciones --}}
                    <td>
                        <div class="actions-cell">
                            <a href="{{ route('admin.tours.edit', $tour) }}" class="btn-edit">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.tours.destroy', $tour) }}"
                                  onsubmit="return confirm('¿Eliminar «{{ addslashes($tour->nombre) }}»?\nEsta acción no se puede deshacer.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-briefcase"></i></div>
                            <div class="empty-title">No se encontraron tours</div>
                            <div class="empty-sub">
                                <a href="{{ route('admin.tours.create') }}">Crea el primero</a> para empezar a gestionar tus servicios.
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Paginación ── --}}
@if($tours->hasPages())
    <div class="pagination-wrap">
        {{ $tours->links('pagination::simple-tailwind') }}
    </div>
@endif

</div>
@endsection