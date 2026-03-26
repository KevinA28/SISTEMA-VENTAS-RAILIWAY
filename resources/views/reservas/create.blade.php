{{-- =====================================================================
     ARCHIVO: create.blade.php
     UBICACIÓN: resources/views/reservas/create.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Nueva Reserva')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --bg:       #f4f5f7;
    --surface:  #ffffff;
    --border:   #e2e5ea;
    --border-h: #b8bec8;
    --ink:      #0f1923;
    --ink-2:    #2d3748;
    --ink-3:    #6b7280;
    --ink-4:    #9ca3af;
    --blue:     #2563eb;
    --blue-d:   #1d4ed8;
    --blue-l:   #eff6ff;
    --blue-m:   #dbeafe;
    --green:    #059669;
    --green-l:  #ecfdf5;
    --green-m:  #a7f3d0;
    --amber:    #d97706;
    --amber-l:  #fffbeb;
    --red:      #dc2626;
    --red-l:    #fef2f2;
    --r:        8px;
    --r-lg:     12px;
    --sh:       0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
}
*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--ink); -webkit-font-smoothing: antialiased; }

.pw { max-width: 960px; margin: 0 auto; padding: 2rem 1.25rem 4rem; }

/* ── HEADER ── */
.ph {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    box-shadow: var(--sh);
    flex-wrap: wrap;
}
.ph-eyebrow {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--blue);
    margin-bottom: .4rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.ph h1 {
    font-family: 'Instrument Serif', serif;
    font-size: 2rem;
    font-weight: 400;
    color: var(--ink);
    line-height: 1.15;
    letter-spacing: -.01em;
}
.ph p { font-size: .82rem; color: var(--ink-4); margin-top: .4rem; line-height: 1.5; }
.btn-back {
    display: inline-flex; align-items: center; gap: .4rem;
    color: var(--ink-3); font-size: .82rem; font-weight: 500;
    text-decoration: none; padding: 8px 16px;
    border: 1.5px solid var(--border); border-radius: var(--r);
    transition: all .15s; white-space: nowrap; margin-top: .2rem;
}
.btn-back:hover { color: var(--blue); border-color: var(--blue); background: var(--blue-l); }

/* ── BLOQUES ── */
.fb { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); margin-bottom: 1.25rem; overflow: hidden; box-shadow: var(--sh); }
.fb-head { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); background: #fafbfc; display: flex; align-items: center; gap: .75rem; }
.fb-head .ico { width: 32px; height: 32px; border-radius: 8px; background: var(--blue-m); color: var(--blue); display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }
.fb-head h3 { font-size: .9rem; font-weight: 700; color: var(--ink); letter-spacing: -.01em; }
.fb-head p  { font-size: .72rem; color: var(--ink-4); margin-top: 2px; }
.fb-body { padding: 1.5rem; }

/* ── SUBTÍTULO ── */
.st {
    font-size: .65rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--ink-4);
    padding-bottom: .55rem; border-bottom: 1px solid var(--border);
    margin: 1.4rem 0 1rem;
}
.st:first-child { margin-top: 0; }

/* ── FIELD ── */
.field { margin-bottom: 1rem; }
.field:last-child { margin-bottom: 0; }
.lbl {
    display: block; font-size: .7rem; font-weight: 700;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--ink-3); margin-bottom: .4rem; line-height: 1;
}
.lbl .req { color: var(--red); margin-left: 2px; }
.lbl .opt { color: var(--ink-4); font-weight: 500; text-transform: none; letter-spacing: 0; font-size: .68rem; margin-left: 4px; }
.fi {
    width: 100%; padding: 9px 12px;
    border: 1.5px solid var(--border); border-radius: var(--r);
    font-size: .875rem; font-family: 'DM Sans', sans-serif;
    color: var(--ink); background: var(--surface);
    outline: none; transition: border-color .15s, box-shadow .15s;
    -webkit-appearance: none; appearance: none; line-height: 1.4;
}
.fi:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.fi::placeholder { color: var(--ink-4); }
.fi.err { border-color: var(--red); }
textarea.fi { resize: vertical; min-height: 76px; line-height: 1.5; }
select.fi { cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; padding-right: 30px; }
.ferr  { font-size: .71rem; color: var(--red); margin-top: .3rem; font-weight: 600; }
.fhint { font-size: .71rem; color: var(--ink-4); margin-top: .3rem; line-height: 1.45; }

/* ── INPUT GROUP ── */
.ig { display: flex; align-items: stretch; border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; transition: border-color .15s, box-shadow .15s; }
.ig:focus-within { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.ig .ia { padding: 0 11px; background: #fafbfc; color: var(--ink-3); font-size: .8rem; font-weight: 600; display: flex; align-items: center; gap: .3rem; border-right: 1.5px solid var(--border); white-space: nowrap; flex-shrink: 0; }
.ig .ia-r { border-right: none; border-left: 1.5px solid var(--border); cursor: pointer; background: var(--blue); color: #fff; transition: background .15s; padding: 0 14px; font-size: .8rem; font-weight: 600; display: flex; align-items: center; gap: .3rem; }
.ig .ia-r:hover { background: var(--blue-d); }
.ig .fi { border: none !important; box-shadow: none !important; border-radius: 0 !important; flex: 1; min-width: 0; }

/* ── HORA CON AM/PM ── */
.hora-wrap { display: flex; align-items: stretch; border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; transition: border-color .15s, box-shadow .15s; }
.hora-wrap:focus-within { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.hora-wrap .ia { padding: 0 11px; background: #fafbfc; color: var(--ink-3); font-size: .8rem; font-weight: 600; display: flex; align-items: center; gap: .3rem; border-right: 1.5px solid var(--border); white-space: nowrap; flex-shrink: 0; }
.hora-wrap .fi-hora { flex: 1; border: none !important; box-shadow: none !important; border-radius: 0 !important; min-width: 0; }
.hora-wrap .ampm-sel { border: none; border-left: 1.5px solid var(--border); background: #fafbfc; color: var(--ink-2); font-size: .82rem; font-weight: 700; font-family: 'DM Sans', sans-serif; padding: 0 10px; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; min-width: 56px; }
.hora-wrap .ampm-sel:focus { color: var(--blue); }

/* ── GRILLAS ── */
.g2  { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.g3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
.g12 { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; }
.g13 { display: grid; grid-template-columns: 1fr 3fr; gap: 1rem; }
@media(max-width:680px){ .g2,.g3,.g12,.g13 { grid-template-columns:1fr; } }

/* ── PANEL PRECIOS ── */
.pp { background: linear-gradient(135deg,#0f172a 0%,#1e3a5f 100%); border-radius: var(--r); padding: 1.25rem 1.5rem; display: grid; grid-template-columns: repeat(3,1fr); gap: .75rem; }
.pp-item .pp-l { font-size: .62rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.4); margin-bottom: .3rem; }
.pp-item .pp-v { font-family: 'DM Mono', monospace; font-size: 1.25rem; font-weight: 500; color: #fff; }
.pp-item .pp-v.a { color: #fbbf24; }
.pp-item .pp-v.r { color: #f87171; }
@media(max-width:680px){ .pp { grid-template-columns: 1fr; gap:.5rem; } }

/* ── ESTADOS ── */
.eg { display: grid; grid-template-columns: repeat(3,1fr); gap: .6rem; margin-top: .5rem; }
.eo {
    border: 2px solid var(--border); border-radius: var(--r);
    padding: .9rem .6rem; text-align: center; cursor: pointer;
    font-size: .75rem; font-weight: 600; color: var(--ink-3);
    transition: all .18s; user-select: none; line-height: 1.4;
    background: var(--surface); position: relative;
}
.eo input { display: none; }
.eo .eo-icon { font-size: 1.2rem; display: block; margin-bottom: .35rem; }
.eo:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.08); }
.eo.e-pagado.sel  { border-color: #059669; background: #ecfdf5; color: #065f46; box-shadow: 0 0 0 4px rgba(5,150,105,.18), 0 4px 16px rgba(5,150,105,.15); transform: translateY(-2px); }
.eo.e-mitad.sel   { border-color: #2563eb; background: #eff6ff; color: #1e40af; box-shadow: 0 0 0 4px rgba(37,99,235,.18), 0 4px 16px rgba(37,99,235,.15); transform: translateY(-2px); }
.eo.e-cancel.sel  { border-color: #dc2626; background: #fef2f2; color: #991b1b; box-shadow: 0 0 0 4px rgba(220,38,38,.18), 0 4px 16px rgba(220,38,38,.15); transform: translateY(-2px); }
.eo-check { position: absolute; top: 6px; right: 8px; width: 16px; height: 16px; border-radius: 50%; opacity: 0; transition: opacity .18s; display: flex; align-items: center; justify-content: center; font-size: .65rem; }
.eo.e-pagado.sel .eo-check { opacity: 1; background: #059669; color: white; }
.eo.e-mitad.sel  .eo-check { opacity: 1; background: #2563eb; color: white; }
.eo.e-cancel.sel .eo-check { opacity: 1; background: #dc2626; color: white; }

/* ── EMAIL WIDGET ── */
.email-wrap { position: relative; }
.email-row { display: flex; align-items: stretch; border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; transition: border-color .15s, box-shadow .15s; }
.email-row:focus-within { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
.email-row .ea { padding: 0 10px; background: #fafbfc; color: var(--ink-3); font-size: .8rem; font-weight: 600; display: flex; align-items: center; gap: .3rem; border-right: 1.5px solid var(--border); white-space: nowrap; flex-shrink: 0; }
.email-row .email-user   { flex: 0 0 auto; min-width: 80px; max-width: 150px; border: none !important; box-shadow: none !important; border-radius: 0 !important; padding-right: 2px; }
.email-row .at-sign      { padding: 0 5px; display: flex; align-items: center; color: var(--ink-4); font-size: .9rem; font-weight: 700; flex-shrink: 0; user-select: none; }
.email-row .email-domain { flex: 1; border: none !important; box-shadow: none !important; border-radius: 0 !important; padding-left: 0; min-width: 0; -webkit-appearance: none; appearance: none; }
.domain-list { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: white; border: 1.5px solid var(--border); border-radius: var(--r); box-shadow: 0 8px 24px rgba(0,0,0,.1); z-index: 200; overflow: hidden; }
.domain-list.open { display: block; }
.domain-list li { padding: 9px 14px; font-size: .84rem; color: var(--ink-2); cursor: pointer; display: flex; align-items: center; gap: .5rem; transition: background .1s; }
.domain-list li:hover { background: var(--blue-l); color: var(--blue); }

/* ── BÚSQUEDA CLIENTE ── */
.cr { display: none; align-items: center; gap: .55rem; padding: .65rem 1rem; border-radius: var(--r); font-size: .81rem; font-weight: 500; margin-top: .75rem; }
.cr.v  { display: flex; }
.cr.ok { background: var(--green-l); color: #065f46; border: 1.5px solid var(--green-m); }
.cr.wa { background: var(--amber-l); color: #92400e; border: 1.5px solid #fcd34d; }
.cr.er { background: var(--red-l);   color: #991b1b; border: 1.5px solid #fca5a5; }
.cr.ld { background: var(--bg);      color: var(--ink-3); border: 1.5px solid var(--border); }

/* ── PASAJEROS ── */
.pax-card { background: var(--bg); border: 1.5px solid var(--border); border-radius: var(--r); padding: 1rem; margin-bottom: .75rem; position: relative; animation: fu .2s ease; }
.pax-head { font-size: .69rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--ink-3); display: flex; justify-content: space-between; align-items: center; margin-bottom: .9rem; }
.pax-del { width: 26px; height: 26px; border: 1.5px solid var(--border); border-radius: 6px; background: white; color: var(--ink-3); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: .78rem; transition: all .15s; flex-shrink: 0; }
.pax-del:hover { border-color: var(--red); color: var(--red); background: var(--red-l); }
.btn-add { width: 100%; padding: 9px; border: 2px dashed var(--border); border-radius: var(--r); background: transparent; color: var(--ink-3); font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .4rem; transition: all .15s; margin-top: .5rem; }
.btn-add:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }

/* ── SALUD POR PASAJERO ── */
.salud-pasajero { background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; margin-bottom: .75rem; }
.salud-pasajero-head { padding: .65rem 1rem; background: #fafbfc; border-bottom: 1px solid var(--border); font-size: .73rem; font-weight: 700; color: var(--ink-2); display: flex; align-items: center; gap: .4rem; }
.salud-pasajero-body { padding: .9rem 1rem; }

/* ── SALUD GRID ALINEADO ── */
.salud-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    align-items: start;
}
.salud-alerg-col { display: flex; flex-direction: column; }
.salud-alerg-col .pg { margin-bottom: .5rem; }
.salud-alerg-col textarea.fi {
    flex: 1;
    min-height: 76px;
    /* nunca se desborda del contenedor */
    overflow: hidden;
    resize: none;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: pre-wrap;
}
.salud-restrict-col {}
.salud-restrict-col textarea.fi {
    overflow: hidden;
    resize: none;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: pre-wrap;
}
@media(max-width:680px){ .salud-grid { grid-template-columns: 1fr; } }

/* ── UPLOAD ── */
.uz { border: 2px dashed var(--border); border-radius: var(--r); padding: 1.5rem; text-align: center; cursor: pointer; transition: all .2s; background: var(--bg); position: relative; }
.uz:hover,.uz.over { border-color: var(--blue); background: var(--blue-l); }
.uz input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.uz .uzi { font-size: 1.7rem; color: var(--ink-4); margin-bottom: .35rem; }
.uz .uzt { font-size: .81rem; color: var(--ink-3); font-weight: 500; }
.uz .uzs { font-size: .69rem; color: var(--ink-4); margin-top: .15rem; }
.fprev { display: none; margin-top: .75rem; border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; }
.fprev.v { display: block; }
.fprev img { width: 100%; max-height: 190px; object-fit: contain; background: var(--bg); display: block; }
.fprev-ft { background: var(--ink); padding: .45rem .8rem; display: flex; justify-content: space-between; align-items: center; }
.fprev-ft .fn { font-size: .72rem; color: rgba(255,255,255,.75); font-family: 'DM Mono', monospace; }
.fprev-ft .fr { background: none; border: none; color: rgba(255,255,255,.5); cursor: pointer; font-size: .72rem; transition: color .15s; }
.fprev-ft .fr:hover { color: #f87171; }

/* ── ALERTA ── */
.alerta { display: flex; gap: .7rem; align-items: flex-start; background: var(--amber-l); border: 1.5px solid #fcd34d; border-radius: var(--r); padding: .8rem 1rem; margin-bottom: 1rem; }
.alerta .ai { font-size: .95rem; color: var(--amber); flex-shrink: 0; margin-top: .1rem; }
.alerta .at { font-size: .79rem; color: #92400e; line-height: 1.5; }
.alerta .at strong { display: block; margin-bottom: .1rem; }

/* ── PILL ── */
.pg { display: flex; gap: .45rem; flex-wrap: wrap; }
.pill { display: flex; align-items: center; gap: .38rem; padding: 6px 14px; border: 1.5px solid var(--border); border-radius: 999px; cursor: pointer; font-size: .77rem; font-weight: 500; color: var(--ink-3); transition: all .15s; user-select: none; }
.pill input { display: none; }
.pill:hover { border-color: var(--blue); color: var(--blue); }
.pill.sel { border-color: var(--blue); background: var(--blue-l); color: var(--blue); font-weight: 700; }

/* ── BOTONES ── */
.btn-p { background: var(--blue); color: #fff; border: none; border-radius: var(--r); padding: 10px 26px; font-size: .875rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; display: inline-flex; align-items: center; gap: .45rem; transition: all .15s; text-decoration: none; }
.btn-p:hover { background: var(--blue-d); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(37,99,235,.3); }
.btn-p.grn { background: var(--green); }
.btn-p.grn:hover { background: #047857; box-shadow: 0 4px 14px rgba(5,150,105,.3); }
.btn-p:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-s { background: transparent; color: var(--ink-2); border: 1.5px solid var(--border); border-radius: var(--r); padding: 9px 18px; font-size: .875rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; display: inline-flex; align-items: center; gap: .45rem; transition: all .15s; text-decoration: none; }
.btn-s:hover { border-color: var(--border-h); color: var(--ink); }

/* ── SUBMIT BAR ── */
.sbar { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--sh); gap: 1rem; flex-wrap: wrap; }
.sbar .si { font-size: .82rem; color: var(--ink-3); }
.sbar .si strong { color: var(--ink); font-family: 'DM Mono', monospace; font-size: 1rem; }
.sbar .sr { display: flex; gap: .65rem; align-items: center; flex-wrap: wrap; }

/* ── ERRORES ── */
.lerr { background: var(--red-l); border: 1.5px solid #fca5a5; border-radius: var(--r); padding: .9rem 1.2rem; margin-bottom: 1.3rem; font-size: .81rem; color: #991b1b; }
.lerr strong { display: block; margin-bottom: .35rem; font-weight: 700; }
.lerr ul { padding-left: 1.15rem; }
.lerr li { margin-bottom: .18rem; }

@keyframes fu { from{opacity:0;transform:translateY(4px)} to{opacity:1;transform:translateY(0)} }
</style>
@endpush

@section('contenido')
<div class="pw">

<div class="ph">
    <div>
        <div class="ph-eyebrow"><i class="bi bi-calendar-plus"></i> Sistema de reservas</div>
        <h1>Nueva Reserva</h1>
        <p>Ingresa todos los datos manualmente y guarda la reserva al finalizar.</p>
    </div>
    <a href="{{ route('reservas.index') }}" class="btn-back"><i class="bi bi-arrow-left"></i> Volver a Reservas</a>
</div>

@if($errors->any())
<div class="lerr">
    <strong><i class="bi bi-exclamation-triangle me-1"></i> Corrige los siguientes errores:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ route('reservas.store') }}" enctype="multipart/form-data" id="form-reserva" novalidate>
@csrf

{{-- ══════════════════════════════════
     BLOQUE 1 · TOUR Y CONFIGURACIÓN
══════════════════════════════════ --}}
<div class="fb">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-map"></i></div>
        <div><h3>Tour y configuración</h3><p>Nombre, precio, fechas y estado de la reserva</p></div>
    </div>
    <div class="fb-body">

        <div class="st">Información del tour</div>

        {{-- Fila 1: Nombre + Precio + Ciudad --}}
        <div class="g3" style="margin-bottom:1rem">
            <div class="field" style="margin-bottom:0">
                <label class="lbl">Nombre del tour <span class="req">*</span></label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-map"></i></span>
                    <input type="text" name="nombre_tour" value="{{ old('nombre_tour') }}"
                        class="fi {{ $errors->has('nombre_tour')?'err':'' }}"
                        placeholder="Ej: Vuelo en Globo Aerostático" required maxlength="200">
                </div>
                @error('nombre_tour')<div class="ferr">{{ $message }}</div>@enderror
            </div>
            <div class="field" style="margin-bottom:0">
                <label class="lbl">Precio del tour <span class="req">*</span></label>
                <div class="ig">
                    <span class="ia">S/</span>
                    <input type="number" name="precio_tour" id="precio_tour"
                        value="{{ old('precio_tour') }}"
                        class="fi {{ $errors->has('precio_tour')?'err':'' }}"
                        step="0.01" min="0" placeholder="0.00" required oninput="calcTotal()">
                </div>
                @error('precio_tour')<div class="ferr">{{ $message }}</div>@enderror
            </div>
            <div class="field" style="margin-bottom:0">
                <label class="lbl">Ciudad de origen <span class="req">*</span></label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="ciudad_procedencia" value="{{ old('ciudad_procedencia') }}"
                        class="fi {{ $errors->has('ciudad_procedencia')?'err':'' }}"
                        placeholder="Lima, Cusco, Trujillo..." required maxlength="100">
                </div>
                @error('ciudad_procedencia')<div class="ferr">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Panel de precios --}}
        <div class="pp">
            <div class="pp-item"><div class="pp-l">Precio total</div><div class="pp-v" id="pp-total">S/ 0.00</div></div>
            <div class="pp-item"><div class="pp-l">Adelanto mínimo (50%)</div><div class="pp-v a" id="pp-adel">S/ 0.00</div></div>
            <div class="pp-item"><div class="pp-l">Saldo al embarque</div><div class="pp-v r" id="pp-saldo">S/ 0.00</div></div>
        </div>

        {{-- Fila 2: Fecha + Hora + Canal --}}
        <div class="g3" style="margin-top:1rem">
            <div class="field" style="margin-bottom:0">
                <label class="lbl">Fecha del tour <span class="req">*</span></label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_tour" value="{{ old('fecha_tour') }}"
                        class="fi {{ $errors->has('fecha_tour')?'err':'' }}" required>
                </div>
                @error('fecha_tour')<div class="ferr">{{ $message }}</div>@enderror
            </div>
            <div class="field" style="margin-bottom:0">
                <label class="lbl">Hora de salida <span class="req">*</span></label>
                <div class="hora-wrap">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="time" name="hora_salida" id="hora_salida_24"
                        value="{{ old('hora_salida') }}"
                        class="fi fi-hora {{ $errors->has('hora_salida')?'err':'' }}"
                        required oninput="syncAmPm()">
                    <select class="ampm-sel" id="ampm-sel" onchange="syncHora()">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
                @error('hora_salida')<div class="ferr">{{ $message }}</div>@enderror
            </div>
            <div class="field" style="margin-bottom:0">
                <label class="lbl">Canal de contacto <span class="req">*</span></label>
                <select name="canal_contacto" class="fi" required>
                    <option value="whatsapp"       {{ old('canal_contacto','whatsapp')=='whatsapp'       ?'selected':'' }}>WhatsApp</option>
                    <option value="presencial"     {{ old('canal_contacto')=='presencial'     ?'selected':'' }}>Presencial</option>
                    <option value="llamada"        {{ old('canal_contacto')=='llamada'        ?'selected':'' }}>Llamada</option>
                    <option value="redes_sociales" {{ old('canal_contacto')=='redes_sociales' ?'selected':'' }}>Redes Sociales</option>
                    <option value="web"            {{ old('canal_contacto')=='web'            ?'selected':'' }}>Web</option>
                </select>
            </div>
        </div>

        <div class="st" style="margin-top:1.4rem">Estado inicial</div>
        <div class="eg">
            <label class="eo e-mitad {{ old('estado_inicial','mitad_pago')=='mitad_pago'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="mitad_pago" {{ old('estado_inicial','mitad_pago')=='mitad_pago'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-half"></i></span>
                50% Pagado
            </label>
            <label class="eo e-pagado {{ old('estado_inicial')=='pagado'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="pagado" {{ old('estado_inicial')=='pagado'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-check-circle"></i></span>
                Pagado completo
            </label>
            <label class="eo e-cancel {{ old('estado_inicial')=='cancelada'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="cancelada" {{ old('estado_inicial')=='cancelada'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-x-circle"></i></span>
                Cancelada
            </label>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 2 · DATOS DEL TITULAR
══════════════════════════════════ --}}
<div class="fb">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-person-badge"></i></div>
        <div><h3>Datos del titular</h3><p>Responsable principal de la reserva</p></div>
    </div>
    <div class="fb-body">

        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">

        <div class="st">Información personal</div>

        <div class="field">
            <label class="lbl">Nombre completo <span class="req">*</span></label>
            <div class="ig">
                <span class="ia"><i class="bi bi-person"></i></span>
                <input type="text" name="titular_nombre" id="titular_nombre"
                    value="{{ old('titular_nombre') }}"
                    class="fi {{ $errors->has('titular_nombre')?'err':'' }}"
                    placeholder="NOMBRES Y APELLIDOS COMPLETOS" required maxlength="200"
                    oninput="this.value=this.value.toUpperCase()">
            </div>
            @error('titular_nombre')<div class="ferr">{{ $message }}</div>@enderror
        </div>

        <div class="g13">
            <div class="field">
                <label class="lbl">Tipo de doc.</label>
                <select name="titular_tipo_documento" class="fi">
                    <option value="DNI"       {{ old('titular_tipo_documento','DNI')=='DNI'       ?'selected':'' }}>DNI</option>
                    <option value="CE"        {{ old('titular_tipo_documento')=='CE'        ?'selected':'' }}>C. Extranjería</option>
                    <option value="PASAPORTE" {{ old('titular_tipo_documento')=='PASAPORTE' ?'selected':'' }}>Pasaporte</option>
                    <option value="RUC"       {{ old('titular_tipo_documento')=='RUC'       ?'selected':'' }}>RUC</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl">Número de documento</label>
                <input type="text" name="titular_numero_documento"
                    value="{{ old('titular_numero_documento') }}"
                    class="fi" placeholder="Número de documento" maxlength="15">
            </div>
        </div>

        <div class="g3">
            <div class="field">
                <label class="lbl">Fecha de nacimiento</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar2-heart"></i></span>
                    <input type="date" name="titular_fecha_nacimiento"
                        value="{{ old('titular_fecha_nacimiento') }}" class="fi">
                </div>
            </div>
            <div class="field">
                <label class="lbl">Género</label>
                <select name="titular_genero" class="fi">
                    <option value="">— No especificar —</option>
                    <option value="M" {{ old('titular_genero')=='M'?'selected':'' }}>Masculino</option>
                    <option value="F" {{ old('titular_genero')=='F'?'selected':'' }}>Femenino</option>
                    <option value="otro" {{ old('titular_genero')=='otro'?'selected':'' }}>Otro</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl">Nacionalidad</label>
                <input type="text" name="titular_nacionalidad"
                    value="{{ old('titular_nacionalidad', 'Peruana') }}"
                    class="fi" placeholder="Peruana, Americana..." maxlength="80">
            </div>
        </div>

        <div class="st">Contacto</div>

        <div class="g2">
            <div class="field">
                <label class="lbl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="#25d366" style="margin-right:3px;vertical-align:middle"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Celular / WhatsApp <span class="req">*</span>
                </label>
                <div class="ig">
                    <span class="ia">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        +51
                    </span>
                    <input type="text" name="titular_telefono" id="titular_telefono"
                        value="{{ old('titular_telefono') }}"
                        class="fi {{ $errors->has('titular_telefono')?'err':'' }}"
                        placeholder="9XXXXXXXX" maxlength="9" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').substring(0,9)" required>
                </div>
                @error('titular_telefono')<div class="ferr">{{ $message }}</div>@enderror
                <div class="fhint">9 dígitos · Se usará para la confirmación por WhatsApp</div>
            </div>

            <div class="field">
                <label class="lbl"><i class="bi bi-envelope" style="color:var(--blue);margin-right:3px"></i>Correo electrónico</label>
                <div class="email-wrap">
                    <div class="email-row">
                        <span class="ea"><i class="bi bi-envelope" style="color:var(--blue)"></i></span>
                        <input type="text" id="email-user" class="fi email-user"
                            placeholder="usuario" maxlength="80" autocomplete="off"
                            oninput="emailInput()" onfocus="emailInput()"
                            onblur="setTimeout(closeDomains,200)">
                        <span class="at-sign">@</span>
                        <input type="text" id="email-domain" class="fi email-domain"
                            placeholder="dominio.com" maxlength="80" autocomplete="off"
                            oninput="joinEmail()" onblur="setTimeout(closeDomains,200)">
                    </div>
                    <ul class="domain-list" id="domain-list"></ul>
                    <input type="hidden" name="titular_email" id="titular_email" value="{{ old('titular_email') }}">
                </div>
                <div class="fhint">Escribe el usuario y elige o escribe el dominio</div>
            </div>
        </div>

        <div class="field" style="max-width:280px">
            <label class="lbl">
                <i class="bi bi-telephone" style="margin-right:3px"></i>
                Teléfono secundario
                <span class="opt">(opcional — solo si difiere del principal)</span>
            </label>
            <div class="ig">
                <span class="ia"><i class="bi bi-telephone"></i></span>
                <input type="text" name="titular_telefono2" value="{{ old('titular_telefono2') }}"
                    class="fi" placeholder="076-XXXXXX o 9XXXXXXXX" maxlength="15">
            </div>
            <div class="fhint">Si no se indica, las notificaciones van solo al número principal.</div>
        </div>

        <div class="st">Notificaciones</div>
        <div class="pg">
            <label class="pill sel" id="p-wa" onclick="togPill(this)">
                <input type="checkbox" name="notif_whatsapp" value="1" checked>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </label>
            <label class="pill" id="p-em" onclick="togPill(this)">
                <input type="checkbox" name="notif_email" value="1">
                <i class="bi bi-envelope"></i> Correo
            </label>
        </div>
        <div class="fhint" style="margin-top:.5rem">La confirmación se enviará al guardar la reserva.</div>
    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 3 · PASAJEROS ADICIONALES
══════════════════════════════════ --}}
<div class="fb">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-people"></i></div>
        <div><h3>Pasajeros adicionales</h3><p>Añade el resto del grupo (el titular ya está incluido)</p></div>
    </div>
    <div class="fb-body">
        <div class="alerta">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>El titular se registra como pasajero principal automáticamente.</strong>
                Agrega aquí a los demás integrantes. El documento es opcional.
            </div>
        </div>
        <div id="pax-lista"></div>
        <p id="pax-cnt" style="font-size:.77rem;color:var(--ink-4);margin-bottom:.4rem;"></p>
        <button type="button" class="btn-add" onclick="addPax()">
            <i class="bi bi-person-plus"></i> Agregar pasajero
        </button>
    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 4 · SALUD POR PASAJERO
══════════════════════════════════ --}}
<div class="fb">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-heart-pulse"></i></div>
        <div><h3>Salud y seguridad</h3><p>Información médica de cada pasajero del grupo</p></div>
    </div>
    <div class="fb-body">
        <div id="salud-lista">
            <div class="salud-pasajero" id="salud-titular">
                <div class="salud-pasajero-head">
                    <i class="bi bi-person-badge" style="color:var(--blue)"></i>
                    <span>Titular — <span id="salud-titular-nombre" style="font-style:italic;color:var(--ink-3)">nombre del titular</span></span>
                </div>
                <div class="salud-pasajero-body">
                    <div class="salud-grid">
                        <div class="salud-alerg-col">
                            <label class="lbl">Alergias o condiciones médicas</label>
                            <div class="pg" style="margin-bottom:.5rem">
                                <label class="pill sel" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="no" {{ old('titular_tiene_alergias','no')=='no'?'checked':'' }}>
                                    <i class="bi bi-check-circle"></i> No
                                </label>
                                <label class="pill {{ old('titular_tiene_alergias')=='si'?'sel':'' }}" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="si" {{ old('titular_tiene_alergias')=='si'?'checked':'' }}>
                                    <i class="bi bi-exclamation-triangle"></i> Sí, tiene
                                </label>
                            </div>
                            <textarea name="titular_alergias_detalle" id="alerg-titular" class="fi" rows="3"
                                placeholder="Describe alergias, medicamentos o condiciones..."
                                style="display:{{ old('titular_tiene_alergias')=='si'?'block':'none' }}">{{ old('titular_alergias_detalle') }}</textarea>
                        </div>
                        <div class="salud-restrict-col">
                            <label class="lbl">Restricciones alimentarias</label>
                            <textarea name="titular_restricciones" class="fi" rows="3"
                                placeholder="Vegetariano, vegano, sin gluten, halal...">{{ old('titular_restricciones') }}</textarea>
                        </div>
                    </div>
                    <div class="field" style="margin-top:.75rem;margin-bottom:0">
                        <label class="lbl">Observaciones médicas adicionales</label>
                        <textarea name="titular_obs_medicas" class="fi" rows="2"
                            placeholder="Discapacidades, condiciones que el guía deba conocer...">{{ old('titular_obs_medicas') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <p style="font-size:.75rem;color:var(--ink-4);margin-top:.5rem">
            <i class="bi bi-info-circle me-1"></i>
            Al agregar pasajeros en el bloque anterior, aparecerán aquí sus campos de salud automáticamente.
        </p>
    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 5 · PAGO Y COMPROBANTE
══════════════════════════════════ --}}
<div class="fb">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-credit-card"></i></div>
        <div><h3>Pago y comprobante</h3><p>Método de pago, monto y comprobante adjunto</p></div>
    </div>
    <div class="fb-body">
        <div class="alerta">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Se requiere el 50% de adelanto para confirmar.</strong>
                El saldo restante se abona al momento del embarque.
            </div>
        </div>

        <div class="st">Comprobante fiscal</div>
        <div class="g2">
            <div class="field">
                <label class="lbl">Tipo de comprobante <span class="req">*</span></label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="fi" required onchange="togFactura()">
                    <option value="boleta"  {{ old('tipo_comprobante','boleta')=='boleta'?'selected':'' }}>Boleta de venta</option>
                    <option value="factura" {{ old('tipo_comprobante')=='factura'?'selected':'' }}>Factura</option>
                </select>
            </div>
            <div id="campos-factura" style="display:{{ old('tipo_comprobante')=='factura'?'grid':'none' }};grid-template-columns:1fr 2fr;gap:1rem;align-items:start">
                <div class="field" id="campo-ruc">
                    <label class="lbl">RUC <span class="req">*</span></label>
                    <input type="text" name="ruc_factura" id="ruc_factura" value="{{ old('ruc_factura') }}" class="fi" placeholder="20XXXXXXXXX" maxlength="11" inputmode="numeric" oninput="this.value=this.value.replace(/\D/g,'').substring(0,11)">
                </div>
                <div class="field" id="campo-razon">
                    <label class="lbl">Razón social <span class="req">*</span></label>
                    <input type="text" name="razon_social" id="razon_social" value="{{ old('razon_social') }}" class="fi" placeholder="EMPRESA S.A.C." maxlength="200" oninput="this.value=this.value.toUpperCase()">
                </div>
            </div>
        </div>

        <div class="st">Registro del pago</div>
        <div class="g2">
            <div class="field">
                <label class="lbl">Método de pago <span class="req">*</span></label>
                <select name="metodo_pago" id="metodo_pago" class="fi" required onchange="updOpHint()">
                    <option value="">Seleccionar...</option>
                    <optgroup label="Efectivo"><option value="efectivo" {{ old('metodo_pago')=='efectivo'?'selected':'' }}>Efectivo</option></optgroup>
                    <optgroup label="Pagos digitales">
                        <option value="yape"  {{ old('metodo_pago')=='yape' ?'selected':'' }}>Yape</option>
                        <option value="plin"  {{ old('metodo_pago')=='plin' ?'selected':'' }}>Plin</option>
                        <option value="tunki" {{ old('metodo_pago')=='tunki'?'selected':'' }}>Tunki</option>
                    </optgroup>
                    <optgroup label="Transferencia bancaria">
                        <option value="transf_bcp"   {{ old('metodo_pago')=='transf_bcp'  ?'selected':'' }}>Transferencia BCP</option>
                        <option value="transf_bbva"  {{ old('metodo_pago')=='transf_bbva' ?'selected':'' }}>Transferencia BBVA</option>
                        <option value="transf_inter" {{ old('metodo_pago')=='transf_inter'?'selected':'' }}>Transferencia Interbank</option>
                        <option value="transf_sc"    {{ old('metodo_pago')=='transf_sc'   ?'selected':'' }}>Transferencia Scotiabank</option>
                        <option value="transf_bn"    {{ old('metodo_pago')=='transf_bn'   ?'selected':'' }}>Transferencia Banco Nación</option>
                        <option value="transf_otros" {{ old('metodo_pago')=='transf_otros'?'selected':'' }}>Otro banco</option>
                    </optgroup>
                    <optgroup label="Depósito bancario">
                        <option value="dep_bcp"   {{ old('metodo_pago')=='dep_bcp'  ?'selected':'' }}>Depósito BCP</option>
                        <option value="dep_bbva"  {{ old('metodo_pago')=='dep_bbva' ?'selected':'' }}>Depósito BBVA</option>
                        <option value="dep_inter" {{ old('metodo_pago')=='dep_inter'?'selected':'' }}>Depósito Interbank</option>
                        <option value="dep_otros" {{ old('metodo_pago')=='dep_otros'?'selected':'' }}>Depósito otro banco</option>
                    </optgroup>
                    <optgroup label="Tarjeta">
                        <option value="tarjeta_credito" {{ old('metodo_pago')=='tarjeta_credito'?'selected':'' }}>Tarjeta crédito</option>
                        <option value="tarjeta_debito"  {{ old('metodo_pago')=='tarjeta_debito' ?'selected':'' }}>Tarjeta débito</option>
                    </optgroup>
                </select>
            </div>
            <div class="field">
                <label class="lbl">Tipo de pago</label>
                <select name="tipo_pago" id="tipo_pago" class="fi" onchange="onTipoPago()">
                    <option value="adelanto">Adelanto (50%)</option>
                    <option value="pago_completo">Pago completo (100%)</option>
                </select>
            </div>
        </div>

        <div class="g2">
            <div class="field">
                <label class="lbl">Monto pagado (S/) <span class="req">*</span></label>
                <div class="ig">
                    <span class="ia">S/</span>
                    <input type="number" name="monto_pagado_inicial" id="monto_pagado_inicial"
                        value="{{ old('monto_pagado_inicial') }}"
                        class="fi {{ $errors->has('monto_pagado_inicial')?'err':'' }}"
                        step="0.01" min="0" placeholder="0.00" required oninput="calcTotal()">
                </div>
                <div class="fhint" id="hint-adel"></div>
                @error('monto_pagado_inicial')<div class="ferr">{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl">Fecha de pago</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" class="fi">
                </div>
            </div>
        </div>

        <div class="field">
            <label class="lbl">N° operación / referencia <span class="opt">(opcional — se puede identificar con el comprobante)</span></label>
            <input type="text" name="numero_operacion" value="{{ old('numero_operacion') }}"
                class="fi" placeholder="Código de transacción..." maxlength="100" style="max-width:360px">
            <div class="fhint" id="op-hint">Código visible en Yape, Plin o constancia bancaria</div>
        </div>

        <div class="st">Comprobante adjunto</div>
        <div class="uz" id="uz"
             ondragover="event.preventDefault();this.classList.add('over')"
             ondragleave="this.classList.remove('over')"
             ondrop="onDrop(event)">
            <input type="file" name="archivo_baucher" id="archivo_baucher" accept=".jpg,.jpeg,.png,.pdf,.webp" onchange="onFile(event)">
            <div class="uzi"><i class="bi bi-cloud-arrow-up"></i></div>
            <div class="uzt">Arrastra el comprobante aquí o <strong style="color:var(--blue)">haz clic</strong> para seleccionar</div>
            <div class="uzs">JPG · PNG · PDF · WEBP — máx. 5 MB</div>
        </div>
        <div class="fprev" id="fprev">
            <img id="prev-img" src="" alt="">
            <div class="fprev-ft">
                <span class="fn" id="prev-name">—</span>
                <button type="button" class="fr" onclick="removeFile()"><i class="bi bi-x-circle me-1"></i>Quitar</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 6 · LOGÍSTICA
══════════════════════════════════ --}}
<div class="fb">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-geo-alt"></i></div>
        <div><h3>Logística</h3><p>Punto de encuentro, recojo y observaciones finales</p></div>
    </div>
    <div class="fb-body">
        <div class="g2">
            <div class="field">
                <label class="lbl">Punto de encuentro</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-pin-map"></i></span>
                    <input type="text" name="punto_encuentro" value="{{ old('punto_encuentro') }}" class="fi" placeholder="Hotel, dirección, referencia..." maxlength="200">
                </div>
            </div>
            <div class="field">
                <label class="lbl">Hora de recojo</label>
                <div class="hora-wrap">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="time" name="hora_recojo" id="hora_recojo_24" value="{{ old('hora_recojo') }}" class="fi fi-hora" oninput="syncAmPmRecojo()">
                    <select class="ampm-sel" id="ampm-sel-recojo" onchange="syncHoraRecojo()">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="field">
            <label class="lbl">Guía asignado</label>
            <div class="ig" style="max-width:340px">
                <span class="ia"><i class="bi bi-person-badge"></i></span>
                <input type="text" name="guia_asignado" value="{{ old('guia_asignado') }}" class="fi" placeholder="Nombre del guía..." maxlength="150">
            </div>
        </div>
        <div class="field">
            <label class="lbl">Observaciones adicionales</label>
            <textarea name="observaciones" class="fi" rows="3" placeholder="Notas internas, requerimientos especiales, indicaciones para el guía...">{{ old('observaciones') }}</textarea>
        </div>
    </div>
</div>

<div class="sbar">
    <div class="si">Total de la reserva: <strong id="sb-total">S/ 0.00</strong></div>
    <div class="sr">
        <a href="{{ route('reservas.index') }}" class="btn-s"><i class="bi bi-x"></i> Cancelar</a>
        <button type="submit" class="btn-p grn" id="btn-submit">
            <i class="bi bi-check-circle"></i> Guardar reserva
        </button>
    </div>
</div>

</form>
</div>
@endsection

@push('scripts')
<script>
function calcTotal() {
    const precio = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const pagado = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
    const adel   = precio * 0.5;
    const saldo  = Math.max(0, precio - pagado);
    const fmt    = v => `S/ ${v.toFixed(2)}`;
    document.getElementById('pp-total').textContent  = fmt(precio);
    document.getElementById('pp-adel').textContent   = fmt(adel);
    document.getElementById('pp-saldo').textContent  = fmt(saldo);
    document.getElementById('sb-total').textContent  = fmt(precio);
    const h = document.getElementById('hint-adel');
    if (h) h.textContent = precio > 0 ? `Adelanto mínimo sugerido: S/ ${adel.toFixed(2)}` : '';
}
function onTipoPago() {
    const tipo   = document.getElementById('tipo_pago').value;
    const precio = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const c      = document.getElementById('monto_pagado_inicial');
    c.value = tipo === 'pago_completo' ? precio.toFixed(2) : (precio * 0.5).toFixed(2);
    calcTotal();
}
function syncAmPm() {
    const v = document.getElementById('hora_salida_24').value;
    if (!v) return;
    document.getElementById('ampm-sel').value = parseInt(v.split(':')[0]) >= 12 ? 'PM' : 'AM';
}
function syncHora() {
    const sel = document.getElementById('ampm-sel');
    const inp = document.getElementById('hora_salida_24');
    if (!inp.value) return;
    let [hh, mm] = inp.value.split(':').map(Number);
    if (sel.value === 'PM' && hh < 12) hh += 12;
    if (sel.value === 'AM' && hh === 12) hh = 0;
    inp.value = `${String(hh).padStart(2,'0')}:${String(mm).padStart(2,'0')}`;
}
function syncAmPmRecojo() {
    const v = document.getElementById('hora_recojo_24')?.value;
    if (!v) return;
    const s = document.getElementById('ampm-sel-recojo');
    if (s) s.value = parseInt(v.split(':')[0]) >= 12 ? 'PM' : 'AM';
}
function syncHoraRecojo() {
    const sel = document.getElementById('ampm-sel-recojo');
    const inp = document.getElementById('hora_recojo_24');
    if (!inp || !inp.value) return;
    let [hh, mm] = inp.value.split(':').map(Number);
    if (sel.value === 'PM' && hh < 12) hh += 12;
    if (sel.value === 'AM' && hh === 12) hh = 0;
    inp.value = `${String(hh).padStart(2,'0')}:${String(mm).padStart(2,'0')}`;
}
function selEst(lbl) {
    document.querySelectorAll('.eo').forEach(e => e.classList.remove('sel'));
    lbl.classList.add('sel');
    lbl.querySelector('input').checked = true;
}
const DOMS = [
    {icon:'✉',v:'gmail.com'},{icon:'✉',v:'hotmail.com'},
    {icon:'✉',v:'outlook.com'},{icon:'✉',v:'yahoo.com'},
    {icon:'✉',v:'icloud.com'},{icon:'✉',v:'live.com'},
];
function emailInput() {
    const u = document.getElementById('email-user').value.trim();
    if (!u) { closeDomains(); return; }
    const dl = document.getElementById('domain-list');
    dl.innerHTML = DOMS.map(d => `<li onclick="pickDomain('${d.v}')">${d.icon} ${d.v}</li>`).join('')
        + `<li onclick="closeDomains()" style="color:var(--ink-4);font-style:italic;font-size:.79rem">Escribir dominio propio</li>`;
    dl.classList.add('open'); joinEmail();
}
function pickDomain(v) { document.getElementById('email-domain').value = v; closeDomains(); joinEmail(); }
function closeDomains() { document.getElementById('domain-list').classList.remove('open'); joinEmail(); }
function joinEmail() {
    const u = document.getElementById('email-user').value.trim();
    const d = document.getElementById('email-domain').value.trim();
    document.getElementById('titular_email').value = (u && d) ? `${u}@${d}` : '';
}
function loadEmailOld() {
    const raw = '{{ old("titular_email") }}';
    if (!raw) return;
    const p = raw.split('@');
    if (p.length === 2) { document.getElementById('email-user').value = p[0]; document.getElementById('email-domain').value = p[1]; joinEmail(); }
}
function actualizarNombreTitularSalud(nombre) {
    const el = document.getElementById('salud-titular-nombre');
    if (el) el.textContent = nombre || 'nombre del titular';
}
let pN = 0;
function addPax() {
    const lista = document.getElementById('pax-lista');
    const i = pN++;
    const d = document.createElement('div');
    d.className = 'pax-card'; d.id = `pax-${i}`;
    d.innerHTML = `
        <div class="pax-head">
            <span><i class="bi bi-person me-1"></i>Pasajero adicional ${i+1}</span>
            <button type="button" class="pax-del" onclick="removePax(${i})"><i class="bi bi-x"></i></button>
        </div>
        <div class="g3">
            <div class="field"><label class="lbl">Nombre completo <span class="req">*</span></label>
                <input type="text" name="pasajeros[${i}][nombre_completo]" id="pax-nombre-${i}" class="fi"
                    placeholder="NOMBRES APELLIDOS" oninput="this.value=this.value.toUpperCase();updateSaludNombre(${i},this.value)" required></div>
            <div class="field"><label class="lbl">Tipo</label>
                <select name="pasajeros[${i}][tipo]" class="fi"><option value="adulto">Adulto</option><option value="nino">Niño</option></select></div>
            <div class="field"><label class="lbl">Edad</label>
                <input type="number" name="pasajeros[${i}][edad]" class="fi" min="0" max="120" placeholder="—"></div>
            <div class="field"><label class="lbl">Tipo de doc.</label>
                <select name="pasajeros[${i}][tipo_documento]" class="fi" onchange="paxDoc(this,${i})">
                    <option value="">Sin documento</option><option value="DNI">DNI</option>
                    <option value="CE">C.E.</option><option value="PASAPORTE">Pasaporte</option></select></div>
            <div class="field"><label class="lbl">N° de documento</label>
                <input type="text" name="pasajeros[${i}][numero_documento]" id="pd-${i}" class="fi" placeholder="Opcional"></div>
            <div class="field"><label class="lbl">Teléfono</label>
                <input type="text" name="pasajeros[${i}][telefono]" class="fi" placeholder="Opcional" maxlength="15"></div>
        </div>`;
    lista.appendChild(d);
    addSaludPax(i);
    paxCnt();
}
function removePax(i) {
    document.getElementById(`pax-${i}`)?.remove();
    document.getElementById(`salud-pax-${i}`)?.remove();
    paxCnt();
}
function addSaludPax(i) {
    const lista = document.getElementById('salud-lista');
    const s = document.createElement('div');
    s.className = 'salud-pasajero'; s.id = `salud-pax-${i}`;
    s.innerHTML = `
        <div class="salud-pasajero-head">
            <i class="bi bi-person" style="color:var(--ink-3)"></i>
            <span>Pasajero ${i+1} — <span id="salud-nombre-${i}" style="font-style:italic;color:var(--ink-3)">sin nombre</span></span>
        </div>
        <div class="salud-pasajero-body">
            <div class="salud-grid">
                <div class="salud-alerg-col">
                    <label class="lbl">Alergias o condiciones médicas</label>
                    <div class="pg" style="margin-bottom:.5rem">
                        <label class="pill sel" onclick="togAlergPax(this,'alerg-pax-${i}')">
                            <input type="radio" name="pasajeros[${i}][tiene_alergias]" value="no" checked>
                            <i class="bi bi-check-circle"></i> No
                        </label>
                        <label class="pill" onclick="togAlergPax(this,'alerg-pax-${i}')">
                            <input type="radio" name="pasajeros[${i}][tiene_alergias]" value="si">
                            <i class="bi bi-exclamation-triangle"></i> Sí, tiene
                        </label>
                    </div>
                    <textarea name="pasajeros[${i}][alergias_detalle]" id="alerg-pax-${i}" class="fi" rows="3"
                        placeholder="Describe alergias, medicamentos..." style="display:none"></textarea>
                </div>
                <div class="salud-restrict-col">
                    <label class="lbl">Restricciones alimentarias</label>
                    <textarea name="pasajeros[${i}][restricciones]" class="fi" rows="3"
                        placeholder="Vegetariano, sin gluten..."></textarea>
                </div>
            </div>
            <div class="field" style="margin-top:.75rem;margin-bottom:0">
                <label class="lbl">Observaciones médicas adicionales</label>
                <textarea name="pasajeros[${i}][obs_medicas]" class="fi" rows="2"
                    placeholder="Discapacidades, condiciones que el guía deba conocer..."></textarea>
            </div>
        </div>`;
    lista.appendChild(s);
}
function updateSaludNombre(i, nombre) {
    const el = document.getElementById(`salud-nombre-${i}`);
    if (el) el.textContent = nombre || 'sin nombre';
}
function paxDoc(sel, i) {
    const inp = document.getElementById(`pd-${i}`); if (!inp) return;
    const t = sel.value;
    if (t==='DNI')      { inp.maxLength=8;  inp.placeholder='8 dígitos'; inp.oninput=()=>{inp.value=inp.value.replace(/\D/g,'').substring(0,8);}; }
    else if(t==='CE')   { inp.maxLength=12; inp.placeholder='Hasta 12';  inp.oninput=()=>{inp.value=inp.value.replace(/\D/g,'').substring(0,12);}; }
    else if(t==='PASAPORTE'){ inp.maxLength=15; inp.placeholder='Alfanum.'; inp.oninput=()=>{inp.value=inp.value.toUpperCase().substring(0,15);}; }
    else { inp.maxLength=20; inp.placeholder='Opcional'; }
}
function paxCnt() {
    const n = document.querySelectorAll('#pax-lista .pax-card').length;
    document.getElementById('pax-cnt').textContent = n > 0 ? `${n} pasajero(s) adicional(es).` : '';
}
function togAlergPax(lbl, taId) {
    lbl.closest('.pg').querySelectorAll('.pill').forEach(p => p.classList.remove('sel'));
    lbl.classList.add('sel');
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
    const ta = document.getElementById(taId);
    if (ta) { const show = radio && radio.value==='si'; ta.style.display=show?'block':'none'; if(!show) ta.value=''; }
}
function togFactura() {
    const es = document.getElementById('tipo_comprobante').value==='factura';
    document.getElementById('campos-factura').style.display = es?'grid':'none';
    document.getElementById('ruc_factura').required  = es;
    document.getElementById('razon_social').required = es;
}
function togPill(lbl) {
    lbl.classList.toggle('sel');
    const cb = lbl.querySelector('input[type="checkbox"]');
    if (cb) cb.checked = lbl.classList.contains('sel');
}
function updOpHint() {
    const v = document.getElementById('metodo_pago').value;
    const h = document.getElementById('op-hint');
    if (!h) return;
    if (v==='yape'||v==='plin'||v==='tunki') h.textContent='Número de operación visible en la app (opcional si adjunta imagen)';
    else if(v.startsWith('transf')||v.startsWith('dep')) h.textContent='N° de constancia o código bancario (opcional si adjunta imagen)';
    else if(v.startsWith('tarjeta')) h.textContent='Últimos 4 dígitos o N° de voucher (opcional)';
    else h.textContent='Código de referencia (opcional — se puede identificar con el comprobante)';
}
function onFile(e) { if (e.target.files[0]) showPrev(e.target.files[0]); }
function onDrop(e) {
    e.preventDefault(); document.getElementById('uz').classList.remove('over');
    const f = e.dataTransfer.files[0]; if (!f) return;
    try { document.getElementById('archivo_baucher').files = e.dataTransfer.files; } catch(_) {}
    showPrev(f);
}
function showPrev(f) {
    document.getElementById('uz').style.display = 'none';
    document.getElementById('prev-name').textContent = f.name;
    const img = document.getElementById('prev-img');
    if (f.type.startsWith('image/')) { const r = new FileReader(); r.onload = e => { img.src=e.target.result; img.style.display='block'; }; r.readAsDataURL(f); }
    else { img.style.display = 'none'; }
    document.getElementById('fprev').classList.add('v');
}
function removeFile() {
    document.getElementById('archivo_baucher').value = '';
    document.getElementById('fprev').classList.remove('v');
    document.getElementById('uz').style.display = '';
    document.getElementById('prev-img').src = '';
}
document.getElementById('form-reserva').addEventListener('submit', function() {
    joinEmail();
    const b = document.getElementById('btn-submit');
    b.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:13px;height:13px;border-width:2px"></span> Guardando...';
    b.disabled = true;
});
document.addEventListener('DOMContentLoaded', () => {
    calcTotal(); loadEmailOld(); updOpHint();
    const horaVal = document.getElementById('hora_salida_24').value;
    if (horaVal) syncAmPm();
    document.getElementById('precio_tour')?.addEventListener('input', calcTotal);
    document.getElementById('monto_pagado_inicial')?.addEventListener('input', calcTotal);
    const est = document.querySelector('[name="estado_inicial"]:checked');
    if (est) est.closest('.eo')?.classList.add('sel');
    const titularInput = document.getElementById('titular_nombre');
    if (titularInput) {
        titularInput.addEventListener('input', () => actualizarNombreTitularSalud(titularInput.value));
        actualizarNombreTitularSalud(titularInput.value);
    }
    // Auto-grow textareas
    document.querySelectorAll('textarea.fi').forEach(ta => {
        ta.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
});
</script>
@endpush