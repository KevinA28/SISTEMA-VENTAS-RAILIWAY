{{-- =====================================================================
     ARCHIVO: resources/views/ciudades/index.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Ciudades / Ubigeo')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap');

    .ciudades-wrap { font-family: 'DM Sans', sans-serif; }

    /* ── Header ── */
    .page-header {
        background: linear-gradient(135deg, #0f172a 0%, #2d1f5e 60%, #534ab7 100%);
        border-radius: 16px;
        padding: 22px 28px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .page-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    .hdr-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; position: relative; z-index: 1; flex-wrap: wrap; }
    .hdr-title { color: #fff; font-size: 18px; font-weight: 600; letter-spacing: -0.3px; margin: 0 0 2px; }
    .hdr-sub   { color: rgba(255,255,255,0.55); font-size: 12px; margin: 0; }
    .btn-new-hdr {
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff; border-radius: 9px;
        padding: 9px 16px; font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
        cursor: pointer; transition: background .15s;
        font-family: 'DM Sans', sans-serif; white-space: nowrap;
    }
    .btn-new-hdr:hover { background: rgba(255,255,255,0.25); }

    /* ── Stats ── */
    .stats-bar { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 20px; }
    .stat-card { background: #fff; border: 0.5px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; }
    .stat-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
    .stat-icon.purple { background: #eeedfe; color: #534ab7; }
    .stat-icon.teal   { background: #e1f5ee; color: #0f6e56; }
    .stat-icon.amber  { background: #faeeda; color: #854f0b; }
    .stat-label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 2px; }
    .stat-value { font-size: 20px; font-weight: 600; color: #0f172a; line-height: 1; }

    /* ── Filter panel ── */
    .filter-panel {
        background: #fff; border: 0.5px solid #e2e8f0; border-radius: 12px;
        padding: 14px 18px; margin-bottom: 18px;
        display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: 4px; flex: 1; min-width: 160px; }
    .filter-group label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; }
    .filter-group input,
    .filter-group select {
        border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 11px;
        font-size: 13px; color: #1e293b; background: #f8fafc; outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .filter-group input:focus, .filter-group select:focus {
        border-color: #534ab7; box-shadow: 0 0 0 3px rgba(83,74,183,0.1); background: #fff;
    }
    .btn-filter {
        background: #0f172a; color: #fff; border: none; border-radius: 8px;
        padding: 9px 16px; font-size: 13px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 5px; transition: background .15s;
        font-family: 'DM Sans', sans-serif; white-space: nowrap;
    }
    .btn-filter:hover { background: #1e293b; }
    .btn-clear { font-size: 12px; color: #94a3b8; text-decoration: none; padding: 9px 4px; white-space: nowrap; }
    .btn-clear:hover { color: #475569; }

    /* ── Table card ── */
    .table-card { background: #fff; border: 0.5px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
    .table-card table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .table-card thead tr { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .table-card thead th {
        padding: 10px 16px; text-align: left;
        font-size: 10px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: .6px; white-space: nowrap;
    }
    .table-card thead th.right { text-align: right; }
    .table-card tbody tr { border-bottom: 0.5px solid #f1f5f9; transition: background .12s; }
    .table-card tbody tr:last-child { border-bottom: none; }
    .table-card tbody tr:hover { background: #fafaff; }
    .table-card td { padding: 12px 16px; vertical-align: middle; }
    .table-card td.right { text-align: right; }

    .distrito-name { font-weight: 600; color: #0f172a; font-size: 13px; }
    .provincia-txt { font-size: 12.5px; color: #475569; }
    .badge-depto {
        display: inline-block; background: #eeedfe; color: #3c3489;
        font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px;
    }
    .ubigeo-code { font-family: 'DM Mono', monospace; font-size: 12px; color: #94a3b8; }
    .btn-delete {
        display: inline-flex; align-items: center; justify-content: center;
        background: #fff5f5; color: #a32d2d; border: none; border-radius: 7px;
        width: 30px; height: 30px; cursor: pointer; font-size: 14px;
        transition: all .15s; font-family: 'DM Sans', sans-serif;
    }
    .btn-delete:hover { background: #f7c1c1; color: #791f1f; }

    /* ── Empty state ── */
    .empty-state { padding: 56px 20px; text-align: center; }
    .empty-icon  { font-size: 34px; color: #cbd5e1; margin-bottom: 10px; }
    .empty-title { font-size: 14px; font-weight: 600; color: #475569; margin-bottom: 3px; }
    .empty-sub   { font-size: 12.5px; color: #94a3b8; }

    /* ── Pagination ── */
    .pagination-wrap { margin-top: 14px; display: flex; justify-content: flex-end; }

    /* ── Modal ── */
    .modal-backdrop {
        display: none; position: fixed; inset: 0; z-index: 50;
        background: rgba(15,23,42,0.5);
        align-items: center; justify-content: center; padding: 16px;
        backdrop-filter: blur(2px);
    }
    .modal-backdrop.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; width: 100%; max-width: 440px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 22px; border-bottom: 0.5px solid #f1f5f9;
    }
    .modal-title { font-size: 15px; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 8px; }
    .modal-title i { color: #534ab7; }
    .modal-close {
        width: 28px; height: 28px; border-radius: 7px; border: none;
        background: #f1f5f9; color: #64748b; font-size: 16px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .15s; line-height: 1;
    }
    .modal-close:hover { background: #e2e8f0; color: #334155; }
    .modal-body { padding: 20px 22px; }

    /* Modal fields */
    .mfield { margin-bottom: 14px; }
    .mfield:last-of-type { margin-bottom: 0; }
    .mfield label { display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 5px; }
    .mfield label .req { color: #e24b4a; margin-left: 2px; }
    .mfield label .opt { color: #94a3b8; font-weight: 400; }
    .mfield input {
        width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 9px 11px; font-size: 13px; color: #1e293b; background: #f8fafc;
        outline: none; transition: border-color .15s, box-shadow .15s, background .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .mfield input:focus { border-color: #534ab7; box-shadow: 0 0 0 3px rgba(83,74,183,0.1); background: #fff; }
    .mfield input.is-error { border-color: #e24b4a; background: #fff; }
    .mfield-hint  { font-size: 11px; color: #94a3b8; margin-top: 3px; }
    .mfield-error { font-size: 11px; color: #a32d2d; margin-top: 3px; }
    .mfield input.mono { font-family: 'DM Mono', monospace; }

    .modal-footer {
        padding: 14px 22px; background: #f8fafc;
        border-top: 0.5px solid #e2e8f0;
        display: flex; align-items: center; gap: 10px;
    }
    .btn-modal-save {
        background: #534ab7; color: #fff; border: none; border-radius: 8px;
        padding: 9px 20px; font-size: 13px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px; transition: background .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-modal-save:hover { background: #3c3489; }
    .btn-modal-cancel {
        color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500;
        padding: 9px 12px; border-radius: 8px; transition: background .15s, color .15s;
        cursor: pointer; border: none; background: none; font-family: 'DM Sans', sans-serif;
    }
    .btn-modal-cancel:hover { background: #f1f5f9; color: #334155; }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-bar { grid-template-columns: 1fr 1fr; }
        .sm-hide { display: none; }
    }
    @media (max-width: 640px) {
        .stats-bar { grid-template-columns: 1fr; }
        .md-hide { display: none; }
    }
</style>
@endpush

@section('contenido')
<div class="ciudades-wrap max-w-6xl mx-auto px-4 py-6">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div class="hdr-row">
            <div>
                <div class="hdr-title"><i class="bi bi-geo-alt-fill me-1"></i> Ciudades / Ubigeo</div>
                <div class="hdr-sub">Distritos disponibles para el autocompletado de «Ciudad destino»</div>
            </div>
            <button class="btn-new-hdr" onclick="modalCiudad(true)">
                <i class="bi bi-plus-lg"></i> Agregar ciudad
            </button>
        </div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats-bar">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-geo-alt"></i></div>
            <div>
                <div class="stat-label">Total distritos</div>
                <div class="stat-value">{{ $totalCiudades ?? $ciudades->total() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon teal"><i class="bi bi-map"></i></div>
            <div>
                <div class="stat-label">Departamentos</div>
                <div class="stat-value">{{ count($departamentos) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-pin-map"></i></div>
            <div>
                <div class="stat-label">En esta página</div>
                <div class="stat-value">{{ $ciudades->count() }}</div>
            </div>
        </div>
    </div>

    {{-- ── Filtros ── --}}
    <form method="GET" class="filter-panel">
        <div class="filter-group">
            <label>Buscar</label>
            <input type="text" name="buscar" value="{{ request('buscar') }}"
                   placeholder="Distrito, provincia, departamento…">
        </div>
        <div class="filter-group" style="min-width:170px; flex:0;">
            <label>Departamento</label>
            <select name="departamento">
                <option value="">Todos</option>
                @foreach($departamentos as $dep)
                    <option value="{{ $dep }}" {{ request('departamento') == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-filter">
            <i class="bi bi-search"></i> Filtrar
        </button>
        @if(request()->hasAny(['buscar','departamento']))
            <a href="{{ route('admin.ciudades.index') }}" class="btn-clear">✕ Limpiar</a>
        @endif
    </form>

    {{-- ── Tabla ── --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Distrito</th>
                    <th class="sm-hide">Provincia</th>
                    <th>Departamento</th>
                    <th class="md-hide">Código ubigeo</th>
                    <th class="right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ciudades as $ciudad)
                <tr>
                    <td><span class="distrito-name">{{ $ciudad->distrito }}</span></td>
                    <td class="sm-hide"><span class="provincia-txt">{{ $ciudad->provincia }}</span></td>
                    <td>
                        <span class="badge-depto">{{ $ciudad->departamento }}</span>
                    </td>
                    <td class="md-hide">
                        @if($ciudad->codigo_ubigeo)
                            <span class="ubigeo-code">{{ $ciudad->codigo_ubigeo }}</span>
                        @else
                            <span style="color:#e2e8f0">—</span>
                        @endif
                    </td>
                    <td class="right">
                        <form method="POST" action="{{ route('admin.ciudades.destroy', $ciudad->id) }}"
                              onsubmit="return confirm('¿Eliminar «{{ addslashes($ciudad->distrito) }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon"><i class="bi bi-geo-alt"></i></div>
                            <div class="empty-title">No se encontraron registros</div>
                            <div class="empty-sub">Agrega la primera ciudad con el botón de arriba.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Paginación ── --}}
    @if($ciudades->hasPages())
        <div class="pagination-wrap">
            {{ $ciudades->links('pagination::simple-tailwind') }}
        </div>
    @endif

</div>

{{-- ══════════════════════ MODAL ══════════════════════ --}}
<div id="modal-ciudad" class="modal-backdrop">
    <div class="modal-box">

        <div class="modal-header">
            <div class="modal-title">
                <i class="bi bi-geo-alt-fill"></i> Agregar ciudad / distrito
            </div>
            <button class="modal-close" onclick="modalCiudad(false)">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.ciudades.store') }}">
            @csrf
            <div class="modal-body">

                @if($errors->any())
                    <div style="background:#fff5f5;border:1px solid #f7c1c1;border-radius:8px;padding:10px 14px;margin-bottom:14px;font-size:12px;color:#a32d2d;">
                        <ul style="list-style:disc;padding-left:16px;margin:0;">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <div class="mfield">
                    <label>Departamento <span class="req">*</span></label>
                    <input type="text" name="departamento" value="{{ old('departamento') }}"
                           list="lista-deptos"
                           class="{{ $errors->has('departamento') ? 'is-error' : '' }}"
                           placeholder="Ej: Cajamarca" required maxlength="100">
                    <datalist id="lista-deptos">
                        @foreach($departamentos as $dep)<option value="{{ $dep }}">@endforeach
                        @foreach(['Amazonas','Ancash','Apurimac','Arequipa','Ayacucho','Cajamarca','Callao',
                                   'Cusco','Huancavelica','Huanuco','Ica','Junin','La Libertad','Lambayeque',
                                   'Lima','Loreto','Madre de Dios','Moquegua','Pasco','Piura','Puno',
                                   'San Martin','Tacna','Tumbes','Ucayali'] as $d)
                            <option value="{{ $d }}">
                        @endforeach
                    </datalist>
                    @error('departamento')<div class="mfield-error">{{ $message }}</div>@enderror
                </div>

                <div class="mfield">
                    <label>Provincia <span class="req">*</span></label>
                    <input type="text" name="provincia" value="{{ old('provincia') }}"
                           class="{{ $errors->has('provincia') ? 'is-error' : '' }}"
                           placeholder="Ej: Cajamarca" required maxlength="100">
                    @error('provincia')<div class="mfield-error">{{ $message }}</div>@enderror
                </div>

                <div class="mfield">
                    <label>Distrito / Ciudad <span class="req">*</span></label>
                    <input type="text" name="distrito" value="{{ old('distrito') }}"
                           class="{{ $errors->has('distrito') ? 'is-error' : '' }}"
                           placeholder="Ej: Baños del Inca" required maxlength="100">
                    <div class="mfield-hint">Valor que aparecerá en el autocompletado de «Ciudad destino».</div>
                    @error('distrito')<div class="mfield-error">{{ $message }}</div>@enderror
                </div>

                <div class="mfield">
                    <label>Código ubigeo <span class="opt">(opcional)</span></label>
                    <input type="text" name="codigo_ubigeo" value="{{ old('codigo_ubigeo') }}"
                           class="mono {{ $errors->has('codigo_ubigeo') ? 'is-error' : '' }}"
                           placeholder="060101" maxlength="10">
                    @error('codigo_ubigeo')<div class="mfield-error">{{ $message }}</div>@enderror
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-modal-save">
                    <i class="bi bi-check-lg"></i> Agregar
                </button>
                <button type="button" class="btn-modal-cancel" onclick="modalCiudad(false)">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function modalCiudad(open) {
        document.getElementById('modal-ciudad').classList.toggle('open', open);
    }
    @if($errors->any() || old('distrito'))
        document.addEventListener('DOMContentLoaded', () => modalCiudad(true));
    @endif
</script>

@endsection