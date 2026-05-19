{{-- =====================================================================
     ARCHIVO: resources/views/tours/edit.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Editar Tour')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Mono:wght@400;500&display=swap');

    .edit-wrap { font-family: 'DM Sans', sans-serif; }

    /* ── Header ── */
    .page-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #185fa5 100%);
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
    .hdr-inner { display: flex; align-items: center; gap: 14px; position: relative; z-index: 1; }
    .btn-back {
        width: 36px; height: 36px; border-radius: 9px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 16px; text-decoration: none;
        transition: background .15s; flex-shrink: 0;
    }
    .btn-back:hover { background: rgba(255,255,255,0.22); color: #fff; }
    .hdr-title { color: #fff; font-size: 18px; font-weight: 600; letter-spacing: -0.3px; margin: 0 0 2px; }
    .hdr-sub   { color: rgba(255,255,255,0.55); font-size: 12px; margin: 0; }

    /* ── Error alert ── */
    .alert-error {
        background: #fff5f5;
        border: 1px solid #f7c1c1;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #a32d2d;
    }
    .alert-error ul { list-style: disc; padding-left: 18px; margin: 0; }
    .alert-error li { margin: 2px 0; }

    /* ── Form card ── */
    .form-card {
        background: #fff;
        border: 0.5px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Section dentro del form ── */
    .form-section {
        padding: 22px 24px;
        border-bottom: 0.5px solid #f1f5f9;
    }
    .form-section:last-child { border-bottom: none; }
    .section-title {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .section-title i { font-size: 13px; }

    /* ── Fields ── */
    .field { margin-bottom: 16px; }
    .field:last-child { margin-bottom: 0; }
    .field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 5px;
        letter-spacing: 0.1px;
    }
    .field label .req { color: #e24b4a; margin-left: 2px; }
    .field label .opt { color: #94a3b8; font-weight: 400; }

    .field input[type="text"],
    .field input[type="number"],
    .field select,
    .field textarea {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13px;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border-color .15s, box-shadow .15s, background .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .field input:focus,
    .field select:focus,
    .field textarea:focus {
        border-color: #378add;
        box-shadow: 0 0 0 3px rgba(55,138,221,0.1);
        background: #fff;
    }
    .field input.is-error,
    .field select.is-error,
    .field textarea.is-error {
        border-color: #e24b4a;
        background: #fff;
    }
    .field-error { font-size: 11.5px; color: #a32d2d; margin-top: 4px; }
    .field textarea { resize: none; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }

    /* ── Stats readonly ── */
    .stats-readonly {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 0.5px solid #e2e8f0;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 12px;
        color: #64748b;
    }
    .stat-pill i { font-size: 13px; }
    .stat-pill strong { color: #1e293b; font-weight: 600; }

    /* ── Toggle switch ── */
    .toggle-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .toggle-switch { position: relative; display: inline-block; width: 42px; height: 24px; flex-shrink: 0; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .toggle-slider {
        position: absolute; inset: 0;
        background: #e2e8f0;
        border-radius: 24px;
        transition: background .2s;
        cursor: pointer;
    }
    .toggle-slider::before {
        content: '';
        position: absolute;
        width: 18px; height: 18px;
        left: 3px; top: 3px;
        background: #fff;
        border-radius: 50%;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.15);
    }
    .toggle-switch input:checked + .toggle-slider { background: #378add; }
    .toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }
    .toggle-label { font-size: 13px; font-weight: 500; color: #334155; cursor: pointer; }
    .toggle-hint  { font-size: 11.5px; color: #94a3b8; margin-top: 1px; }

    /* ── Footer buttons ── */
    .form-footer {
        padding: 16px 24px;
        background: #f8fafc;
        border-top: 0.5px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-save {
        background: #185fa5;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 9px 22px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background .15s;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-save:hover { background: #0c447c; }
    .btn-cancel {
        color: #64748b;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        padding: 9px 14px;
        border-radius: 8px;
        transition: background .15s, color .15s;
    }
    .btn-cancel:hover { background: #f1f5f9; color: #334155; }
</style>
@endpush

@section('contenido')
<div class="edit-wrap max-w-2xl mx-auto px-4 py-6">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div class="hdr-inner">
            <a href="{{ route('admin.tours.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="hdr-title"><i class="bi bi-pencil-square me-1"></i> Editar tour</div>
                <div class="hdr-sub">{{ $tour->nombre }}</div>
            </div>
        </div>
    </div>

    {{-- ── Errores ── --}}
    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tours.update', $tour) }}">
    @csrf
    @method('PUT')

        <div class="form-card">

            {{-- ── Información básica ── --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-info-circle"></i> Información básica
                </div>

                <div class="field">
                    <label>Nombre del tour <span class="req">*</span></label>
                    <input type="text" name="nombre"
                           value="{{ old('nombre', $tour->nombre) }}"
                           class="{{ $errors->has('nombre') ? 'is-error' : '' }}"
                           maxlength="200" required
                           placeholder="Ej: City Tour Cajamarca">
                    @error('nombre')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Categoría <span class="req">*</span></label>
                    <select name="categoria"
                            class="{{ $errors->has('categoria') ? 'is-error' : '' }}" required>
                        <option value="">— Seleccionar categoría —</option>
                        @foreach($categorias as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('categoria', $tour->categoria) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria')<div class="field-error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label>Descripción <span class="opt">(opcional)</span></label>
                    <textarea name="descripcion" rows="3" maxlength="1000"
                              placeholder="Breve descripción del tour o servicio…">{{ old('descripcion', $tour->descripcion) }}</textarea>
                    @error('descripcion')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ── Precios y duración ── --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-tag"></i> Precios y duración
                </div>

                <div class="grid-2">
                    <div class="field" style="margin-bottom:0">
                        <label>Precio adulto <span class="opt">(S/)</span></label>
                        <input type="number" name="precio_adulto"
                               value="{{ old('precio_adulto', $tour->precio_adulto) }}"
                               step="0.01" min="0" placeholder="0.00">
                        @error('precio_adulto')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field" style="margin-bottom:0">
                        <label>Precio niño <span class="opt">(S/)</span></label>
                        <input type="number" name="precio_nino"
                               value="{{ old('precio_nino', $tour->precio_nino) }}"
                               step="0.01" min="0" placeholder="0.00">
                        @error('precio_nino')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="field" style="margin-top:14px; max-width:180px;">
                    <label>Duración <span class="opt">(horas)</span></label>
                    <input type="number" name="duracion_horas"
                           value="{{ old('duracion_horas', $tour->duracion_horas) }}"
                           min="1" max="72" placeholder="Ej: 8">
                    @error('duracion_horas')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ── Estadísticas solo lectura ── --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-bar-chart-line"></i> Estadísticas
                </div>
                <div class="stats-readonly">
                    <div class="stat-pill">
                        <i class="bi bi-repeat text-blue-500"></i>
                        Usado en <strong>{{ $tour->veces_usado }}</strong> reservas
                    </div>
                    <div class="stat-pill">
                        <i class="bi bi-calendar3"></i>
                        Creado {{ $tour->created_at->diffForHumans() }}
                    </div>
                    <div class="stat-pill">
                        <i class="bi bi-pencil"></i>
                        Actualizado {{ $tour->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            {{-- ── Estado ── --}}
            <div class="form-section">
                <div class="section-title">
                    <i class="bi bi-toggles"></i> Estado
                </div>
                <div class="toggle-row">
                    <input type="hidden" name="activo" value="0">
                    <label class="toggle-switch">
                        <input type="checkbox" name="activo" value="1" id="activo"
                               {{ old('activo', $tour->activo ? '1' : '0') == '1' ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    <div>
                        <label for="activo" class="toggle-label">Tour activo</label>
                        <div class="toggle-hint">Visible en el autocompletado de reservas</div>
                    </div>
                </div>
            </div>

            {{-- ── Footer ── --}}
            <div class="form-footer">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-lg"></i> Guardar cambios
                </button>
                <a href="{{ route('admin.tours.index') }}" class="btn-cancel">
                    Cancelar
                </a>
            </div>

        </div>
    </form>

</div>
@endsection