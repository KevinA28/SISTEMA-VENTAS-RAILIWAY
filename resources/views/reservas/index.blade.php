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
    --ink:#0d1117;--ink-2:#374151;--ink-3:#6b7280;--ink-4:#9ca3af;
    --line:#e5e7eb;--line-2:#f3f4f6;
    --blue:#1d4ed8;--blue-l:#eff6ff;--blue-m:#dbeafe;
    --green:#059669;--green-l:#ecfdf5;--green-m:#6ee7b7;
    --amber:#d97706;--amber-l:#fffbeb;
    --red:#dc2626;--red-l:#fef2f2;
    --purple:#7c3aed;--purple-l:#f5f3ff;
}
body { font-family:'DM Sans',sans-serif; }

/* ── TOP BAR ── */
.page-topbar {
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;
}
.page-topbar .page-title {
    font-size:1.35rem;font-weight:700;color:var(--ink);
    display:flex;align-items:center;gap:.5rem;
}
.page-topbar .page-title i { color:var(--blue);font-size:1.25rem; }
.page-topbar .page-subtitle { font-size:.82rem;color:var(--ink-4);margin-top:2px; }

.btn-reportes {
    display:inline-flex;align-items:center;gap:.5rem;
    padding:10px 20px;border-radius:10px;font-size:.88rem;font-weight:700;
    font-family:'DM Sans',sans-serif;
    background:linear-gradient(135deg,#059669 0%,#047857 100%);
    color:#fff;border:none;cursor:pointer;
    text-decoration:none;transition:all .2s;
    box-shadow:0 2px 8px rgba(5,150,105,.25);
}
.btn-reportes:hover { opacity:.9;transform:translateY(-1px); }

.btn-pdf-salud {
    display:inline-flex;align-items:center;gap:.5rem;
    padding:10px 20px;border-radius:10px;font-size:.88rem;font-weight:700;
    font-family:'DM Sans',sans-serif;
    background:linear-gradient(135deg,#dc2626 0%,#b91c1c 100%);
    color:#fff;border:none;cursor:pointer;
    text-decoration:none;transition:all .2s;
    box-shadow:0 2px 8px rgba(220,38,38,.25);
}
.btn-pdf-salud:hover { opacity:.9;transform:translateY(-1px); }

/* ── FILTROS ── */
.filtros-card {
    background:white;border:1px solid var(--line);border-radius:14px;
    padding:1rem 1.25rem;margin-bottom:1rem;
    display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;
}

.search-wrap { position:relative;flex:1;min-width:200px; }
.search-ico {
    position:absolute;left:11px;top:50%;transform:translateY(-50%);
    color:var(--ink-4);font-size:.85rem;pointer-events:none;transition:color .15s;
}
.search-wrap:focus-within .search-ico { color:var(--blue); }
.input-search {
    width:100%;padding:9px 36px 9px 34px;
    border:1.5px solid var(--line);border-radius:10px;
    font-size:.85rem;font-family:'DM Sans',sans-serif;
    color:var(--ink);background:white;outline:none;
    transition:border-color .15s,box-shadow .15s;box-sizing:border-box;
}
.input-search:focus { border-color:var(--blue);box-shadow:0 0 0 3px #dbeafe; }
.input-search::placeholder { color:var(--ink-4); }
.search-clear {
    position:absolute;right:9px;top:50%;transform:translateY(-50%);
    background:none;border:none;cursor:pointer;color:var(--ink-4);
    padding:2px 4px;display:none;font-size:.8rem;line-height:1;
    align-items:center;
}
.search-clear:hover { color:var(--red); }
.search-wrap.has-value .search-clear { display:flex; }

.f-ctrl {
    padding:9px 12px;border:1.5px solid var(--line);border-radius:10px;
    font-size:.84rem;font-family:'DM Sans',sans-serif;
    color:var(--ink);background:white;outline:none;
    transition:border-color .15s,box-shadow .15s;
    -webkit-appearance:none;appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat:no-repeat;background-position:right 10px center;padding-right:30px;
}
.f-ctrl:focus { border-color:var(--blue);box-shadow:0 0 0 3px #dbeafe; }

.date-wrap { position:relative; }
.date-wrap i { position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--ink-4);font-size:.8rem;pointer-events:none; }
.input-date {
    padding:9px 12px 9px 30px;border:1.5px solid var(--line);border-radius:10px;
    font-size:.84rem;font-family:'DM Sans',sans-serif;
    color:var(--ink);background:white;outline:none;width:130px;cursor:pointer;
    transition:border-color .15s,box-shadow .15s;
}
.input-date:focus { border-color:var(--blue);box-shadow:0 0 0 3px #dbeafe; }
.input-date.active { border-color:var(--blue);background:var(--blue-l); }

.filtros-divider { width:1px;height:28px;background:var(--line);flex-shrink:0; }

.btn-clear {
    background:transparent;color:var(--ink-4);border:1.5px solid var(--line);
    padding:8px 12px;border-radius:10px;font-size:.84rem;cursor:pointer;
    display:inline-flex;align-items:center;transition:all .15s;text-decoration:none;
}
.btn-clear:hover { color:var(--red);border-color:var(--red); }

/* ── BOTONES ESTADO ── */
.estado-btns { display:flex;gap:.4rem;flex-wrap:wrap;align-items:center; }
.btn-estado {
    padding:7px 14px;border-radius:20px;font-size:.79rem;font-weight:600;
    font-family:'DM Sans',sans-serif;cursor:pointer;
    border:1.5px solid var(--line);background:white;color:var(--ink-3);
    transition:all .15s;white-space:nowrap;
    display:inline-flex;align-items:center;gap:.3rem;
}
.btn-estado::before { content:'';width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0; }
.btn-estado:hover { border-color:var(--ink-3);color:var(--ink-2); }
.btn-estado.act-pagado    { background:var(--green-l);border-color:var(--green);color:var(--green); }
.btn-estado.act-cancelada { background:var(--red-l);border-color:var(--red);color:var(--red); }
.btn-estado.act-mitad     { background:var(--blue-l);border-color:var(--blue);color:var(--blue); }

/* ── CARD LISTA ── */
.reservas-card {
    background:white;border:1px solid var(--line);border-radius:14px;overflow:hidden;
}
.reservas-header {
    padding:.9rem 1.25rem;border-bottom:1px solid var(--line);
    display:flex;justify-content:space-between;align-items:center;
}
.reservas-header .title {
    font-size:.9rem;font-weight:700;color:var(--ink);
    display:flex;align-items:center;gap:.5rem;
}
.reservas-header .title .count {
    background:var(--line-2);border-radius:999px;
    padding:2px 10px;font-size:.72rem;font-weight:700;color:var(--ink-3);
}

/* ── GRID TARJETAS ── */
.cards-grid {
    display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:1rem;padding:1.25rem;
}

.reserva-card {
    border:1.5px solid var(--line);border-radius:12px;
    padding:1rem 1.1rem;background:white;
    transition:box-shadow .15s,border-color .15s,transform .1s;
    display:flex;flex-direction:column;gap:.75rem;position:relative;
}
.reserva-card:hover {
    border-color:var(--blue-m);
    box-shadow:0 4px 16px rgba(29,78,216,.08);
    transform:translateY(-1px);
}

.rc-head { display:flex;justify-content:space-between;align-items:flex-start;gap:.5rem; }
.codigo-reserva {
    font-family:'DM Mono',monospace;font-size:.76rem;font-weight:600;
    color:var(--blue);background:var(--blue-l);padding:3px 9px;
    border-radius:6px;white-space:nowrap;text-decoration:none;transition:background .15s;
}
.codigo-reserva:hover { background:var(--blue-m); }

.rc-cliente { display:flex;flex-direction:column;gap:2px; }
.rc-cliente .nombre { font-weight:700;font-size:.88rem;color:var(--ink); }
.rc-cliente .tel { font-size:.73rem;color:var(--ink-4);display:flex;align-items:center;gap:.3rem; }

.rc-tour { font-size:.83rem;color:var(--ink-2);font-weight:500;display:flex;align-items:center;gap:.35rem; }
.rc-tour i { color:var(--ink-4);font-size:.75rem; }

.rc-fecha-hora { display:flex;align-items:center;gap:.5rem;font-size:.78rem;color:var(--ink-3); }
.rc-fecha-hora .fecha { font-weight:600;color:var(--ink-2); }
.rc-dot { width:3px;height:3px;border-radius:50%;background:var(--line);flex-shrink:0; }

.rc-pago { display:flex;flex-direction:column;gap:4px; }
.rc-pago-row { display:flex;align-items:center;gap:.5rem; }
.pago-bar { flex:1;height:6px;background:var(--line);border-radius:999px;overflow:hidden; }
.pago-bar-fill { height:100%;border-radius:999px;transition:width .5s ease; }
.pago-pct { font-size:.75rem;font-weight:700;font-family:'DM Mono',monospace;white-space:nowrap;min-width:34px;text-align:right; }
.pago-detalle { font-size:.71rem;color:var(--ink-4);font-family:'DM Mono',monospace; }

.rc-footer { display:flex;justify-content:space-between;align-items:center;gap:.5rem;flex-wrap:wrap; }
.rc-footer-left { display:flex;align-items:center;gap:.4rem;flex-wrap:wrap; }

.badge-estado {
    display:inline-flex;align-items:center;gap:.3rem;
    padding:4px 10px;border-radius:999px;
    font-size:.7rem;font-weight:700;white-space:nowrap;
}
.badge-estado::before { content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0; }
.est-consulta    { background:#f1f5f9;color:#475569; }
.est-pre_reserva { background:#fffbeb;color:#92400e; }
.est-confirmada  { background:#ecfdf5;color:#065f46; }
.est-mitad_pago  { background:#eff6ff;color:#1e40af; }
.est-pagado      { background:#f0fdf4;color:#15803d; }
.est-cancelada   { background:#fef2f2;color:#991b1b; }
.est-finalizada  { background:#f5f3ff;color:#5b21b6; }

.canal-badge { display:inline-flex;align-items:center;gap:.3rem;font-size:.73rem;color:var(--ink-3);font-weight:500; }

.btn-tabla {
    padding:5px 12px;border-radius:8px;font-size:.78rem;font-weight:600;
    font-family:'DM Sans',sans-serif;cursor:pointer;
    display:inline-flex;align-items:center;gap:.3rem;
    text-decoration:none;transition:all .15s;
    border:1.5px solid var(--line);color:var(--ink-2);background:white;
}
.btn-tabla:hover { border-color:var(--blue);color:var(--blue);background:var(--blue-l); }

.btn-completar-card {
    padding:5px 12px;border-radius:8px;font-size:.78rem;font-weight:600;
    font-family:'DM Sans',sans-serif;cursor:pointer;
    display:inline-flex;align-items:center;gap:.3rem;
    border:1.5px solid var(--green);color:var(--green);background:var(--green-l);
    transition:all .15s;
}
.btn-completar-card:hover { background:var(--green);color:white; }

.empty-res { text-align:center;padding:4rem 1rem;color:var(--ink-4);grid-column:1/-1; }
.empty-res i { font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.4; }
.empty-res p { font-size:.88rem;margin-bottom:1rem; }

.pag-footer {
    padding:.875rem 1.25rem;border-top:1px solid var(--line);
    display:flex;justify-content:space-between;align-items:center;
    flex-wrap:wrap;gap:.5rem;background:var(--line-2);
}
.pag-info { font-size:.78rem;color:var(--ink-4); }

/* ── MODAL GENÉRICO ── */
.modal-overlay {
    display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
    z-index:9999;align-items:center;justify-content:center;
}
.modal-overlay.open { display:flex;animation:mFadeIn .15s ease; }
@keyframes mFadeIn { from{opacity:0}to{opacity:1} }
.modal-box {
    background:white;border-radius:16px;padding:1.5rem;
    width:100%;
    box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem;
    animation:mSlide .2s ease;
    max-height:90vh;overflow-y:auto;
}
@keyframes mSlide { from{transform:translateY(10px);opacity:0}to{transform:translateY(0);opacity:1} }
.modal-title { font-size:1rem;font-weight:700;color:var(--ink);margin-bottom:.25rem; }
.modal-resumen {
    background:var(--green-l);border:1.5px solid var(--green-m);
    border-radius:10px;padding:.75rem 1rem;margin:.75rem 0;font-size:.82rem;
}
.modal-resumen .mr-row {
    display:flex;justify-content:space-between;align-items:center;padding:.18rem 0;color:var(--ink-2);
}
.modal-resumen .mr-lbl { color:var(--ink-4);font-size:.75rem; }
.modal-resumen .mr-val { font-family:'DM Mono',monospace;font-weight:700;color:var(--green); }
.modal-footer { display:flex;gap:.6rem;justify-content:flex-end;margin-top:1rem;flex-wrap:wrap; }
.modal-btn-cancel {
    padding:8px 18px;border-radius:9px;font-size:.84rem;font-weight:600;
    background:var(--line-2);color:var(--ink-3);border:1.5px solid var(--line);
    cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s;
}
.modal-btn-cancel:hover { background:var(--line); }
.modal-btn-confirm {
    padding:8px 20px;border-radius:9px;font-size:.84rem;font-weight:700;
    background:var(--green);color:white;border:none;cursor:pointer;
    font-family:'DM Sans',sans-serif;transition:background .15s;
    display:flex;align-items:center;gap:.4rem;
}
.modal-btn-confirm:hover { background:#047857; }
.modal-btn-danger {
    padding:8px 20px;border-radius:9px;font-size:.84rem;font-weight:700;
    background:var(--red);color:white;border:none;cursor:pointer;
    font-family:'DM Sans',sans-serif;transition:background .15s;
    display:flex;align-items:center;gap:.4rem;
}
.modal-btn-danger:hover { background:#b91c1c; }
.modal-tab-wrap {
    display:flex;gap:.4rem;background:var(--line-2);border-radius:10px;
    padding:.3rem;margin-bottom:.875rem;
}
.modal-tab {
    flex:1;padding:.45rem .6rem;border-radius:8px;border:none;cursor:pointer;
    font-size:.78rem;font-weight:700;font-family:'DM Sans',sans-serif;
    background:none;color:var(--ink-4);transition:all .15s;
}
.modal-tab.act {
    background:white;color:var(--ink-2);
    box-shadow:0 1px 3px rgba(0,0,0,.1);
}
.modal-field-label {
    font-size:.67rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.06em;color:var(--ink-4);margin-bottom:.3rem;display:block;
}
.modal-select, .modal-input {
    width:100%;padding:.5rem .7rem;border:1.5px solid var(--line);
    border-radius:8px;font-size:.83rem;background:white;
    color:var(--ink-2);font-family:'DM Sans',sans-serif;box-sizing:border-box;
    outline:none;transition:border-color .15s;
}
.modal-select:focus, .modal-input:focus { border-color:var(--blue); }
.modal-input-s {
    display:flex;align-items:center;border:1.5px solid var(--line);
    border-radius:8px;overflow:hidden;background:white;
}
.modal-input-s span {
    padding:.5rem .65rem;background:var(--line-2);color:var(--ink-4);
    font-weight:700;font-size:.8rem;border-right:1px solid var(--line);
    white-space:nowrap;
}
.modal-input-s input {
    border:none;outline:none;padding:.5rem .7rem;font-size:.88rem;
    font-family:'DM Mono',monospace;font-weight:700;color:var(--ink);
    width:100%;background:white;
}
.modal-upload {
    display:flex;align-items:center;gap:.5rem;
    padding:.5rem .75rem;border:1.5px dashed var(--line);
    border-radius:8px;cursor:pointer;font-size:.78rem;
    color:var(--ink-4);background:var(--line-2);transition:all .15s;
}
.modal-upload:hover { border-color:var(--blue);color:var(--blue); }
.modal-error {
    display:none;margin-top:.6rem;padding:.5rem .75rem;
    background:var(--red-l);border:1px solid #fca5a5;border-radius:8px;
    font-size:.79rem;color:var(--red);font-weight:600;
}

/* ── Sección filtros modal Excel ── */
.filtro-grupo {
    background:var(--line-2);border:1px solid var(--line);border-radius:10px;
    padding:.875rem 1rem;margin-bottom:.75rem;
}
.filtro-grupo-title {
    font-size:.67rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.07em;color:var(--ink-4);margin-bottom:.6rem;
    display:flex;align-items:center;gap:.35rem;
}
.check-group { display:flex;flex-wrap:wrap;gap:.4rem; }
.check-pill input { display:none; }
.check-pill label {
    display:inline-flex;align-items:center;gap:.3rem;
    padding:5px 12px;border-radius:20px;font-size:.76rem;font-weight:600;
    border:1.5px solid var(--line);background:white;color:var(--ink-3);
    cursor:pointer;transition:all .15s;user-select:none;
}
.check-pill label::before { content:'';width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0; }
.check-pill input:checked + label {
    background:var(--blue-l);border-color:var(--blue);color:var(--blue);
}
.check-pill.pill-pagado   input:checked + label { background:var(--green-l);border-color:var(--green);color:var(--green); }
.check-pill.pill-cancelada input:checked + label { background:var(--red-l);border-color:var(--red);color:var(--red); }
.check-pill.pill-mitad    input:checked + label { background:var(--blue-l);border-color:var(--blue);color:var(--blue); }

/* ── Flatpickr override ── */
.flatpickr-calendar {
    border-radius:12px!important;box-shadow:0 8px 30px rgba(0,0,0,.12)!important;
    border:1px solid var(--line)!important;font-family:'DM Sans',sans-serif!important;font-size:.83rem!important;
}
.flatpickr-day.selected,.flatpickr-day.selected:hover { background:var(--blue)!important;border-color:var(--blue)!important; }
.flatpickr-day:hover { background:var(--blue-l)!important; }

/* ── RESPONSIVE ── */
@media(max-width:640px) {
    /* Top bar */
    .page-topbar { gap:.75rem; }
    .page-topbar > div:last-child { width:100%; }
    .page-topbar > div:last-child .btn-reportes,
    .page-topbar > div:last-child .btn-pdf-salud { flex:1;justify-content:center; }

    /* Filtros */
    .filtros-card { flex-direction:column;align-items:stretch;padding:.875rem 1rem; }
    .filtros-divider { display:none; }
    .f-ctrl { width:100%; }
    .date-wrap { flex:1; }
    .input-date { width:100% !important;box-sizing:border-box; }

    /* Botones estado */
    .estado-btns { gap:.3rem; }
    .btn-estado { font-size:.74rem;padding:6px 11px; }

    /* Grid tarjetas */
    .cards-grid { grid-template-columns:1fr;padding:1rem; }

    /* Modal panel pago — 1 col en móvil */
    #panel-pago > div { grid-template-columns:1fr !important; }

    /* Paginación */
    .pag-footer { flex-direction:column;align-items:flex-start; }
}

@media(max-width:480px) {
    .page-topbar .page-title { font-size:1.1rem; }
    .modal-box { padding:1.25rem;margin:.5rem; }
    .modal-tab { font-size:.72rem;padding:.4rem .4rem; }
}
</style>
@endpush

@section('contenido')

{{-- ── TOP BAR ── --}}
<div class="page-topbar">
    <div>
        <div class="page-title">
            <i class="bi bi-calendar-check-fill"></i> Reservas
        </div>
        <div class="page-subtitle">Gestiona y monitorea todas las reservas</div>
    </div>
    <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
        <button type="button" class="btn-reportes" onclick="abrirModalExcel()" title="Exportar reservas a Excel con filtros">
            <i class="bi bi-file-earmark-excel-fill"></i> Exportar Excel
        </button>
        <button type="button" class="btn-pdf-salud" onclick="abrirModalSalud()" title="Reporte de salud y documentos por día de tour">
            <i class="bi bi-file-earmark-medical-fill"></i> Reporte Salud PDF
        </button>
    </div>
</div>

{{-- ── FORMULARIO FILTROS ── --}}
<form method="GET" action="{{ route('reservas.index') }}" id="form-filtros">

    <div class="filtros-card">
        <div class="search-wrap" id="search-wrap">
            <i class="bi bi-search search-ico"></i>
            <input type="text" name="buscar" id="input-buscar"
                   value="{{ request('buscar') }}"
                   class="input-search"
                   placeholder="Buscar código, nombre, DNI, celular…"
                   autocomplete="off" spellcheck="false">
            <button type="button" class="search-clear" id="btn-search-clear" tabindex="-1">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <div class="filtros-divider"></div>

        <select name="canal" class="f-ctrl" style="min-width:145px;" onchange="submitForm()">
            <option value="">Todos los canales</option>
            <option value="whatsapp"       {{ request('canal')=='whatsapp'       ?'selected':'' }}>WhatsApp</option>
            <option value="presencial"     {{ request('canal')=='presencial'     ?'selected':'' }}>Presencial</option>
            <option value="llamada"        {{ request('canal')=='llamada'        ?'selected':'' }}>Llamada</option>
            <option value="redes_sociales" {{ request('canal')=='redes_sociales' ?'selected':'' }}>Redes Sociales</option>
            <option value="web"            {{ request('canal')=='web'            ?'selected':'' }}>Web</option>
            <option value="referido"       {{ request('canal')=='referido'       ?'selected':'' }}>Referido</option>
        </select>

        <div class="date-wrap">
            <i class="bi bi-calendar3"></i>
            <input type="text" name="fecha_desde" id="fecha-desde"
                   value="{{ request('fecha_desde') }}"
                   class="input-date {{ request('fecha_desde') ? 'active':'' }}"
                   placeholder="Desde" readonly>
        </div>
        <div class="date-wrap">
            <i class="bi bi-calendar3"></i>
            <input type="text" name="fecha_hasta" id="fecha-hasta"
                   value="{{ request('fecha_hasta') }}"
                   class="input-date {{ request('fecha_hasta') ? 'active':'' }}"
                   placeholder="Hasta" readonly>
        </div>

        @if(request()->hasAny(['buscar','estado','canal','fecha_desde','fecha_hasta']))
        <a href="{{ route('reservas.index') }}" class="btn-clear" title="Limpiar filtros">
            <i class="bi bi-x-lg"></i>
        </a>
        @endif
    </div>

    {{-- Botones estado --}}
    <div style="margin-bottom:1.1rem;">
        <div class="estado-btns">
            <span style="font-size:.78rem;color:var(--ink-4);font-weight:600;margin-right:.2rem;">Estado:</span>
            @php
                $estadoActual = request('estado');
                $idPagado     = $estados->firstWhere('nombre','pagado')?->id;
                $idCancelada  = $estados->firstWhere('nombre','cancelada')?->id;
                $idMitad      = $estados->firstWhere('nombre','mitad_pago')?->id;
                $idConfirmada = $estados->firstWhere('nombre','confirmada')?->id;
                $idPreReserva = $estados->firstWhere('nombre','pre_reserva')?->id;
            @endphp
            <button type="button"
                    class="btn-estado {{ $estadoActual == $idPagado    ? 'act-pagado'    : '' }}"
                    data-id="{{ $idPagado }}" data-cls="act-pagado"
                    onclick="toggleEstado(this)">Pagado</button>
            <button type="button"
                    class="btn-estado {{ $estadoActual == $idMitad     ? 'act-mitad'     : '' }}"
                    data-id="{{ $idMitad }}" data-cls="act-mitad"
                    onclick="toggleEstado(this)">50% Pagado</button>
            <button type="button"
                    class="btn-estado {{ $estadoActual == $idConfirmada ? 'act-pagado' : '' }}"
                    data-id="{{ $idConfirmada }}" data-cls="act-pagado"
                    onclick="toggleEstado(this)">Confirmada</button>
            <button type="button"
                    class="btn-estado {{ $estadoActual == $idCancelada ? 'act-cancelada' : '' }}"
                    data-id="{{ $idCancelada }}" data-cls="act-cancelada"
                    onclick="toggleEstado(this)">Cancelada</button>
            @if($idPreReserva)
            <button type="button"
                    class="btn-estado"
                    data-id="{{ $idPreReserva }}" data-cls="act-mitad"
                    onclick="toggleEstado(this)">Pre-reserva</button>
            @endif
        </div>
    </div>

    <input type="hidden" name="estado" id="input-estado" value="{{ request('estado') }}">
</form>

{{-- ── LISTA DE RESERVAS ── --}}
<div class="reservas-card">
    <div class="reservas-header">
        <div class="title">
            <i class="bi bi-grid-3x3-gap-fill" style="color:var(--blue)"></i>
            Lista de Reservas
            <span class="count" id="reservas-count">{{ $reservas->total() ?? $reservas->count() }}</span>
        </div>
    </div>

    <div id="ajax-results">
        <div class="cards-grid">
            @forelse($reservas as $reserva)
            @php
                $total      = (float)($reserva->precio_total ?? 0);
                $pagado     = (float)($reserva->pagos_sum_monto ?? $reserva->monto_pagado ?? 0);
                $saldo      = max(0, $total - $pagado);
                $pct        = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
                $barColor   = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#1d4ed8' : '#d97706');
                $estadoSlug = str_replace(' ','_', strtolower($reserva->estado->nombre ?? 'consulta'));
                $canales    = ['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2','referido'=>'bi-people'];
                $fechaTour  = $reserva->fecha_tour ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y') : ($reserva->fechaTour?->fecha ? \Carbon\Carbon::parse($reserva->fechaTour->fecha)->format('d/m/Y') : '—');
                $horaTour   = $reserva->hora_salida ? substr($reserva->hora_salida,0,5) : ($reserva->fechaTour?->hora_salida ? substr($reserva->fechaTour->hora_salida,0,5) : null);
                $nombreTour = $reserva->nombre_tour ?? $reserva->fechaTour?->tour?->nombre ?? '—';
                $estadoPagadoId  = $estados->firstWhere('nombre','pagado')?->id ?? null;
                $mostrarCompletar = $saldo > 0 && !in_array($estadoSlug,['pagado','cancelada','finalizada']) && $estadoPagadoId;
                $pagoUrl = route('reservas.registrarPago', $reserva);
            @endphp
            <div class="reserva-card" data-reserva-id="{{ $reserva->id }}">
                <div class="rc-head">
                    <a href="{{ route('reservas.show', $reserva) }}" class="codigo-reserva">{{ $reserva->codigo_reserva }}</a>
                    <span class="badge-estado est-{{ $estadoSlug }}">{{ ucfirst(str_replace('_',' ',$reserva->estado->nombre ?? '')) }}</span>
                </div>
                <div class="rc-cliente">
                    <span class="nombre">{{ $reserva->cliente->nombre_completo }}</span>
                    <span class="tel"><i class="bi bi-telephone-fill"></i> {{ $reserva->cliente->telefono ?? '—' }}@if($reserva->cliente->numero_documento) &nbsp;·&nbsp; <i class="bi bi-card-text" style="font-size:.65rem;"></i> {{ $reserva->cliente->numero_documento }}@endif</span>
                </div>
                <div class="rc-tour"><i class="bi bi-geo-alt-fill"></i> {{ $nombreTour }}@if($reserva->ciudad_destino)<span style="color:var(--ink-4);font-size:.75rem;font-weight:400;"> — {{ $reserva->ciudad_destino }}</span>@endif</div>
                <div class="rc-fecha-hora">
                    <i class="bi bi-calendar-event" style="font-size:.75rem;"></i>
                    <span class="fecha">{{ $fechaTour }}</span>
                    @if($horaTour)<span class="rc-dot"></span><i class="bi bi-clock" style="font-size:.7rem;"></i> {{ $horaTour }}@endif
                </div>
                <div class="rc-pago">
                    <div class="rc-pago-row">
                        <div class="pago-bar"><div class="pago-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div></div>
                        <span class="pago-pct" style="color:{{ $barColor }}">{{ $pct }}%</span>
                    </div>
                    <div class="pago-detalle">S/ {{ number_format($pagado,2) }} pagado &nbsp;/&nbsp; Total: S/ {{ number_format($total,2) }}@if($saldo > 0) &nbsp;·&nbsp; <span style="color:var(--red);font-weight:600;">Saldo: S/ {{ number_format($saldo,2) }}</span>@endif</div>
                </div>
                <div class="rc-footer">
                    <div class="rc-footer-left">
                        <span class="canal-badge"><i class="bi {{ $canales[$reserva->canal_contacto] ?? 'bi-chat' }}"></i> {{ ucfirst(str_replace('_',' ',$reserva->canal_contacto ?? '')) }}</span>
                    </div>
                    <div style="display:flex;gap:.4rem;align-items:center;">
                        <a href="{{ route('reservas.show', $reserva) }}" class="btn-tabla"><i class="bi bi-eye"></i> Ver</a>
                        @if($mostrarCompletar)
                        <button type="button" class="btn-completar-card"
                                onclick="abrirModalPago({{ $reserva->id }},'{{ $pagoUrl }}','{{ $reserva->codigo_reserva }}',{{ $total }},{{ $pagado }},{{ $saldo }})">
                            <i class="bi bi-check-circle"></i> Completar
                        </button>
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

        @if(method_exists($reservas,'hasPages') && $reservas->hasPages())
        <div class="pag-footer">
            <div class="pag-info">
                Mostrando {{ $reservas->firstItem() }}–{{ $reservas->lastItem() }} de {{ $reservas->total() }} reservas
            </div>
            {{ $reservas->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ══ MODAL — EXPORTAR EXCEL ══ --}}
<div class="modal-overlay" id="modal-excel" onclick="if(event.target===this)cerrarModalExcel()">
    <div class="modal-box" style="max-width:520px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="modal-title">
                <i class="bi bi-file-earmark-excel-fill" style="color:#059669;margin-right:.4rem;"></i>
                Exportar Reservas a Excel
            </div>
            <button type="button" onclick="cerrarModalExcel()"
                    style="background:none;border:none;cursor:pointer;color:var(--ink-4);font-size:1rem;padding:.2rem .4rem;border-radius:6px;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="filtro-grupo">
            <div class="filtro-grupo-title"><i class="bi bi-circle-fill" style="font-size:.5rem;"></i> Estado de la reserva</div>
            <div class="check-group">
                <div class="check-pill"><input type="checkbox" id="xls-est-todos" checked onchange="toggleTodos(this)"><label for="xls-est-todos">Todos</label></div>
                @foreach($estados as $est)
                @php $slug = str_replace(' ','_',strtolower($est->nombre)); @endphp
                <div class="check-pill pill-{{ in_array($slug,['pagado']) ? 'pagado' : (in_array($slug,['cancelada']) ? 'cancelada' : 'mitad') }}">
                    <input type="checkbox" id="xls-est-{{ $est->id }}" class="xls-estado" value="{{ $est->id }}" checked>
                    <label for="xls-est-{{ $est->id }}">{{ ucfirst(str_replace('_',' ',$est->nombre)) }}</label>
                </div>
                @endforeach
            </div>
        </div>
        <div class="filtro-grupo">
            <div class="filtro-grupo-title"><i class="bi bi-broadcast" style="font-size:.65rem;"></i> Canal de contacto</div>
            <div class="check-group">
                <div class="check-pill"><input type="checkbox" id="xls-canal-todos" checked onchange="toggleTodosCanal(this)"><label for="xls-canal-todos">Todos</label></div>
                @foreach(['whatsapp'=>'WhatsApp','presencial'=>'Presencial','llamada'=>'Llamada','redes_sociales'=>'Redes Sociales','web'=>'Web','referido'=>'Referido'] as $val => $lbl)
                <div class="check-pill">
                    <input type="checkbox" id="xls-canal-{{ $val }}" class="xls-canal" value="{{ $val }}" checked>
                    <label for="xls-canal-{{ $val }}">{{ $lbl }}</label>
                </div>
                @endforeach
            </div>
        </div>
        <div class="filtro-grupo">
            <div class="filtro-grupo-title"><i class="bi bi-calendar3-range" style="font-size:.65rem;"></i> Rango de fechas de registro</div>
            <div style="display:flex;gap:.65rem;align-items:center;flex-wrap:wrap;">
                <div class="date-wrap" style="flex:1;min-width:120px;">
                    <i class="bi bi-calendar3"></i>
                    <input type="text" id="xls-desde" placeholder="Desde"
                           class="input-date" style="width:100%;box-sizing:border-box;" readonly>
                </div>
                <span style="color:var(--ink-4);font-size:.8rem;">—</span>
                <div class="date-wrap" style="flex:1;min-width:120px;">
                    <i class="bi bi-calendar3"></i>
                    <input type="text" id="xls-hasta" placeholder="Hasta"
                           class="input-date" style="width:100%;box-sizing:border-box;" readonly>
                </div>
                <button type="button" onclick="limpiarFechasXls()"
                        style="background:none;border:1.5px solid var(--line);border-radius:8px;padding:7px 10px;cursor:pointer;color:var(--ink-4);font-size:.75rem;white-space:nowrap;">
                    <i class="bi bi-x"></i> Limpiar
                </button>
            </div>
        </div>
        <div style="margin-bottom:.75rem;">
            <label class="modal-field-label">Buscar (código, nombre, DNI)</label>
            <div class="search-wrap" style="min-width:unset;">
                <i class="bi bi-search search-ico"></i>
                <input type="text" id="xls-buscar" class="input-search" placeholder="Opcional…" style="padding-left:34px;">
            </div>
        </div>
        <div id="xls-preview" style="font-size:.75rem;color:var(--ink-4);margin-bottom:.75rem;padding:.5rem .7rem;background:var(--line-2);border-radius:8px;">
            <i class="bi bi-info-circle"></i> Se exportarán todas las reservas con los filtros seleccionados.
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" onclick="cerrarModalExcel()">Cancelar</button>
            <button type="button" class="modal-btn-confirm" onclick="descargarExcel()">
                <i class="bi bi-download"></i> Descargar Excel
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL — REPORTE PDF SALUD ══ --}}
<div class="modal-overlay" id="modal-salud" onclick="if(event.target===this)cerrarModalSalud()">
    <div class="modal-box" style="max-width:480px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="modal-title">
                <i class="bi bi-file-earmark-medical-fill" style="color:var(--red);margin-right:.4rem;"></i>
                Reporte de Salud y Documentos
            </div>
            <button type="button" onclick="cerrarModalSalud()"
                    style="background:none;border:none;cursor:pointer;color:var(--ink-4);font-size:1rem;padding:.2rem .4rem;border-radius:6px;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <p style="font-size:.82rem;color:var(--ink-3);margin-bottom:1rem;line-height:1.55;">
            Genera un PDF con el listado de pasajeros por reserva para un día de tour específico.
        </p>
        <div style="margin-bottom:.875rem;">
            <label class="modal-field-label" for="salud-fecha">
                Fecha del tour <span style="color:var(--red);">*</span>
            </label>
            <div class="date-wrap" style="width:100%;">
                <i class="bi bi-calendar3"></i>
                <input type="text" id="salud-fecha" placeholder="Seleccionar fecha del tour"
                       class="input-date" style="width:100%;box-sizing:border-box;" readonly>
            </div>
        </div>
        <div style="margin-bottom:.875rem;">
            <label class="modal-field-label" for="salud-tour">
                Filtrar por nombre de tour <span style="font-weight:400;opacity:.6;">(opcional)</span>
            </label>
            <input type="text" id="salud-tour" class="modal-input"
                   placeholder="Ej: Ruta Inca, Tour Amazonas…">
        </div>
        <div style="margin-bottom:1rem;display:flex;align-items:center;gap:.6rem;">
            <input type="checkbox" id="salud-solo-alertas" style="width:16px;height:16px;cursor:pointer;">
            <label for="salud-solo-alertas" style="font-size:.83rem;color:var(--ink-2);cursor:pointer;font-weight:500;">
                Incluir solo pasajeros con alertas médicas
            </label>
        </div>
        <div id="salud-error" class="modal-error"></div>
        <div style="background:var(--red-l);border:1px solid #fca5a5;border-radius:8px;padding:.65rem .875rem;font-size:.76rem;color:#991b1b;margin-bottom:1rem;display:flex;gap:.4rem;">
            <i class="bi bi-shield-lock-fill" style="flex-shrink:0;margin-top:1px;"></i>
            <span>Este reporte contiene información médica sensible. Úsalo solo para operaciones del tour.</span>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" onclick="cerrarModalSalud()">Cancelar</button>
            <button type="button" class="modal-btn-danger" onclick="descargarPdfSalud()" id="salud-btn">
                <i class="bi bi-file-earmark-pdf-fill"></i> Generar PDF
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL COMPLETAR PAGO ══ --}}
<div class="modal-overlay" id="modal-pago" onclick="if(event.target===this)cerrarModal()">
    <div class="modal-box" style="max-width:460px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem;">
            <div class="modal-title">
                <i class="bi bi-check-circle-fill" style="color:var(--green);margin-right:.35rem;"></i>
                Completar pago
            </div>
            <button type="button" onclick="cerrarModal()"
                    style="background:none;border:none;cursor:pointer;color:var(--ink-4);font-size:1rem;padding:.2rem .4rem;border-radius:6px;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="modal-resumen">
            <div class="mr-row"><span class="mr-lbl">Total reserva</span><span class="mr-val" id="m-total">—</span></div>
            <div class="mr-row"><span class="mr-lbl">Ya pagado</span><span class="mr-val" id="m-pagado">—</span></div>
            <div class="mr-row" style="border-top:1px solid var(--green-m);padding-top:.35rem;margin-top:.2rem;">
                <span class="mr-lbl" style="font-weight:700;color:var(--ink-2);">Saldo pendiente</span>
                <span id="m-saldo" style="font-family:'DM Mono',monospace;font-weight:800;color:var(--red);font-size:1rem;">—</span>
            </div>
        </div>
        <div class="modal-tab-wrap">
            <button type="button" class="modal-tab act" id="tab-solo" onclick="switchTab('solo')">
                <i class="bi bi-check-circle me-1"></i> Solo marcar pagado
            </button>
            <button type="button" class="modal-tab" id="tab-pago" onclick="switchTab('pago')">
                <i class="bi bi-plus-circle me-1"></i> Registrar nuevo pago
            </button>
        </div>
        <div id="panel-solo">
            <div style="background:var(--green-l);border:1.5px solid var(--green-m);border-radius:10px;padding:.75rem 1rem;font-size:.82rem;color:#065f46;line-height:1.5;">
                <i class="bi bi-info-circle-fill me-1"></i>
                Se marcará la reserva <strong id="m-codigo">—</strong> como <strong>Pagada al 100%</strong> sin registrar un nuevo pago.
            </div>
        </div>
        <div id="panel-pago" style="display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.65rem;">
                <div>
                    <div class="modal-field-label">Método <span style="color:var(--red);">*</span></div>
                    <select id="m-metodo" class="modal-select">
                        <option value="">— Seleccionar —</option>
                        <optgroup label="Efectivo"><option value="efectivo">Efectivo</option></optgroup>
                        <optgroup label="Pagos digitales">
                            <option value="yape">Yape</option><option value="plin">Plin</option><option value="tunki">Tunki</option>
                        </optgroup>
                        <optgroup label="Transferencia">
                            <option value="transf_bcp">Transf. BCP</option><option value="transf_bbva">Transf. BBVA</option>
                            <option value="transf_inter">Transf. Interbank</option><option value="transf_sc">Transf. Scotiabank</option>
                            <option value="transf_bn">Transf. Banco Nación</option><option value="transf_otros">Otro banco</option>
                        </optgroup>
                        <optgroup label="Depósito">
                            <option value="dep_bcp">Depósito BCP</option><option value="dep_bbva">Depósito BBVA</option>
                            <option value="dep_inter">Depósito Interbank</option><option value="dep_otros">Depósito otro banco</option>
                        </optgroup>
                        <optgroup label="Tarjeta">
                            <option value="tarjeta_credito">Tarjeta crédito</option><option value="tarjeta_debito">Tarjeta débito</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <div class="modal-field-label">Monto (S/) <span style="color:var(--red);">*</span></div>
                    <div class="modal-input-s"><span>S/</span><input type="number" id="m-monto" step="0.01" min="0.01" placeholder="0.00"></div>
                    <div style="font-size:.67rem;color:var(--ink-4);margin-top:.2rem;">Saldo: <strong id="m-saldo-hint" style="color:var(--red);font-family:'DM Mono',monospace;">—</strong></div>
                </div>
                <div>
                    <div class="modal-field-label">N° Operación <span style="opacity:.5;font-weight:400;">(opcional)</span></div>
                    <input type="text" id="m-operacion" class="modal-input" placeholder="Código..." maxlength="100">
                </div>
                <div>
                    <div class="modal-field-label">Voucher <span style="opacity:.5;font-weight:400;">(opcional)</span></div>
                    <label class="modal-upload">
                        <i class="bi bi-cloud-upload"></i>
                        <span id="m-upload-txt">Seleccionar...</span>
                        <input type="file" id="m-baucher" accept=".jpg,.jpeg,.png,.pdf,.webp" style="display:none;" onchange="onModalFile(this)">
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-error" id="m-error"></div>
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" id="m-btn-cancel" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="modal-btn-confirm" id="m-btn-confirm" onclick="ejecutarPago()">
                <span id="m-btn-txt"><i class="bi bi-check-circle-fill"></i> Confirmar</span>
                <span id="m-btn-load" style="display:none;"><i class="bi bi-hourglass-split"></i> Guardando...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
(function () {

const fpCfg = {
    locale: 'es',
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
    allowInput: false,
    disableMobile: true,
    onClose(selectedDates, dateStr, instance) {
        instance.input.value = dateStr;
        if (dateStr) instance.input.classList.add('active');
        else instance.input.classList.remove('active');
        submitForm();
    },
    onReady(selectedDates, dateStr, instance) {
        if (instance.input.value) instance.input.classList.add('active');
    }
};

flatpickr('#fecha-desde', fpCfg);
flatpickr('#fecha-hasta', fpCfg);

const fpXlsCfg = { locale:'es', dateFormat:'Y-m-d', altInput:true, altFormat:'d/m/Y', allowInput:false, disableMobile:true };
flatpickr('#xls-desde', fpXlsCfg);
flatpickr('#xls-hasta', fpXlsCfg);

flatpickr('#salud-fecha', { locale:'es', dateFormat:'Y-m-d', altInput:true, altFormat:'d M Y', allowInput:false, disableMobile:true });

window.submitForm = function () {
    document.getElementById('form-filtros').submit();
};

window.toggleEstado = function (btn) {
    const cls      = btn.dataset.cls;
    const id       = btn.dataset.id;
    const hidInput = document.getElementById('input-estado');
    const allCls   = ['act-pagado','act-cancelada','act-mitad'];
    const isActive = btn.classList.contains(cls);
    document.querySelectorAll('.btn-estado').forEach(b => b.classList.remove(...allCls));
    if (isActive) { hidInput.value = ''; }
    else { btn.classList.add(cls); hidInput.value = id; }
    submitForm();
};

const inputBuscar = document.getElementById('input-buscar');
const btnClear    = document.getElementById('btn-search-clear');
const searchWrap  = document.getElementById('search-wrap');

function updateClearBtn() {
    searchWrap.classList.toggle('has-value', inputBuscar.value.length > 0);
}
updateClearBtn();

if (btnClear) {
    btnClear.addEventListener('click', function () {
        inputBuscar.value = '';
        updateClearBtn();
        inputBuscar.focus();
        doSearch('');
    });
}

let searchTimer = null;
let searchSeq   = 0;

inputBuscar.addEventListener('input', function () {
    updateClearBtn();
    clearTimeout(searchTimer);
    const query = this.value;
    searchTimer = setTimeout(() => doSearch(query), 380);
});

inputBuscar.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        clearTimeout(searchTimer);
        doSearch(this.value);
    }
});

function doSearch(query) {
    const seq = ++searchSeq;
    const url = new URL(window.location.href);
    url.searchParams.set('buscar', query);
    const selStart = inputBuscar.selectionStart;
    const selEnd   = inputBuscar.selectionEnd;

    fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
    })
    .then(r => r.text())
    .then(html => {
        if (seq !== searchSeq) return;
        const doc      = new DOMParser().parseFromString(html, 'text/html');
        const newRes   = doc.getElementById('ajax-results');
        const newCount = doc.getElementById('reservas-count');
        const curRes   = document.getElementById('ajax-results');
        const curCount = document.getElementById('reservas-count');
        if (newRes  && curRes)   curRes.replaceWith(newRes);
        if (newCount && curCount) curCount.textContent = newCount.textContent;
        window.history.replaceState({}, '', url.toString());
        requestAnimationFrame(() => {
            inputBuscar.focus();
            try { inputBuscar.setSelectionRange(selStart, selEnd); } catch(e){}
        });
    })
    .catch(() => {});
}

window.abrirModalExcel = function () {
    const urlParams = new URLSearchParams(window.location.search);
    document.getElementById('xls-buscar').value = urlParams.get('buscar') || '';
    const fpDesde = document.getElementById('xls-desde')._flatpickr;
    const fpHasta = document.getElementById('xls-hasta')._flatpickr;
    if (fpDesde && urlParams.get('fecha_desde')) fpDesde.setDate(urlParams.get('fecha_desde'));
    if (fpHasta && urlParams.get('fecha_hasta')) fpHasta.setDate(urlParams.get('fecha_hasta'));
    document.getElementById('modal-excel').classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.cerrarModalExcel = function () {
    document.getElementById('modal-excel').classList.remove('open');
    document.body.style.overflow = '';
};
window.toggleTodos = function (cb) {
    document.querySelectorAll('.xls-estado').forEach(c => c.checked = cb.checked);
};
window.toggleTodosCanal = function (cb) {
    document.querySelectorAll('.xls-canal').forEach(c => c.checked = cb.checked);
};
window.limpiarFechasXls = function () {
    const fpDesde = document.getElementById('xls-desde')._flatpickr;
    const fpHasta = document.getElementById('xls-hasta')._flatpickr;
    if (fpDesde) fpDesde.clear();
    if (fpHasta) fpHasta.clear();
};
window.descargarExcel = function () {
    const params = new URLSearchParams();
    const estados = [...document.querySelectorAll('.xls-estado:checked')].map(c => c.value);
    const todosEstados = document.querySelectorAll('.xls-estado').length;
    if (estados.length > 0 && estados.length < todosEstados) params.set('estados', estados.join(','));
    const canales = [...document.querySelectorAll('.xls-canal:checked')].map(c => c.value);
    const todosCanales = document.querySelectorAll('.xls-canal').length;
    if (canales.length > 0 && canales.length < todosCanales) params.set('canales', canales.join(','));
    const desde = document.getElementById('xls-desde').value;
    const hasta = document.getElementById('xls-hasta').value;
    if (desde) params.set('fecha_desde', desde);
    if (hasta) params.set('fecha_hasta', hasta);
    const buscar = document.getElementById('xls-buscar').value.trim();
    if (buscar) params.set('buscar', buscar);
    window.location.href = "{{ route('reservas.exportar') }}" + '?' + params.toString();
    cerrarModalExcel();
};

window.abrirModalSalud = function () {
    document.getElementById('salud-error').style.display = 'none';
    document.getElementById('modal-salud').classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.cerrarModalSalud = function () {
    document.getElementById('modal-salud').classList.remove('open');
    document.body.style.overflow = '';
};
window.descargarPdfSalud = function () {
    const fecha = document.getElementById('salud-fecha').value;
    const errEl = document.getElementById('salud-error');
    if (!fecha) {
        errEl.textContent = 'Selecciona una fecha de tour para generar el reporte.';
        errEl.style.display = 'block';
        return;
    }
    errEl.style.display = 'none';
    const params = new URLSearchParams();
    params.set('fecha', fecha);
    const tour = document.getElementById('salud-tour').value.trim();
    if (tour) params.set('tour', tour);
    if (document.getElementById('salud-solo-alertas').checked) params.set('solo_alertas', '1');
    window.open("{{ route('reservas.reporteSalud') }}" + '?' + params.toString(), '_blank');
    cerrarModalSalud();
};

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { cerrarModal(); cerrarModalExcel(); cerrarModalSalud(); }
});

let modalReservaId = null;
let modalPagoUrl   = null;
let modalTab       = 'solo';
let modalCsrf      = "{{ csrf_token() }}";

window.abrirModalPago = function (reservaId, pagoUrl, codigo, total, pagado, saldo) {
    modalReservaId = reservaId;
    modalPagoUrl   = pagoUrl;
    document.getElementById('m-codigo').textContent      = codigo;
    document.getElementById('m-total').textContent       = 'S/ ' + fmtNum(total);
    document.getElementById('m-pagado').textContent      = 'S/ ' + fmtNum(pagado);
    document.getElementById('m-saldo').textContent       = 'S/ ' + fmtNum(saldo);
    document.getElementById('m-saldo-hint').textContent  = 'S/ ' + fmtNum(saldo);
    const inputMonto = document.getElementById('m-monto');
    if (inputMonto) inputMonto.value = parseFloat(saldo).toFixed(2);
    switchTab('solo');
    ocultarError();
    resetLoading();
    document.getElementById('m-metodo').value    = '';
    document.getElementById('m-operacion').value = '';
    document.getElementById('m-baucher').value   = '';
    document.getElementById('m-upload-txt').textContent = 'Seleccionar...';
    document.getElementById('modal-pago').classList.add('open');
    document.body.style.overflow = 'hidden';
};
window.cerrarModal = function () {
    document.getElementById('modal-pago').classList.remove('open');
    document.body.style.overflow = '';
};
window.switchTab = function (tab) {
    modalTab = tab;
    document.getElementById('tab-solo').classList.toggle('act', tab === 'solo');
    document.getElementById('tab-pago').classList.toggle('act', tab === 'pago');
    document.getElementById('panel-solo').style.display = tab === 'solo' ? 'block' : 'none';
    document.getElementById('panel-pago').style.display = tab === 'pago' ? 'block' : 'none';
    ocultarError();
};
window.onModalFile = function (input) {
    const txt = document.getElementById('m-upload-txt');
    if (input.files.length && input.files[0].size > 5*1024*1024) {
        mostrarError('El archivo supera los 5 MB.');
        input.value = '';
        txt.textContent = 'Seleccionar...';
        return;
    }
    txt.textContent = input.files.length ? input.files[0].name : 'Seleccionar...';
};
window.ejecutarPago = async function () {
    ocultarError();
    if (modalTab === 'pago') {
        const metodo = document.getElementById('m-metodo').value;
        const monto  = parseFloat(document.getElementById('m-monto').value || 0);
        if (!metodo) { mostrarError('Selecciona un método de pago.'); return; }
        if (!monto || monto <= 0) { mostrarError('Ingresa un monto válido mayor a 0.'); return; }
    }
    setLoading(true);
    try {
        const fd = new FormData();
        fd.append('_token', modalCsrf);
        fd.append('solo_estado', modalTab === 'solo' ? '1' : '0');
        if (modalTab === 'pago') {
            fd.append('metodo_pago',      document.getElementById('m-metodo').value);
            fd.append('monto',            document.getElementById('m-monto').value);
            fd.append('numero_operacion', document.getElementById('m-operacion').value);
            const baucherFile = document.getElementById('m-baucher').files[0];
            if (baucherFile) fd.append('archivo_baucher', baucherFile);
        }
        const resp = await fetch(modalPagoUrl, { method:'POST', body:fd });
        const data = await resp.json();
        if (!data.ok) { mostrarError(data.message || 'Error al guardar. Intenta de nuevo.'); setLoading(false); return; }
        animarTarjeta(modalReservaId);
        cerrarModal();
        mostrarToast('✓ Pago completado correctamente.');
        setTimeout(() => window.location.reload(), 1500);
    } catch (e) {
        mostrarError('Error de conexión. Verifica tu internet.');
        setLoading(false);
    }
};

function animarTarjeta(reservaId) {
    const card = document.querySelector(`[data-reserva-id="${reservaId}"]`);
    if (!card) return;
    const bar = card.querySelector('.pago-bar-fill');
    const pct = card.querySelector('.pago-pct');
    if (bar) { bar.style.width = '100%'; bar.style.background = '#059669'; }
    if (pct) { pct.textContent = '100%'; pct.style.color = '#059669'; }
}
function mostrarToast(msg) {
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;top:1rem;left:50%;transform:translateX(-50%);
        background:#059669;color:white;padding:.7rem 1.4rem;border-radius:12px;
        font-size:.86rem;font-weight:700;z-index:99999;
        box-shadow:0 4px 20px rgba(5,150,105,.4);
        animation:mFadeIn .25s ease;white-space:nowrap;`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .3s'; }, 1200);
    setTimeout(() => t.remove(), 1500);
}
function fmtNum(n) { return parseFloat(n).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); }
function mostrarError(msg) { const el = document.getElementById('m-error'); if(el){el.textContent=msg;el.style.display='block';} }
function ocultarError()    { const el = document.getElementById('m-error'); if(el) el.style.display='none'; }
function setLoading(on) {
    document.getElementById('m-btn-txt').style.display  = on ? 'none'  : 'flex';
    document.getElementById('m-btn-load').style.display = on ? 'flex' : 'none';
    document.getElementById('m-btn-confirm').disabled = on;
    document.getElementById('m-btn-cancel').disabled  = on;
}
function resetLoading() { setLoading(false); }

})();
</script>
@endpush

@endsection