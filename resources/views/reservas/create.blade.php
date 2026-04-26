{{-- =====================================================================
     ARCHIVO: create.blade.php
     UBICACIÓN: resources/views/reservas/create.blade.php
     SERVICIO: Adventure — Agencia de Viajes
     VERSIÓN 7.0 — Ajustes UI/UX puntuales
     CAMBIOS v7:
       ✓ 1. Búsqueda de RUC corregida (listener oninput correctamente conectado)
       ✓ 2. Progreso y Resumen Financiero CENTRADOS verticalmente y siguen scroll
       ✓ 3. Resumen financiero SIN sección de progreso interna (bloque independiente)
       ✓ 4. Campos "Detalla alergias" y "Restricciones alimentarias" alineados
       ✓ 5. Switch Salud invertido: desactivado=SÍ hay alergias (muestra campos),
              activado=NO hay alergias. Inicia desactivado y NO marca completo
              hasta interacción/datos.
       ✓ 6. Switch Pasajeros invertido: desactivado=hay pasajeros (muestra),
              activado=solo titular (oculta). Inicia desactivado.
       ✓ 7. Barra de progreso con mejor alineación/distribución/espaciado
       ✓ NO se modificó lógica de negocio, flujo, ni colores
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Nueva Reserva — Adventure')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Familjen+Grotesk:ital,wght@0,400;0,600;0,700;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
:root {
    --adv-navy:      #0B1E3D;
    --adv-navy-2:    #152B54;
    --adv-navy-3:    #1C3464;
    --adv-blue:      #1A56DB;
    --adv-blue-l:    #EBF1FD;
    --adv-blue-m:    #C3D5F8;
    --adv-azure:     #3B82F6;
    --adv-amber:     #F59E0B;
    --adv-amber-d:   #B45309;
    --adv-amber-l:   #FFFBEB;
    --adv-amber-m:   #FDE68A;
    --adv-amber-xm:  #FCD34D;
    --adv-red:       #DC2626;
    --adv-red-l:     #FEF2F2;
    --adv-red-m:     #FECACA;
    --adv-green:     #16A34A;
    --adv-green-l:   #F0FDF4;
    --adv-green-m:   #BBF7D0;
    --adv-slate:     #475569;
    --adv-slate-l:   #F1F5F9;
    --adv-slate-2:   #94A3B8;
    --adv-slate-3:   #CBD5E1;
    --surface:       #FFFFFF;
    --bg:            #F0F4F8;
    --border:        #E2E8F0;
    --border-h:      #94A3B8;
    --ink:           #0F172A;
    --ink-2:         #1E293B;
    --ink-3:         #475569;
    --ink-4:         #94A3B8;
    --r:             8px;
    --r-lg:          12px;
    --r-xl:          16px;
    --sh:            0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
    --sh-md:         0 4px 20px rgba(15,23,42,.09), 0 2px 8px rgba(15,23,42,.05);
    --sh-lg:         0 10px 40px rgba(15,23,42,.12), 0 4px 16px rgba(15,23,42,.07);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
    letter-spacing: -.01em;
}

/* ═══════════════════════════════
   PAGE LAYOUT
   Sidebar izq: 220px | Form: 1fr | Panel der: 290px
═══════════════════════════════ */
.page-wrap {
    max-width: 1440px;
    margin: 0 auto;
    padding: 1.75rem 1.5rem 5rem;
    display: grid;
    grid-template-columns: 220px 1fr 290px;
    grid-template-rows: auto 1fr;
    gap: 1.5rem;
    align-items: start;
}

.page-header { grid-column: 1 / -1; }

/* ─── SIDEBAR PROGRESO — centrado vertical y sigue scroll ─── */
.progress-sidebar {
    grid-column: 1;
    grid-row: 2;
    align-self: start;
    /* Sticky con offset que centra el contenido aprox. en mitad del viewport */
    position: sticky;
    top: 50%;
    transform: translateY(-50%);
    /* Limita altura para que nunca exceda viewport */
    max-height: calc(100vh - 4rem);
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--border) transparent;
}
.progress-sidebar::-webkit-scrollbar { width: 4px; }
.progress-sidebar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

.form-main { grid-column: 2; grid-row: 2; min-width: 0; }

/* ─── PANEL RESUMEN — centrado vertical y sigue scroll ─── */
.summary-panel {
    grid-column: 3;
    grid-row: 2;
    align-self: start;
    position: sticky;
    top: 50%;
    transform: translateY(-50%);
    max-height: calc(100vh - 4rem);
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: rgba(255,255,255,.15) transparent;
}
.summary-panel::-webkit-scrollbar { width: 4px; }
.summary-panel::-webkit-scrollbar-thumb { background: rgba(0,0,0,.15); border-radius: 4px; }

/* Responsive */
@media (max-width: 1200px) {
    .page-wrap { grid-template-columns: 200px 1fr; }
    .summary-panel {
        grid-column: 1 / -1; grid-row: 3;
        position: static; transform: none; max-height: none;
    }
}
@media (max-width: 768px) {
    .page-wrap { grid-template-columns: 1fr; padding: 1rem .75rem 4rem; }
    .progress-sidebar, .form-main, .summary-panel { grid-column: 1; grid-row: auto; }
    .progress-sidebar {
        position: static; transform: none; max-height: none;
    }
    .summary-panel { position: static; transform: none; max-height: none; }
}

/* ═══════════════════════════════
   PAGE HEADER
═══════════════════════════════ */
.page-header-inner {
    background: linear-gradient(135deg, var(--adv-navy) 0%, var(--adv-navy-2) 40%, var(--adv-navy-3) 100%);
    border-radius: var(--r-xl);
    padding: 1.75rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    box-shadow: var(--sh-lg);
    position: relative;
    overflow: hidden;
}
.page-header-inner::before {
    content: ''; position: absolute; top: -60px; right: -40px;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(245,158,11,.18) 0%, transparent 70%);
    pointer-events: none;
}
.ph-brand { display: flex; align-items: center; gap: .75rem; position: relative; z-index: 1; }
.ph-logo {
    width: 46px; height: 46px; background: var(--adv-amber);
    border-radius: var(--r); display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(245,158,11,.4);
}
.ph-text .eyebrow { font-size: .6rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--adv-amber-xm); margin-bottom: .25rem; }
.ph-text h1 { font-family: 'Familjen Grotesk', sans-serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.1; }
.ph-text p { font-size: .77rem; color: rgba(255,255,255,.55); margin-top: .2rem; }
.btn-back {
    position: relative; z-index: 1;
    display: inline-flex; align-items: center; gap: .4rem;
    color: rgba(255,255,255,.75); font-size: .78rem; font-weight: 600;
    text-decoration: none; padding: 8px 18px;
    border: 1.5px solid rgba(255,255,255,.2); border-radius: var(--r);
    background: rgba(255,255,255,.08); backdrop-filter: blur(6px); transition: all .15s; white-space: nowrap;
}
.btn-back:hover { background: rgba(255,255,255,.15); color: #fff; border-color: rgba(255,255,255,.4); }

/* ═══════════════════════════════
   PROGRESS SIDEBAR — mejorado en alineación/distribución
═══════════════════════════════ */
.ps-inner {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-xl);
    padding: 1.15rem 1rem 1rem;
    box-shadow: var(--sh-md);
}
.ps-title {
    font-size: .58rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
    color: var(--ink-4);
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--border);
    margin-bottom: .9rem;
    display: flex; align-items: center; gap: .4rem;
    justify-content: center;
    text-align: center;
}
.ps-steps {
    display: flex;
    flex-direction: column;
    gap: .15rem;
}

/* Item — más espacio interno y mejor balance */
.ps-item {
    display: flex; align-items: flex-start; gap: .65rem;
    padding: .5rem .45rem;
    border-radius: var(--r);
    cursor: pointer; transition: background .2s; position: relative;
}
.ps-item:hover { background: var(--adv-slate-l); }

.ps-connector-wrap {
    display: flex; flex-direction: column; align-items: center;
    flex-shrink: 0; width: 26px;
}
.ps-num {
    width: 26px; height: 26px; border-radius: 50%;
    border: 2px solid var(--border); background: var(--surface);
    color: var(--ink-4); font-size: .65rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: all .25s; position: relative; z-index: 1;
}
.ps-line {
    width: 1.5px;
    height: 16px;
    background: var(--border);
    margin: 3px 0;
    transition: background .3s;
    flex-shrink: 0;
}
.ps-item:last-child .ps-line { display: none; }

.ps-info {
    flex: 1; min-width: 0;
    padding-top: 4px;
    display: flex; flex-direction: column; justify-content: center;
}
.ps-label {
    font-size: .73rem; font-weight: 700; color: var(--ink-4);
    transition: color .2s;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    line-height: 1.2;
}
.ps-sub   {
    font-size: .6rem; color: var(--ink-4); opacity: 0;
    transition: opacity .2s;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    line-height: 1.3; margin-top: 2px;
}

/* States */
.ps-item.active { background: var(--adv-amber-l); }
.ps-item.active .ps-num { border-color: var(--adv-amber); background: var(--adv-amber); color: var(--adv-navy); box-shadow: 0 0 0 3px rgba(245,158,11,.2); }
.ps-item.active .ps-label { color: var(--ink-2); font-weight: 800; }
.ps-item.active .ps-sub { opacity: 1; color: var(--adv-amber-d); }

.ps-item.done .ps-num { border-color: var(--adv-blue); background: var(--adv-blue); color: #fff; }
.ps-item.done .ps-label { color: var(--adv-blue); font-weight: 700; }
.ps-item.done .ps-line { background: var(--adv-blue-m); }

.ps-item.has-error .ps-num { border-color: var(--adv-red); background: var(--adv-red); color: #fff; animation: shake .35s ease; }
.ps-item.has-error .ps-label { color: var(--adv-red); }
.ps-item.has-error { background: var(--adv-red-l); }
.ps-item.has-error .ps-line { background: var(--adv-red-m); }

/* Badges */
.ps-badges { display: flex; align-items: center; flex-shrink: 0; padding-top: 5px; }
.ps-badge-ok  { width: 15px; height: 15px; border-radius: 50%; background: var(--adv-blue); color: #fff; font-size: .55rem; display: none; align-items: center; justify-content: center; }
.ps-badge-err { width: 8px; height: 8px; border-radius: 50%; background: var(--adv-red); display: none; }
.ps-item.done .ps-badge-ok { display: flex; }
.ps-item.has-error .ps-badge-err { display: block; }
.ps-item.has-error .ps-badge-ok { display: none; }

/* Footer progreso — distribución mejorada */
.ps-footer {
    margin-top: 1.1rem;
    padding: .9rem .8rem .75rem;
    border-top: 1px solid var(--border);
    background: var(--adv-slate-l);
    border-radius: var(--r);
}
.ps-footer-row {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: .55rem;
    gap: .5rem;
}
.ps-footer-row .ps-f-label { font-size: .58rem; font-weight: 800; text-transform: uppercase; letter-spacing: .09em; color: var(--ink-4); }
.ps-footer-row .ps-f-count { font-family: 'JetBrains Mono', monospace; font-size: .82rem; font-weight: 800; color: var(--adv-blue); }
.ps-bar { height: 6px; background: var(--border); border-radius: 999px; overflow: hidden; }
.ps-fill { height: 100%; background: linear-gradient(90deg, var(--adv-blue) 0%, var(--adv-azure) 70%, var(--adv-amber) 100%); border-radius: 999px; transition: width .5s cubic-bezier(.4,0,.2,1); width: 0%; }
.ps-pct {
    font-size: .68rem; font-weight: 800; color: var(--adv-blue);
    text-align: center;
    margin-top: .45rem;
    font-family: 'JetBrains Mono', monospace;
}

@keyframes shake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-3px)} 75%{transform:translateX(3px)} }

/* ═══════════════════════════════
   SUMMARY PANEL — independiente, sin progreso interno
═══════════════════════════════ */
.summary-panel-inner {
    background: var(--adv-navy);
    border-radius: var(--r-xl);
    padding: 1.3rem 1.4rem;
    box-shadow: var(--sh-lg);
    overflow: hidden;
    position: relative;
}
.summary-panel-inner::before {
    content: ''; position: absolute; top: -40px; right: -20px;
    width: 150px; height: 150px;
    background: radial-gradient(circle, rgba(245,158,11,.15) 0%, transparent 70%); pointer-events: none;
}
.sp-title {
    font-size: .58rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase;
    color: rgba(255,255,255,.4);
    margin-bottom: 1rem;
    padding-bottom: .7rem;
    border-bottom: 1px solid rgba(255,255,255,.08);
    display: flex; align-items: center; gap: .4rem;
    justify-content: center;
}
.sp-item { margin-bottom: .9rem; position: relative; z-index: 1; }
.sp-item:last-child { margin-bottom: 0; }
.sp-label { font-size: .58rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,255,255,.38); margin-bottom: .3rem; }
.sp-value { font-family: 'JetBrains Mono', monospace; font-size: 1.35rem; font-weight: 500; color: #fff; letter-spacing: -.02em; line-height: 1; }
.sp-value.amber { color: var(--adv-amber-xm); }
.sp-value.red   { color: #FCA5A5; }
.sp-divider { height: 1px; background: rgba(255,255,255,.08); margin: .8rem 0; }

.sp-status-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 5px 12px; border-radius: 999px; font-size: .7rem; font-weight: 700;
    background: rgba(245,158,11,.15); color: var(--adv-amber-xm); border: 1px solid rgba(245,158,11,.25); margin-top: .55rem;
}
.sp-status-badge.paid { background: rgba(26,86,219,.2); color: #93C5FD; border-color: rgba(26,86,219,.4); }
.sp-pax-count { font-size: .7rem; color: rgba(255,255,255,.4); margin-top: .35rem; }

/* ═══════════════════════════════
   FORM BLOCKS — con indicador numérico
═══════════════════════════════ */
.fb {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--r-xl); margin-bottom: 1rem;
    overflow: hidden; box-shadow: var(--sh); transition: border-color .2s, box-shadow .2s;
    position: relative;
}
.fb.is-complete { border-color: var(--adv-blue-m); }
.fb.has-errors  { border-color: var(--adv-red-m); box-shadow: var(--sh), 0 0 0 3px rgba(220,38,38,.07); }

.fb-num-badge {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--adv-blue);
    color: #fff;
    font-size: .7rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(26,86,219,.3);
    z-index: 10; flex-shrink: 0;
    transition: background .25s, transform .2s;
}
.fb.is-complete .fb-num-badge {
    background: var(--adv-blue);
    box-shadow: 0 2px 8px rgba(26,86,219,.35);
}
.fb.has-errors .fb-num-badge {
    background: var(--adv-red);
    box-shadow: 0 2px 8px rgba(220,38,38,.35);
}

.fb-head {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(135deg, #FAFBFD 0%, #F6F8FB 100%);
    display: flex; align-items: center; gap: .85rem;
    padding-right: 3rem;
}
.fb-ico { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.fb-ico.blue  { background: var(--adv-blue-l); color: var(--adv-blue); }
.fb-ico.amber { background: var(--adv-amber-l); color: var(--adv-amber-d); }
.fb-ico.navy  { background: #E8EEF8; color: var(--adv-navy); }
.fb-titles h3 { font-size: .9rem; font-weight: 800; color: var(--ink); letter-spacing: -.01em; }
.fb-titles p  { font-size: .7rem; color: var(--ink-4); margin-top: 2px; }

.fb-status { display: none; }
.fb-body { padding: 1.5rem; }

/* ═══════════════════════════════
   SECTION SUBTITLES
═══════════════════════════════ */
.st {
    font-size: .62rem; font-weight: 800; text-transform: uppercase;
    letter-spacing: .1em; color: var(--adv-blue);
    padding-bottom: .55rem; border-bottom: 2px solid var(--adv-blue-l);
    margin: 1.5rem 0 1rem;
    display: flex; align-items: center; gap: .4rem;
}
.st:first-child { margin-top: 0; }
.st::before { content: ''; width: 3px; height: 11px; background: var(--adv-amber); border-radius: 2px; display: inline-block; flex-shrink: 0; }

/* ═══════════════════════════════
   FIELDS
═══════════════════════════════ */
.field { margin-bottom: 1rem; }
.field:last-child { margin-bottom: 0; }
.lbl { display: block; font-size: .63rem; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; color: var(--ink-3); margin-bottom: .4rem; line-height: 1; }
.lbl .req { color: var(--adv-red); margin-left: 2px; }
.lbl .opt { color: var(--ink-4); font-weight: 500; text-transform: none; letter-spacing: 0; font-size: .65rem; margin-left: 4px; }
.fi {
    width: 100%; padding: 9px 12px;
    border: 1.5px solid var(--border); border-radius: var(--r);
    font-size: .875rem; font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--ink); background: var(--surface); outline: none;
    transition: border-color .15s, box-shadow .15s, background .15s;
    -webkit-appearance: none; appearance: none; line-height: 1.4;
}
.fi:focus { border-color: var(--adv-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); background: #FAFCFF; }
.fi::placeholder { color: var(--ink-4); font-size: .83rem; }
.fi.err { border-color: var(--adv-red); background: var(--adv-red-l); }
.fi.ok-val { border-color: var(--adv-blue); background: var(--adv-blue-l); }
textarea.fi { resize: vertical; min-height: 76px; line-height: 1.55; }
select.fi {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 11 11'%3E%3Cpath fill='%2394A3B8' d='M5.5 7.5L1 3h9z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 11px center; padding-right: 30px;
}
.fi[readonly] { background: var(--adv-slate-l); cursor: not-allowed; color: var(--ink-3); }
.ferr { font-size: .68rem; color: var(--adv-red); margin-top: .3rem; font-weight: 700; display: flex; align-items: center; gap: .25rem; }
.fhint { font-size: .68rem; color: var(--ink-4); margin-top: .28rem; line-height: 1.45; }
.fcalc { font-size: .7rem; color: var(--adv-blue); margin-top: .28rem; font-weight: 700; display: flex; align-items: center; gap: .25rem; font-family: 'JetBrains Mono', monospace; }

/* INPUT GROUP */
.ig {
    display: flex;
    align-items: stretch;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    transition: border-color .15s, box-shadow .15s;
    height: 40px;
}
.ig:focus-within { border-color: var(--adv-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.ig.err-group { border-color: var(--adv-red); }
.ig .ia {
    padding: 0 11px;
    background: var(--adv-slate-l);
    color: var(--ink-3);
    font-size: .78rem; font-weight: 700;
    display: flex; align-items: center; gap: .3rem;
    border-right: 1.5px solid var(--border);
    white-space: nowrap; flex-shrink: 0;
    line-height: 1;
}
.ig .ia .bi { display: flex; align-items: center; line-height: 1; }
.ig .fi {
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    flex: 1;
    min-width: 0;
    background: transparent !important;
    height: 100%;
    padding-top: 0;
    padding-bottom: 0;
    display: flex;
    align-items: center;
}
.ig textarea.fi { height: auto; padding-top: 8px; padding-bottom: 8px; }

/* ═══════════════════════════════
   DNI LOOKUP
═══════════════════════════════ */
.dni-wrap { position: relative; }
.dni-row {
    display: flex; align-items: stretch; gap: 0;
    border: 1.5px solid var(--border); border-radius: var(--r);
    overflow: hidden; transition: border-color .15s, box-shadow .15s;
    height: 40px;
}
.dni-row:focus-within { border-color: var(--adv-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.dni-row .ia {
    padding: 0 11px; background: var(--adv-slate-l);
    color: var(--ink-3); font-size: .78rem; font-weight: 700;
    display: flex; align-items: center; gap: .3rem;
    border-right: 1.5px solid var(--border); white-space: nowrap; flex-shrink: 0;
}
.dni-row .fi {
    border: none !important; box-shadow: none !important;
    border-radius: 0 !important; flex: 1; min-width: 0;
    background: transparent !important; height: 100%;
    padding-top: 0; padding-bottom: 0;
}
.btn-dni-lookup {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: 0 16px; background: var(--adv-blue); color: #fff;
    border: none; border-left: 1.5px solid var(--adv-blue);
    font-size: .78rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; white-space: nowrap; flex-shrink: 0; transition: background .15s;
    height: 100%;
}
.btn-dni-lookup:hover { background: var(--adv-navy); }
.btn-dni-lookup:disabled { opacity: .5; cursor: not-allowed; }
.dni-result { display: none; align-items: center; gap: .55rem; padding: .6rem .9rem; border-radius: var(--r); margin-top: .6rem; font-size: .8rem; font-weight: 600; }
.dni-result.visible { display: flex; }
.dni-result.ok   { background: var(--adv-blue-l); color: var(--adv-blue); border: 1.5px solid var(--adv-blue-m); }
.dni-result.err  { background: var(--adv-red-l); color: var(--adv-red); border: 1.5px solid var(--adv-red-m); }
.dni-result.load { background: var(--adv-slate-l); color: var(--ink-4); border: 1.5px solid var(--border); }

/* AGE BADGE */
.age-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 6px 14px; border-radius: 999px; font-size: .8rem; font-weight: 800;
    background: var(--adv-amber-l); color: var(--adv-amber-d);
    border: 2px solid var(--adv-amber-m); margin-top: .5rem;
    box-shadow: 0 2px 8px rgba(245,158,11,.25);
    animation: fadeUp .3s ease;
}
.age-badge .age-num { font-family: 'JetBrains Mono', monospace; font-size: 1.1rem; color: var(--adv-amber-d); }

/* ═══════════════════════════════
   GRIDS
═══════════════════════════════ */
.g2  { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.g3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
.g4  { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem; }
.g12 { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; }
@media (max-width: 640px) { .g2,.g3,.g4,.g12 { grid-template-columns: 1fr; } }

/* ═══════════════════════════════
   ESTADO SELECTOR
═══════════════════════════════ */
.estado-inline { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem; }
.estado-inline-label { font-size: .63rem; font-weight: 800; letter-spacing: .07em; text-transform: uppercase; color: var(--ink-3); white-space: nowrap; }
.eo-compact {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: 5px 14px;
    border: 1.5px solid var(--border); border-radius: 999px;
    cursor: pointer; font-size: .75rem; font-weight: 700;
    color: var(--ink-3); background: var(--surface);
    transition: all .15s; user-select: none; white-space: nowrap;
}
.eo-compact input { display: none; }
.eo-compact .eo-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--border); transition: background .15s; flex-shrink: 0; }
.eo-compact:hover { border-color: var(--adv-blue-m); color: var(--ink-2); }
.eo-compact.e-mitad.sel  { border-color: var(--adv-amber);  background: var(--adv-amber-l);  color: var(--adv-amber-d); }
.eo-compact.e-mitad.sel  .eo-dot { background: var(--adv-amber); }
.eo-compact.e-pagado.sel { border-color: var(--adv-blue);   background: var(--adv-blue-l);   color: var(--adv-blue); }
.eo-compact.e-pagado.sel .eo-dot { background: var(--adv-blue); }

/* ═══════════════════════════════
   PILLS
═══════════════════════════════ */
.pg { display: flex; gap: .4rem; flex-wrap: wrap; }
.pill {
    display: flex; align-items: center; gap: .35rem;
    padding: 5px 12px; border: 1.5px solid var(--border); border-radius: 999px;
    cursor: pointer; font-size: .74rem; font-weight: 600; color: var(--ink-3);
    transition: all .15s; user-select: none;
}
.pill input { display: none; }
.pill:hover { border-color: var(--adv-blue); color: var(--adv-blue); }
.pill.sel { border-color: var(--adv-blue); background: var(--adv-blue-l); color: var(--adv-blue); font-weight: 800; }

/* ═══════════════════════════════
   SWITCH TOGGLE — Sí/No pills
═══════════════════════════════ */
.sw-group {
    display: flex; align-items: center; gap: .5rem;
}
.sw-btn {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: 6px 16px; border-radius: 999px;
    border: 1.5px solid var(--border);
    font-size: .75rem; font-weight: 700;
    cursor: pointer; transition: all .15s; user-select: none;
    color: var(--ink-3); background: var(--surface);
}
.sw-btn input { display: none; }
.sw-btn.sw-no:hover  { border-color: var(--adv-slate-2); color: var(--ink-2); }
.sw-btn.sw-si:hover  { border-color: var(--adv-amber); color: var(--adv-amber-d); }
.sw-btn.sw-no.sel  { border-color: var(--adv-green); background: var(--adv-green-l); color: var(--adv-green); }
.sw-btn.sw-si.sel  { border-color: var(--adv-amber); background: var(--adv-amber-l); color: var(--adv-amber-d); }

/* ═══════════════════════════════
   TOGGLE SWITCH — binario (on/off)
═══════════════════════════════ */
.toggle-switch-wrap {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; padding: .85rem 1rem;
    border-radius: var(--r); margin-bottom: .85rem;
}
.toggle-switch-wrap.blue-bg {
    background: var(--adv-blue-l); border: 1.5px solid var(--adv-blue-m);
}
.toggle-switch-wrap.slate-bg {
    background: var(--adv-slate-l); border: 1.5px solid var(--border);
}
.toggle-switch-text {
    font-size: .8rem; font-weight: 700; display: flex; align-items: center; gap: .45rem;
}
.toggle-switch-text.blue { color: var(--adv-blue); }
.toggle-switch-text.slate { color: var(--ink-3); }

.toggle-knob {
    position: relative; display: inline-flex; align-items: center;
    width: 44px; height: 24px; flex-shrink: 0;
}
.toggle-knob input { opacity: 0; width: 0; height: 0; position: absolute; }
.toggle-slider {
    position: absolute; inset: 0; background: var(--border); border-radius: 999px;
    cursor: pointer; transition: background .2s;
}
.toggle-slider::before {
    content: ''; position: absolute; width: 18px; height: 18px;
    background: white; border-radius: 50%;
    top: 3px; left: 3px; transition: transform .2s;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
}
.toggle-knob input:checked + .toggle-slider { background: var(--adv-blue); }
.toggle-knob input:checked + .toggle-slider::before { transform: translateX(20px); }

/* alias para compatibilidad con código anterior */
.solo-banner { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .85rem 1rem; background: var(--adv-blue-l); border: 1.5px solid var(--adv-blue-m); border-radius: var(--r); margin-bottom: .85rem; }
.solo-banner-text { font-size: .8rem; font-weight: 700; color: var(--adv-blue); display: flex; align-items: center; gap: .45rem; }
.solo-toggle { position: relative; display: inline-flex; align-items: center; width: 44px; height: 24px; flex-shrink: 0; }
.solo-toggle input { opacity: 0; width: 0; height: 0; position: absolute; }
.solo-slider { position: absolute; inset: 0; background: var(--border); border-radius: 999px; cursor: pointer; transition: background .2s; }
.solo-slider::before { content: ''; position: absolute; width: 18px; height: 18px; background: white; border-radius: 50%; top: 3px; left: 3px; transition: transform .2s; box-shadow: 0 1px 4px rgba(0,0,0,.2); }
.solo-toggle input:checked + .solo-slider { background: var(--adv-blue); }
.solo-toggle input:checked + .solo-slider::before { transform: translateX(20px); }

/* ═══════════════════════════════
   PASAJEROS
═══════════════════════════════ */
.pax-card {
    background: var(--adv-slate-l); border: 1.5px solid var(--border);
    border-radius: var(--r); padding: 1rem; margin-bottom: .65rem;
    animation: fadeUp .2s ease; position: relative;
}
.pax-card.pax-incomplete { border-color: var(--adv-red-m); }
.pax-head {
    font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em;
    color: var(--ink-3); display: flex; justify-content: space-between;
    align-items: center; margin-bottom: .85rem;
}
.pax-del {
    width: 26px; height: 26px; border: 1.5px solid var(--border);
    border-radius: 6px; background: white; color: var(--ink-3);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    font-size: .75rem; transition: all .15s;
}
.pax-del:hover { border-color: var(--adv-red); color: var(--adv-red); background: var(--adv-red-l); }
.btn-add {
    width: 100%; padding: 10px; border: 2px dashed var(--border);
    border-radius: var(--r); background: transparent; color: var(--ink-3);
    font-size: .8rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    gap: .4rem; transition: all .15s; margin-top: .4rem;
}
.btn-add:hover { border-color: var(--adv-blue); color: var(--adv-blue); background: var(--adv-blue-l); }

/* ═══════════════════════════════
   SALUD — switch principal + layout
═══════════════════════════════ */
.salud-block {
    background: var(--surface); border: 1.5px solid var(--border);
    border-radius: var(--r); overflow: hidden; margin-bottom: .65rem;
}
.salud-head {
    padding: .6rem 1rem; background: var(--adv-slate-l);
    border-bottom: 1px solid var(--border); font-size: .7rem; font-weight: 700;
    color: var(--ink-2); display: flex; align-items: center; gap: .4rem;
}
.salud-dot { width: 9px; height: 9px; border-radius: 50%; background: var(--adv-blue); margin-left: auto; flex-shrink: 0; transition: background .3s; }
.salud-block.error .salud-dot { background: var(--adv-red); }
.salud-block.ok    .salud-dot { background: var(--adv-blue); }
.salud-body { padding: 1rem; }

/* Salud grid — alineación correcta entre columnas */
.salud-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    align-items: start;
}
@media (max-width: 640px) { .salud-grid { grid-template-columns: 1fr; } }

/* Columna de cada lado del grid */
.salud-col {
    display: flex;
    flex-direction: column;
}

/* Restricciones alimentarias — textarea ocupa todo el alto disponible */
.salud-col .lbl {
    margin-bottom: .5rem;
}

/* Alergias expand — alineado correctamente */
.alerg-expand {
    margin-top: .5rem;
    padding: .8rem;
    background: var(--adv-amber-l);
    border: 1.5px solid var(--adv-amber-m);
    border-radius: var(--r);
    animation: fadeUp .2s ease;
    width: 100%;
}
.alerg-expand .lbl {
    color: var(--adv-amber-d);
    font-size: .6rem;
    margin-bottom: .45rem;
}
.alerg-expand .fi {
    background: white;
    width: 100%;
    padding: 9px 12px;
    min-height: 70px;
    resize: vertical;
    line-height: 1.5;
    font-size: .85rem;
}

/* Restricciones — textarea con misma estética */
.salud-col textarea.fi {
    min-height: 90px;
    line-height: 1.5;
}

/* ═══════════════════════════════
   EMAIL WIDGET
═══════════════════════════════ */
.email-wrap { position: relative; }
.email-row {
    display: flex; align-items: stretch;
    border: 1.5px solid var(--border); border-radius: var(--r);
    overflow: hidden; transition: border-color .15s, box-shadow .15s;
    height: 40px;
}
.email-row:focus-within { border-color: var(--adv-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.email-row .ea {
    padding: 0 10px; background: var(--adv-slate-l); color: var(--ink-3);
    font-size: .78rem; font-weight: 700; display: flex; align-items: center;
    border-right: 1.5px solid var(--border); white-space: nowrap; flex-shrink: 0;
    gap: .3rem;
}
.email-row .ea .bi { display: flex; align-items: center; line-height: 1; }
.email-row .email-user { flex: 0 0 auto; min-width: 80px; max-width: 150px; border: none !important; box-shadow: none !important; border-radius: 0 !important; background: transparent !important; height: 100%; padding-top: 0; padding-bottom: 0; }
.email-row .at-sign { padding: 0 5px; display: flex; align-items: center; color: var(--ink-4); font-size: .9rem; font-weight: 700; flex-shrink: 0; user-select: none; }
.email-row .email-domain { flex: 1; border: none !important; box-shadow: none !important; border-radius: 0 !important; padding-left: 0; min-width: 0; -webkit-appearance: none; background: transparent !important; height: 100%; padding-top: 0; padding-bottom: 0; }
.domain-list { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: white; border: 1.5px solid var(--border); border-radius: var(--r); box-shadow: var(--sh-md); z-index: 200; overflow: hidden; }
.domain-list.open { display: block; }
.domain-list li { padding: 8px 13px; font-size: .82rem; color: var(--ink-2); cursor: pointer; display: flex; align-items: center; gap: .5rem; transition: background .1s; list-style: none; }
.domain-list li:hover { background: var(--adv-blue-l); color: var(--adv-blue); }

/* ═══════════════════════════════
   WHATSAPP LABEL
═══════════════════════════════ */
.lbl-wa {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: .63rem; font-weight: 800;
    letter-spacing: .07em; text-transform: uppercase;
    color: var(--ink-3); margin-bottom: .4rem; line-height: 1;
}
.lbl-wa svg { flex-shrink: 0; display: block; }
.lbl-wa .req { color: var(--adv-red); margin-left: 2px; }

/* ═══════════════════════════════
   NOTIFICACIONES
═══════════════════════════════ */
.notif-row { display: flex; gap: .6rem; flex-wrap: wrap; }
.notif-item { display: flex; align-items: center; gap: .5rem; padding: 8px 14px; border: 1.5px solid var(--border); border-radius: var(--r); cursor: pointer; font-size: .8rem; font-weight: 700; color: var(--ink-3); background: var(--surface); transition: all .15s; user-select: none; min-width: 140px; }
.notif-item input { display: none; }
.notif-box { width: 17px; height: 17px; border: 2px solid var(--border); border-radius: 5px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: transparent; transition: all .15s; }
.notif-item:hover { border-color: var(--adv-blue); color: var(--ink-2); }
.notif-item.checked { border-color: var(--adv-blue); background: var(--adv-blue-l); color: var(--adv-blue); }
.notif-item.checked .notif-box { border-color: var(--adv-blue); background: var(--adv-blue); color: #fff; font-size: .8rem; }

/* ═══════════════════════════════
   UPLOAD VOUCHER
═══════════════════════════════ */
.upload-zone { border: 2px dashed var(--border); border-radius: var(--r); padding: 1.5rem; text-align: center; cursor: pointer; transition: all .2s; background: var(--adv-slate-l); position: relative; }
.upload-zone:hover, .upload-zone.over { border-color: var(--adv-blue); background: var(--adv-blue-l); }
.upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-zone .uz-icon { font-size: 1.75rem; color: var(--ink-4); margin-bottom: .35rem; }
.upload-zone .uz-text { font-size: .8rem; color: var(--ink-3); font-weight: 600; }
.upload-zone .uz-sub  { font-size: .67rem; color: var(--ink-4); margin-top: .15rem; }
.file-preview { display: none; margin-top: .65rem; border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; }
.file-preview.visible { display: block; }
.file-preview img { width: 100%; max-height: 180px; object-fit: contain; background: var(--adv-slate-l); display: block; }
.file-preview-bar { background: var(--adv-navy); padding: .45rem .85rem; display: flex; justify-content: space-between; align-items: center; }
.file-preview-bar .fn { font-size: .68rem; color: rgba(255,255,255,.65); font-family: 'JetBrains Mono', monospace; }
.file-preview-bar .fr { background: none; border: none; color: rgba(255,255,255,.4); cursor: pointer; font-size: .68rem; transition: color .15s; }
.file-preview-bar .fr:hover { color: #FCA5A5; }

/* ═══════════════════════════════
   ALERTAS
═══════════════════════════════ */
.alerta { display: flex; gap: .7rem; align-items: flex-start; border-radius: var(--r); padding: .8rem .95rem; margin-bottom: .9rem; }
.alerta.amber { background: var(--adv-amber-l); border: 1.5px solid var(--adv-amber-m); border-left: 3px solid var(--adv-amber); }
.alerta.blue  { background: var(--adv-blue-l);  border: 1.5px solid var(--adv-blue-m);  border-left: 3px solid var(--adv-blue); }
.alerta .ai { font-size: .95rem; flex-shrink: 0; margin-top: .1rem; }
.alerta.amber .ai { color: var(--adv-amber); }
.alerta.blue  .ai { color: var(--adv-blue); }
.alerta .at { font-size: .77rem; color: var(--ink-2); line-height: 1.5; }
.alerta .at strong { display: block; margin-bottom: .1rem; font-weight: 800; }

/* ═══════════════════════════════
   POLÍTICAS
═══════════════════════════════ */
.politica-btns { display: flex; gap: .65rem; flex-wrap: wrap; margin-bottom: 1rem; }
.btn-politica { display: inline-flex; align-items: center; gap: .5rem; padding: 9px 18px; border: 2px solid var(--adv-blue-m); border-radius: var(--r); background: var(--adv-blue-l); color: var(--adv-blue); font-size: .8rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; transition: all .2s; }
.btn-politica:hover { background: var(--adv-blue); color: #fff; border-color: var(--adv-blue); transform: translateY(-1px); box-shadow: var(--sh-md); }
.btn-politica.active { background: var(--adv-blue); color: #fff; border-color: var(--adv-navy); }

.send-btns { display: flex; gap: .65rem; flex-wrap: wrap; margin-top: 1rem; }
.btn-send { display: inline-flex; align-items: center; gap: .5rem; padding: 9px 18px; border: 2px solid var(--border); border-radius: var(--r); background: var(--surface); color: var(--ink-3); font-size: .8rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; transition: all .2s; }
.btn-send.wa { border-color: #25d366; color: #128C7E; background: #F0FFF4; }
.btn-send.wa:hover { background: #25d366; color: #fff; border-color: #128C7E; transform: translateY(-1px); box-shadow: var(--sh-md); }
.btn-send.gmail { border-color: #EA4335; color: #EA4335; background: #FEF2F2; }
.btn-send.gmail:hover { background: #EA4335; color: #fff; transform: translateY(-1px); box-shadow: var(--sh-md); }
.btn-send.pdf { border-color: var(--adv-blue-m); color: var(--adv-blue); background: var(--adv-blue-l); }
.btn-send.pdf:hover { background: var(--adv-blue); color: #fff; border-color: var(--adv-navy); transform: translateY(-1px); box-shadow: var(--sh-md); }
.send-note { font-size: .68rem; color: var(--ink-4); margin-top: .5rem; font-style: italic; }

.politica-badge { display: none; align-items: center; gap: .35rem; font-size: .67rem; font-weight: 700; color: var(--adv-blue); background: var(--adv-blue-l); border: 1px solid var(--adv-blue-m); border-radius: 999px; padding: 3px 9px; position: absolute; top: -10px; right: 8px; z-index: 1; }
.politica-badge.visible { display: inline-flex; }

/* ═══════════════════════════════
   BARRA DE SUBMIT
═══════════════════════════════ */
.sbar {
    background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-xl);
    padding: 1.1rem 1.5rem; display: flex; justify-content: space-between;
    align-items: center; box-shadow: var(--sh-lg); gap: 1rem; flex-wrap: wrap;
    position: sticky; bottom: 1.25rem; z-index: 50;
}
.sbar-left { display: flex; flex-direction: column; gap: .15rem; }
.sbar-label { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: var(--ink-4); }
.sbar-value { font-family: 'JetBrains Mono', monospace; font-size: 1.15rem; font-weight: 500; color: var(--ink); }
.sbar-value span { color: var(--adv-amber-d); font-size: .72rem; font-weight: 700; margin-left: .3rem; font-family: 'Plus Jakarta Sans', sans-serif; }
.sbar-actions { display: flex; gap: .6rem; align-items: center; flex-wrap: wrap; }

.btn-primary { background: var(--adv-amber); color: var(--adv-navy); border: none; border-radius: var(--r); padding: 10px 26px; font-size: .875rem; font-weight: 800; font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; display: inline-flex; align-items: center; gap: .45rem; transition: all .15s; letter-spacing: -.01em; }
.btn-primary:hover { background: var(--adv-amber-d); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 18px rgba(245,158,11,.35); }
.btn-primary:disabled { opacity: .55; cursor: not-allowed; transform: none; box-shadow: none; }
.btn-secondary { background: transparent; color: var(--ink-3); border: 1.5px solid var(--border); border-radius: var(--r); padding: 9px 18px; font-size: .875rem; font-weight: 600; font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; display: inline-flex; align-items: center; gap: .45rem; transition: all .15s; text-decoration: none; }
.btn-secondary:hover { border-color: var(--border-h); color: var(--ink); }

/* ═══════════════════════════════
   BLOQUE DE ERRORES
═══════════════════════════════ */
.lerr { background: var(--adv-red-l); border: 1.5px solid var(--adv-red-m); border-left: 4px solid var(--adv-red); border-radius: var(--r); padding: 1rem 1.2rem; margin-bottom: 1.25rem; font-size: .8rem; color: #7F1D1D; }
.lerr strong { display: block; margin-bottom: .4rem; font-weight: 800; font-size: .82rem; }
.lerr ul { padding-left: 1.25rem; }
.lerr li { margin-bottom: .18rem; line-height: 1.5; }

#campos-factura { display: none; }

/* SPINNER */
@keyframes spin { to { transform: rotate(360deg); } }
.spinner { display: inline-block; width: 11px; height: 11px; border: 2px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin .7s linear infinite; vertical-align: middle; margin-right: 3px; }

/* ANIMATIONS */
@keyframes fadeUp { from{opacity:0;transform:translateY(5px)} to{opacity:1;transform:translateY(0)} }
html { scroll-behavior: smooth; }

/* FLATPICKR */
.flatpickr-calendar { box-shadow: var(--sh-lg) !important; border-radius: var(--r-lg) !important; border: 1px solid var(--border) !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
.flatpickr-day.selected { background: var(--adv-blue) !important; border-color: var(--adv-blue) !important; }
.flatpickr-day.today { border-color: var(--adv-amber) !important; }

/* ═══════════════════════════════
   SALUD — switch principal banner
═══════════════════════════════ */
.salud-master-banner {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; padding: .9rem 1.1rem;
    background: var(--adv-slate-l); border: 1.5px solid var(--border);
    border-radius: var(--r); margin-bottom: 1rem;
    transition: background .2s, border-color .2s;
}
.salud-master-banner.has-conditions {
    background: var(--adv-amber-l); border-color: var(--adv-amber-m);
}
.salud-master-text {
    font-size: .8rem; font-weight: 700; color: var(--ink-3);
    display: flex; align-items: center; gap: .5rem; flex: 1;
}
.salud-master-banner.has-conditions .salud-master-text { color: var(--adv-amber-d); }
.salud-master-badge {
    font-size: .65rem; font-weight: 800; padding: 2px 9px;
    border-radius: 999px; background: var(--border); color: var(--ink-4);
    transition: background .2s, color .2s; white-space: nowrap;
}
.salud-master-banner.has-conditions .salud-master-badge {
    background: var(--adv-amber-m); color: var(--adv-amber-d);
}
</style>
@endpush

@section('contenido')
<div class="page-wrap">

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-inner">
        <div class="ph-brand">
            <div class="ph-logo"><i class="bi bi-compass"></i></div>
            <div class="ph-text">
                <div class="eyebrow"><i class="bi bi-airplane-fill"></i> Adventure &middot; Agencia de Viajes</div>
                <h1>Nueva Reserva</h1>
                <p>Completa todos los datos para crear y confirmar la reserva del grupo.</p>
            </div>
        </div>
        <a href="{{ route('reservas.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver a Reservas
        </a>
    </div>
</div>

{{-- PROGRESS SIDEBAR --}}
<aside class="progress-sidebar">
    <div class="ps-inner">
        <div class="ps-title"><i class="bi bi-list-check"></i> Progreso del formulario</div>
        <div class="ps-steps">
            @foreach([
                [1,'Titular','Datos personales'],
                [2,'Viaje','Servicio y fechas'],
                [3,'Pago','Comprobante'],
                [4,'Pasajeros','Grupo de viaje'],
                [5,'Salud','Info médica'],
                [6,'Logística','Encuentro y guía'],
                [7,'Políticas','Términos'],
            ] as [$n,$label,$sub])
            <div class="ps-item {{ $n===1?'active':'' }}" id="ps-{{ $n }}" onclick="scrollToBloque({{ $n }})">
                <div class="ps-connector-wrap">
                    <div class="ps-num">{{ $n }}</div>
                    <div class="ps-line"></div>
                </div>
                <div class="ps-info">
                    <span class="ps-label">{{ $label }}</span>
                    <span class="ps-sub">{{ $sub }}</span>
                </div>
                <div class="ps-badges">
                    <span class="ps-badge-ok"><i class="bi bi-check2" style="font-size:.55rem"></i></span>
                    <span class="ps-badge-err"></span>
                </div>
            </div>
            @endforeach
        </div>
        <div class="ps-footer">
            <div class="ps-footer-row">
                <span class="ps-f-label">Completado</span>
                <span class="ps-f-count" id="ps-done-count">0/7</span>
            </div>
            <div class="ps-bar"><div class="ps-fill" id="ps-fill"></div></div>
            <div class="ps-pct" id="ps-pct">0%</div>
        </div>
    </div>
</aside>

{{-- MAIN FORM --}}
<div class="form-main">

@if($errors->any())
<div class="lerr">
    <strong><i class="bi bi-exclamation-triangle me-1"></i> Corrige los errores antes de guardar:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif
@if(session('error'))
<div class="lerr">
    <strong><i class="bi bi-exclamation-triangle me-1"></i> Error al guardar:</strong>
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div style="background:var(--adv-green-l);border:1.5px solid var(--adv-green-m);border-left:4px solid var(--adv-green);border-radius:var(--r);padding:1rem 1.2rem;margin-bottom:1.25rem;color:var(--adv-green);font-weight:700;">
    <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
</div>
@endif
<form method="POST" action="{{ route('reservas.store') }}" enctype="multipart/form-data" id="form-reserva" novalidate>
@csrf

{{-- ══ BLOQUE 1 · TITULAR ══ --}}
<div class="fb" id="bloque-1">
    <div class="fb-num-badge" id="fb-status-1">1</div>
    <div class="fb-head">
        <div class="fb-ico blue"><i class="bi bi-person-badge"></i></div>
        <div class="fb-titles"><h3>Pasajero titular</h3><p>Datos personales y de contacto del responsable principal</p></div>
    </div>
    <div class="fb-body">
        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">

        <div class="st">Identificación</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="titular_tipo_documento">Tipo de doc.</label>
                <select name="titular_tipo_documento" id="titular_tipo_documento" class="fi" onchange="onTipoDocChange()">
                    <option value="DNI"       {{ old('titular_tipo_documento','DNI')=='DNI'       ?'selected':'' }}>DNI</option>
                    <option value="CE"        {{ old('titular_tipo_documento')=='CE'        ?'selected':'' }}>C. Extranjería</option>
                    <option value="PASAPORTE" {{ old('titular_tipo_documento')=='PASAPORTE' ?'selected':'' }}>Pasaporte</option>
                    <option value="RUC"       {{ old('titular_tipo_documento')=='RUC'       ?'selected':'' }}>RUC</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_numero_documento">Número de documento <span class="req">*</span></label>
                <div class="dni-wrap">
                    <div class="dni-row">
                        <span class="ia"><i class="bi bi-card-text"></i></span>
                        <input type="text" name="titular_numero_documento" id="titular_numero_documento"
                            value="{{ old('titular_numero_documento') }}"
                            class="fi" placeholder="Ingresa el número" maxlength="12"
                            inputmode="numeric"
                            oninput="onDocInput(this)"
                            data-bloque="1">
                        <button type="button" class="btn-dni-lookup" id="btn-lookup" onclick="buscarPorDoc()">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <div class="dni-result" id="dni-result"></div>
                </div>
                <div class="fhint">Ingresa el número y haz clic en Buscar para autocompletar desde SUNAT/RENIEC</div>
            </div>
        </div>

        <div class="st">Datos personales</div>
        <div class="field">
            <label class="lbl" for="titular_nombre">Nombre completo <span class="req">*</span></label>
            <div class="ig {{ $errors->has('titular_nombre')?'err-group':'' }}">
                <span class="ia"><i class="bi bi-person"></i></span>
                <input type="text" name="titular_nombre" id="titular_nombre"
                    value="{{ old('titular_nombre') }}"
                    class="fi {{ $errors->has('titular_nombre')?'err':'' }}"
                    placeholder="NOMBRES Y APELLIDOS COMPLETOS"
                    required maxlength="200"
                    oninput="this.value=this.value.toUpperCase();actualizarNombreTitularSalud(this.value);updateProgressSteps()"
                    data-validate="required" data-bloque="1">
            </div>
            @error('titular_nombre')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
        </div>

        <div class="g3">
            <div class="field">
                <label class="lbl" for="titular_fecha_nacimiento">Fecha de nacimiento</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar2-heart"></i></span>
                    <input type="text" id="titular_fecha_nacimiento"
                        class="fi" placeholder="DD/MM/AAAA" readonly>
                </div>
                <input type="hidden" name="titular_fecha_nacimiento" id="titular_fecha_nacimiento_iso"
                    value="{{ old('titular_fecha_nacimiento') }}">
                <div id="edad-badge"></div>
            </div>
            <div class="field">
                <label class="lbl" for="titular_genero">Género</label>
                <select name="titular_genero" id="titular_genero" class="fi">
                    <option value="">— Sin especificar —</option>
                    <option value="M"    {{ old('titular_genero')=='M'   ?'selected':'' }}>Masculino</option>
                    <option value="F"    {{ old('titular_genero')=='F'   ?'selected':'' }}>Femenino</option>
                    <option value="otro" {{ old('titular_genero')=='otro'?'selected':'' }}>Otro</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_nacionalidad">Nacionalidad</label>
                <input type="text" name="titular_nacionalidad" id="titular_nacionalidad"
                    value="{{ old('titular_nacionalidad','Peruana') }}"
                    class="fi" placeholder="Peruana, Americana..." maxlength="80">
            </div>
        </div>

        <div class="st">Contacto</div>
        <div class="g2">
            <div class="field">
                <label class="lbl-wa" for="titular_telefono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Celular / WhatsApp <span class="req">*</span>
                </label>
                <div class="ig {{ $errors->has('titular_telefono')?'err-group':'' }}">
                    <span class="ia">+51</span>
                    <input type="text" name="titular_telefono" id="titular_telefono"
                        value="{{ old('titular_telefono') }}"
                        class="fi {{ $errors->has('titular_telefono')?'err':'' }}"
                        placeholder="9XXXXXXXX" maxlength="9" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').substring(0,9);updateProgressSteps()"
                        required data-validate="required|phone" data-bloque="1">
                </div>
                @error('titular_telefono')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="fhint">9 dígitos &middot; Confirmación por WhatsApp</div>
            </div>

            <div class="field">
                <label class="lbl">Correo electrónico</label>
                <div class="email-wrap">
                    <div class="email-row">
                        <span class="ea"><i class="bi bi-envelope" style="color:var(--adv-blue)"></i></span>
                        <input type="text" id="email-user" class="fi email-user" placeholder="usuario" maxlength="80" autocomplete="off"
                            oninput="emailInput()" onfocus="emailInput()" onblur="setTimeout(closeDomains,200)"
                            onpaste="handleEmailPaste(event)">
                        <span class="at-sign">@</span>
                        <input type="text" id="email-domain" class="fi email-domain" placeholder="dominio.com" maxlength="80" autocomplete="off"
                            oninput="joinEmail()" onblur="setTimeout(closeDomains,200)">
                    </div>
                    <ul class="domain-list" id="domain-list"></ul>
                    <input type="hidden" name="titular_email" id="titular_email" value="{{ old('titular_email') }}">
                </div>
            </div>
        </div>

        <div class="field" style="max-width:280px">
            <label class="lbl">Teléfono secundario <span class="opt">(opcional)</span></label>
            <div class="ig">
                <span class="ia"><i class="bi bi-telephone"></i></span>
                <input type="text" name="titular_telefono2" value="{{ old('titular_telefono2') }}"
                    class="fi" placeholder="076-XXXXXX o 9XXXXXXXX" maxlength="15"
                    oninput="this.value=this.value.replace(/[^0-9\-]/g,'')">
            </div>
        </div>
    </div>
</div>

{{-- ══ BLOQUE 2 · VIAJE ══ --}}
<div class="fb" id="bloque-2">
    <div class="fb-num-badge" id="fb-status-2">2</div>
    <div class="fb-head">
        <div class="fb-ico amber"><i class="bi bi-airplane"></i></div>
        <div class="fb-titles"><h3>Datos del viaje</h3><p>Nombre del servicio, precio, fechas y canal de venta</p></div>
    </div>
    <div class="fb-body">
        <div class="st">Servicio</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="nombre_servicio">Nombre del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('nombre_tour')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-briefcase"></i></span>
                    <input type="text" name="nombre_tour" id="nombre_servicio"
                        value="{{ old('nombre_tour') }}"
                        class="fi {{ $errors->has('nombre_tour')?'err':'' }}"
                        placeholder="Ej: Paquete Lima - Cusco 5D/4N"
                        required maxlength="200"
                        data-validate="required" data-bloque="2">
                </div>
                @error('nombre_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl" for="precio_tour">Precio del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('precio_tour')?'err-group':'' }}">
                    <span class="ia">S/</span>
                    <input type="number" name="precio_tour" id="precio_tour"
                        value="{{ old('precio_tour') }}"
                        class="fi {{ $errors->has('precio_tour')?'err':'' }}"
                        step="0.01" min="0" placeholder="0.00"
                        required inputmode="decimal"
                        oninput="this.value=this.value.replace(/[^0-9.]/g,'');calcTotal()"
                        data-validate="required|numeric" data-bloque="2">
                </div>
                @error('precio_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="st">Fechas y canal</div>
        <div class="g3">
            <div class="field">
                <label class="lbl" for="fecha_tour_display">Fecha de salida <span class="req">*</span></label>
                <div class="ig {{ $errors->has('fecha_tour')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="text" id="fecha_tour_display"
                        class="fi {{ $errors->has('fecha_tour')?'err':'' }}"
                        placeholder="Seleccionar..." readonly data-bloque="2">
                </div>
                <input type="hidden" name="fecha_tour" id="fecha_tour" value="{{ old('fecha_tour') }}">
                @error('fecha_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl" for="hora_salida_display">Hora de salida <span class="req">*</span></label>
                <div class="ig {{ $errors->has('hora_salida')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="text" id="hora_salida_display"
                        class="fi {{ $errors->has('hora_salida')?'err':'' }}"
                        placeholder="00:00 AM" readonly data-bloque="2">
                </div>
                <input type="hidden" name="hora_salida" id="hora_salida" value="{{ old('hora_salida') }}">
                @error('hora_salida')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl" for="ciudad_procedencia">Ciudad de partida <span class="req">*</span></label>
                <div class="ig {{ $errors->has('ciudad_procedencia')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="ciudad_procedencia" id="ciudad_procedencia"
                        value="{{ old('ciudad_procedencia') }}"
                        class="fi {{ $errors->has('ciudad_procedencia')?'err':'' }}"
                        placeholder="Lima, Cusco, Cajamarca..."
                        required maxlength="100"
                        data-validate="required" data-bloque="2">
                </div>
                @error('ciudad_procedencia')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="field" style="max-width:320px">
            <label class="lbl" for="canal_contacto">Canal de venta <span class="req">*</span></label>
            <select name="canal_contacto" id="canal_contacto" class="fi" required data-validate="required" data-bloque="2">
                <option value="whatsapp"       {{ old('canal_contacto','whatsapp')=='whatsapp'?'selected':'' }}>WhatsApp</option>
                <option value="presencial"     {{ old('canal_contacto')=='presencial'?'selected':'' }}>Presencial</option>
                <option value="llamada"        {{ old('canal_contacto')=='llamada'?'selected':'' }}>Llamada telefónica</option>
                <option value="redes_sociales" {{ old('canal_contacto')=='redes_sociales'?'selected':'' }}>Redes Sociales</option>
                <option value="web"            {{ old('canal_contacto')=='web'?'selected':'' }}>Página web</option>
                <option value="referido"       {{ old('canal_contacto')=='referido'?'selected':'' }}>Referido / Recomendación</option>
            </select>
        </div>
    </div>
</div>

{{-- ══ BLOQUE 3 · PAGO Y COMPROBANTE ══ --}}
<div class="fb" id="bloque-3">
    <div class="fb-num-badge" id="fb-status-3">3</div>
    <div class="fb-head">
        <div class="fb-ico amber"><i class="bi bi-credit-card"></i></div>
        <div class="fb-titles"><h3>Pago y comprobante</h3><p>Método de pago, monto calculado y comprobante adjunto</p></div>
    </div>
    <div class="fb-body">
        <div class="alerta amber">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Adelanto del 50% requerido para confirmar.</strong>
                El monto de adelanto se calcula automáticamente desde el precio del servicio.
            </div>
        </div>

        <div class="st">Comprobante fiscal</div>
        <div class="g12">
            <div class="field">
                <label class="lbl" for="tipo_comprobante">Tipo <span class="req">*</span></label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="fi" required onchange="togFactura()" data-validate="required" data-bloque="3">
                    <option value="boleta"  {{ old('tipo_comprobante','boleta')=='boleta' ?'selected':'' }}>Boleta</option>
                    <option value="factura" {{ old('tipo_comprobante')=='factura' ?'selected':'' }}>Factura</option>
                </select>
            </div>
            <div id="campos-factura" style="display:none">
                <div class="g2">
                    <div class="field">
                        <label class="lbl" for="ruc_factura">RUC <span class="req">*</span></label>
                        <div class="dni-wrap">
                            <div class="dni-row">
                                <span class="ia">RUC</span>
                                <input type="text" name="ruc_factura" id="ruc_factura"
                                    value="{{ old('ruc_factura') }}"
                                    class="fi" placeholder="20XXXXXXXXX" maxlength="11"
                                    inputmode="numeric"
                                    oninput="onRucInput(this)">
                                <button type="button" class="btn-dni-lookup" id="btn-ruc-lookup" onclick="buscarRUC()">
                                    <i class="bi bi-search"></i> SUNAT
                                </button>
                            </div>
                            <div class="dni-result" id="ruc-result"></div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="lbl" for="razon_social">Razón social <span class="req">*</span></label>
                        <input type="text" name="razon_social" id="razon_social"
                            value="{{ old('razon_social') }}"
                            class="fi" placeholder="EMPRESA S.A.C." maxlength="200"
                            oninput="this.value=this.value.toUpperCase()">
                        <div class="fhint">Se autocompleta al buscar el RUC, o ingrésalo manualmente</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="st">Registro del pago</div>

        <div class="field">
            <label class="lbl">Estado de pago <span class="req">*</span></label>
            <div class="estado-inline">
                <label class="eo-compact e-mitad {{ old('estado_inicial','mitad_pago')=='mitad_pago'?'sel':'' }}" onclick="selEst(this)">
                    <input type="radio" name="estado_inicial" value="mitad_pago" {{ old('estado_inicial','mitad_pago')=='mitad_pago'?'checked':'' }}>
                    <span class="eo-dot"></span>
                    <i class="bi bi-hourglass-split"></i> 50% Pagado
                </label>
                <label class="eo-compact e-pagado {{ old('estado_inicial')=='pagado'?'sel':'' }}" onclick="selEst(this)">
                    <input type="radio" name="estado_inicial" value="pagado" {{ old('estado_inicial')=='pagado'?'checked':'' }}>
                    <span class="eo-dot"></span>
                    <i class="bi bi-patch-check"></i> Pagado completo
                </label>
            </div>
        </div>

        <div class="g3">
            <div class="field">
                <label class="lbl" for="metodo_pago">Método de pago <span class="req">*</span></label>
                <select name="metodo_pago" id="metodo_pago" class="fi"
                    onchange="updOpHint();updateProgressSteps()"
                    data-validate="required" data-bloque="3">
                    <option value="">Seleccionar...</option>
                    <optgroup label="Efectivo"><option value="efectivo" {{ old('metodo_pago')=='efectivo'?'selected':'' }}>Efectivo</option></optgroup>
                    <optgroup label="Pagos digitales">
                        <option value="yape"  {{ old('metodo_pago')=='yape' ?'selected':'' }}>Yape</option>
                        <option value="plin"  {{ old('metodo_pago')=='plin' ?'selected':'' }}>Plin</option>
                        <option value="tunki" {{ old('metodo_pago')=='tunki'?'selected':'' }}>Tunki</option>
                    </optgroup>
                    <optgroup label="Transferencia bancaria">
                        <option value="transf_bcp"   {{ old('metodo_pago')=='transf_bcp'  ?'selected':'' }}>Transf. BCP</option>
                        <option value="transf_bbva"  {{ old('metodo_pago')=='transf_bbva' ?'selected':'' }}>Transf. BBVA</option>
                        <option value="transf_inter" {{ old('metodo_pago')=='transf_inter'?'selected':'' }}>Transf. Interbank</option>
                        <option value="transf_sc"    {{ old('metodo_pago')=='transf_sc'   ?'selected':'' }}>Transf. Scotiabank</option>
                        <option value="transf_bn"    {{ old('metodo_pago')=='transf_bn'   ?'selected':'' }}>Transf. Banco Nación</option>
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
                @error('metodo_pago')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl" for="monto_pagado_inicial">Monto pagado (S/) <span class="req">*</span></label>
                <div class="ig {{ $errors->has('monto_pagado_inicial')?'err-group':'' }}">
                    <span class="ia">S/</span>
                    <input type="number" name="monto_pagado_inicial" id="monto_pagado_inicial"
                        value="{{ old('monto_pagado_inicial') }}"
                        class="fi {{ $errors->has('monto_pagado_inicial')?'err':'' }}"
                        step="0.01" min="0.01" placeholder="—" readonly
                        data-validate="required|numeric|positive" data-bloque="3">
                </div>
                <div class="fcalc" id="hint-monto"><i class="bi bi-calculator"></i> <span id="calc-monto-txt">Calculado automáticamente</span></div>
                @error('monto_pagado_inicial')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl">Fecha de pago</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" class="fi">
                </div>
            </div>
        </div>

        <div class="field" style="max-width:360px">
            <label class="lbl">N&deg; operación / referencia <span class="opt">(opcional)</span></label>
            <input type="text" name="numero_operacion" value="{{ old('numero_operacion') }}" class="fi" placeholder="Código de transacción..." maxlength="100">
            <div class="fhint" id="op-hint">Código visible en Yape, Plin o constancia bancaria</div>
        </div>

        <div class="st">Comprobante adjunto <span class="req">*</span></div>
        <div class="upload-zone" id="uz"
             ondragover="event.preventDefault();this.classList.add('over')"
             ondragleave="this.classList.remove('over')"
             ondrop="onDrop(event)">
            <input type="file" name="archivo_baucher" id="archivo_baucher" accept=".jpg,.jpeg,.png,.pdf,.webp" onchange="onFile(event)">
            <div class="uz-icon"><i class="bi bi-cloud-arrow-up"></i></div>
            <div class="uz-text">Arrastra aquí o <strong style="color:var(--adv-blue)">haz clic para seleccionar</strong></div>
            <div class="uz-sub">JPG &middot; PNG &middot; PDF &middot; WEBP &mdash; máx. 5 MB</div>
        </div>
        <div class="file-preview" id="fprev">
            <img id="prev-img" src="" alt="">
            <div class="file-preview-bar">
                <span class="fn" id="prev-name">—</span>
                <button type="button" class="fr" onclick="removeFile()"><i class="bi bi-x-circle me-1"></i> Quitar</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ BLOQUE 4 · PASAJEROS ══ --}}
<div class="fb" id="bloque-4">
    <div class="fb-num-badge" id="fb-status-4">4</div>
    <div class="fb-head">
        <div class="fb-ico blue"><i class="bi bi-people"></i></div>
        <div class="fb-titles"><h3>Pasajeros adicionales</h3><p>Integrantes del grupo, además del titular</p></div>
    </div>
    <div class="fb-body">

        {{-- Switch "Solo titular" — INICIA DESACTIVADO.
             Desactivado = se muestran pasajeros adicionales (estado por defecto)
             Activado    = solo viaja el titular (oculta sección) --}}
        <div class="solo-banner" id="solo-banner">
            <div class="solo-banner-text">
                <i class="bi bi-person-check"></i>
                Solo viaja el titular (sin pasajeros adicionales)
            </div>
            <label class="solo-toggle" title="Activar si solo viaja el titular">
                <input type="checkbox" id="solo-pasajero" name="solo_pasajero" value="1"
                    onchange="toggleSoloPasajero(this)"
                    {{ old('solo_pasajero') ? 'checked' : '' }}>
                <span class="solo-slider"></span>
            </label>
        </div>

        <div id="pax-seccion">
            <div class="alerta blue">
                <i class="bi bi-info-circle ai"></i>
                <div class="at">
                    <strong>El titular se incluye automáticamente como pasajero principal.</strong>
                    Agrega aquí a los demás viajeros del grupo.
                </div>
            </div>
            <div id="pax-lista"></div>
            <p id="pax-cnt" style="font-size:.74rem;color:var(--ink-4);margin-bottom:.35rem;"></p>
            <button type="button" class="btn-add" id="btn-add-pax" onclick="addPax()">
                <i class="bi bi-person-plus"></i> Agregar pasajero
            </button>
        </div>

        <div id="solo-msg" style="display:none; padding:.75rem 1rem; background:var(--adv-green-l); border:1.5px solid var(--adv-green-m); border-radius:var(--r); font-size:.8rem; color:var(--adv-green); font-weight:700;">
            <i class="bi bi-check-circle me-1"></i> Registrado como viajero único. No se requieren pasajeros adicionales.
        </div>
    </div>
</div>

{{-- ══ BLOQUE 5 · SALUD ══ --}}
<div class="fb" id="bloque-5">
    <div class="fb-num-badge" id="fb-status-5">5</div>
    <div class="fb-head">
        <div class="fb-ico amber"><i class="bi bi-heart-pulse"></i></div>
        <div class="fb-titles"><h3>Salud y seguridad</h3><p>Información médica de cada integrante del grupo</p></div>
    </div>
    <div class="fb-body">

        {{-- Switch principal de salud — INICIA DESACTIVADO
             Desactivado = SÍ HAY alergias/condiciones (muestra campos)
             Activado    = NO HAY alergias/condiciones (oculta campos)
             En estado inicial el bloque NO se marca completo hasta interactuar
             o llenar los campos. --}}
        <div class="salud-master-banner has-conditions" id="salud-master-banner">
            <div class="salud-master-text" id="salud-master-text">
                <i class="bi bi-exclamation-triangle" id="salud-master-icon"></i>
                <span id="salud-master-label">Algún pasajero tiene alergias o condiciones médicas</span>
                <span class="salud-master-badge" id="salud-master-badge">Con condiciones</span>
            </div>
            <label class="toggle-knob" title="Activar si NINGÚN pasajero tiene alergias o condiciones">
                <input type="checkbox" id="salud-master-toggle"
                    onchange="toggleSaludMaster(this)"
                    {{ old('salud_sin_condiciones') ? 'checked' : '' }}>
                <span class="toggle-slider"></span>
            </label>
        </div>
        {{-- Hidden: 1 = NO tiene condiciones (switch ON), 0 = SÍ tiene condiciones (switch OFF) --}}
        <input type="hidden" name="salud_sin_condiciones" id="salud-sin-condiciones-hidden"
            value="{{ old('salud_sin_condiciones', '0') }}">

        {{-- Contenido de salud — visible cuando el switch está DESACTIVADO --}}
        <div id="salud-lista">
            {{-- Bloque salud del TITULAR --}}
            <div class="salud-block" id="salud-titular">
                <div class="salud-head">
                    <i class="bi bi-person-badge" style="color:var(--adv-blue)"></i>
                    <span>Titular — <span id="salud-titular-nombre" style="font-style:italic;color:var(--ink-4)">nombre del titular</span></span>
                    <span class="salud-dot"></span>
                </div>
                <div class="salud-body">
                    <div class="salud-grid">
                        {{-- Columna izquierda: Alergias --}}
                        <div class="salud-col">
                            <label class="lbl">Alergias o condiciones médicas</label>
                            <div class="sw-group" style="margin-bottom:.6rem">
                                <label class="sw-btn sw-no {{ old('titular_tiene_alergias','no')=='no'?'sel':'' }}" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="no" {{ old('titular_tiene_alergias','no')=='no'?'checked':'' }}>
                                    <i class="bi bi-check-circle"></i> No tiene
                                </label>
                                <label class="sw-btn sw-si {{ old('titular_tiene_alergias')=='si'?'sel':'' }}" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="si" {{ old('titular_tiene_alergias')=='si'?'checked':'' }}>
                                    <i class="bi bi-exclamation-triangle"></i> Sí tiene
                                </label>
                            </div>
                            <div id="alerg-titular-wrap" style="{{ old('titular_tiene_alergias')=='si'?'':'display:none' }}">
                                <div class="alerg-expand">
                                    <label class="lbl"><i class="bi bi-exclamation-triangle me-1"></i>Detalla las alergias</label>
                                    <textarea name="titular_alergias_detalle" id="alerg-titular"
                                        class="fi" rows="3"
                                        placeholder="Medicamentos, alimentos, materiales...">{{ old('titular_alergias_detalle') }}</textarea>
                                </div>
                            </div>
                        </div>
                        {{-- Columna derecha: Restricciones alimentarias --}}
                        <div class="salud-col">
                            <label class="lbl">Restricciones alimentarias</label>
                            <textarea name="titular_restricciones" class="fi" rows="3" placeholder="Vegetariano, vegano, sin gluten...">{{ old('titular_restricciones') }}</textarea>
                        </div>
                    </div>

                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid var(--border)">
                        <label class="lbl" style="margin-bottom:.65rem">
                            <i class="bi bi-activity me-1" style="color:var(--adv-blue)"></i>
                            Condición física / Movilidad
                        </label>
                        <div class="g2" style="gap:.75rem">
                            <div>
                                <label class="lbl" style="font-size:.6rem; color:var(--ink-4); margin-bottom:.35rem">Dificultad para caminar o moverse</label>
                                <div class="sw-group">
                                    <label class="sw-btn sw-no {{ old('titular_dificultad_movilidad','no')=='no'?'sel':'' }}" onclick="togSw(this,'titular_dificultad_movilidad')">
                                        <input type="radio" name="titular_dificultad_movilidad" value="no" {{ old('titular_dificultad_movilidad','no')=='no'?'checked':'' }}>
                                        <i class="bi bi-x-circle"></i> No
                                    </label>
                                    <label class="sw-btn sw-si {{ old('titular_dificultad_movilidad')=='si'?'sel':'' }}" onclick="togSw(this,'titular_dificultad_movilidad')">
                                        <input type="radio" name="titular_dificultad_movilidad" value="si" {{ old('titular_dificultad_movilidad')=='si'?'checked':'' }}>
                                        <i class="bi bi-check-circle"></i> Sí
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="lbl" style="font-size:.6rem; color:var(--ink-4); margin-bottom:.35rem">Usa medicación regular</label>
                                <div class="sw-group">
                                    <label class="sw-btn sw-no {{ old('titular_usa_medicacion','no')=='no'?'sel':'' }}" onclick="togSw(this,'titular_usa_medicacion')">
                                        <input type="radio" name="titular_usa_medicacion" value="no" {{ old('titular_usa_medicacion','no')=='no'?'checked':'' }}>
                                        <i class="bi bi-x-circle"></i> No
                                    </label>
                                    <label class="sw-btn sw-si {{ old('titular_usa_medicacion')=='si'?'sel':'' }}" onclick="togSw(this,'titular_usa_medicacion')">
                                        <input type="radio" name="titular_usa_medicacion" value="si" {{ old('titular_usa_medicacion')=='si'?'checked':'' }}>
                                        <i class="bi bi-check-circle"></i> Sí
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field" style="margin-top:.75rem;margin-bottom:0">
                        <label class="lbl">Observaciones médicas adicionales</label>
                        <textarea name="titular_obs_medicas" class="fi" rows="2" placeholder="Discapacidades, movilidad reducida...">{{ old('titular_obs_medicas') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mensaje cuando el switch está activado (sin condiciones) --}}
        <div id="salud-confirm-msg" style="display:none; padding:.75rem 1rem; background:var(--adv-green-l); border:1.5px solid var(--adv-green-m); border-radius:var(--r); font-size:.8rem; color:var(--adv-green); font-weight:700;">
            <i class="bi bi-check-circle me-1"></i> Confirmado: ningún pasajero tiene alergias ni condiciones médicas relevantes.
        </div>

        <p style="font-size:.72rem;color:var(--ink-4);margin-top:.5rem">
            <i class="bi bi-info-circle me-1"></i>
            Al agregar pasajeros en el bloque anterior, aparecerán aquí sus campos de salud.
        </p>
    </div>
</div>

{{-- ══ BLOQUE 6 · LOGÍSTICA ══ --}}
<div class="fb" id="bloque-6">
    <div class="fb-num-badge" id="fb-status-6">6</div>
    <div class="fb-head">
        <div class="fb-ico navy"><i class="bi bi-pin-map"></i></div>
        <div class="fb-titles"><h3>Logística del viaje</h3><p>Punto de encuentro, hora de recojo, guía y observaciones</p></div>
    </div>
    <div class="fb-body">
        <div class="alerta blue">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Sección opcional.</strong>
                Se marcará completa cuando al menos un campo tenga datos.
            </div>
        </div>
        <div class="g2">
            <div class="field">
                <label class="lbl">Punto de encuentro</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-pin-map"></i></span>
                    <input type="text" name="punto_encuentro" id="punto_encuentro" value="{{ old('punto_encuentro') }}" class="fi" placeholder="Hotel, terminal, dirección..." maxlength="200" oninput="updateProgressSteps()" data-bloque="6">
                </div>
            </div>
            <div class="field">
                <label class="lbl" for="hora_recojo_display">Hora de recojo <span class="opt">(AM/PM)</span></label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="text" id="hora_recojo_display" class="fi" placeholder="06:00 AM" readonly>
                </div>
                <input type="hidden" name="hora_recojo" id="hora_recojo" value="{{ old('hora_recojo') }}">
                <div class="fhint">Hora en formato 12h (AM/PM)</div>
            </div>
        </div>
        <div class="field" style="max-width:380px">
            <label class="lbl">Guía / asesor asignado</label>
            <div class="ig">
                <span class="ia"><i class="bi bi-person-badge"></i></span>
                <input type="text" name="guia_asignado" id="guia_asignado" value="{{ old('guia_asignado') }}" class="fi" placeholder="Nombre del guía o asesor..." maxlength="150" oninput="updateProgressSteps()" data-bloque="6">
            </div>
        </div>
        <div class="field">
            <label class="lbl">Observaciones generales <span class="opt">(opcional)</span></label>
            <textarea name="observaciones" id="observaciones_generales" class="fi" rows="3" placeholder="Notas internas, requerimientos especiales..." oninput="updateProgressSteps()" data-bloque="6">{{ old('observaciones') }}</textarea>
        </div>
    </div>
</div>

{{-- ══ BLOQUE 7 · POLÍTICAS ══ --}}
<div class="fb" id="bloque-7">
    <div class="fb-num-badge" id="fb-status-7">7</div>
    <div class="fb-head">
        <div class="fb-ico blue"><i class="bi bi-shield-check"></i></div>
        <div class="fb-titles"><h3>Políticas y Privacidad</h3><p>Selecciona, personaliza y envía las políticas aplicables</p></div>
    </div>
    <div class="fb-body">
        <div class="alerta blue">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Selecciona el tipo de servicio para autocompletar las políticas.</strong>
                Puedes editar el contenido antes de guardar.
            </div>
        </div>

        <div class="st">Tipo de política</div>
        <div class="politica-btns">
            <button type="button" class="btn-politica" id="btn-politica-tour" onclick="cargarPolitica('tours')">
                <i class="bi bi-map"></i> Políticas &ndash; Tours
            </button>
            <button type="button" class="btn-politica" id="btn-politica-viaje" onclick="cargarPolitica('viajes')">
                <i class="bi bi-airplane"></i> Políticas &ndash; Viajes
            </button>
        </div>

        <div class="field">
            <label class="lbl" for="politica_descripcion">Descripción de Políticas <span class="req">*</span></label>
            <div style="position:relative">
                <span class="politica-badge" id="politica-loaded-badge">
                    <i class="bi bi-check-circle-fill"></i> Cargado
                </span>
                <textarea name="politica_descripcion" id="politica_descripcion" class="fi" rows="10"
                    placeholder="Selecciona un tipo de política arriba, o escribe el contenido manualmente..."
                    required data-validate="required" data-bloque="7"
                    oninput="updateProgressSteps()">{{ old('politica_descripcion') }}</textarea>
            </div>
            <div class="fhint"><i class="bi bi-info-circle me-1"></i>Puedes editar el contenido antes de guardar.</div>
            @error('politica_descripcion')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
        </div>
        <input type="hidden" name="politica_tipo" id="politica_tipo" value="{{ old('politica_tipo') }}">

        <div class="st">Enviar PDF al guardar</div>
        <div class="alerta amber">
            <i class="bi bi-file-earmark-pdf ai"></i>
            <div class="at">
                <strong>Selecciona por qué canal enviar el PDF al cliente al guardar la reserva.</strong>
                Puedes seleccionar WhatsApp, correo o ambos. El PDF se generará automáticamente.
            </div>
        </div>
        <div class="notif-row">
            <label class="notif-item checked" id="p-wa" onclick="toggleNotif(this,'cb-wa')">
                <input type="checkbox" name="notif_whatsapp" value="1" checked id="cb-wa">
                <span class="notif-box"><i class="bi bi-check2"></i></span>
                <i class="bi bi-whatsapp" style="color:#25d366;font-size:1rem"></i>
                <span>Enviar por WhatsApp</span>
            </label>
            <label class="notif-item" id="p-em" onclick="toggleNotif(this,'cb-em')">
                <input type="checkbox" name="notif_email" value="1" id="cb-em">
                <span class="notif-box"><i class="bi bi-check2"></i></span>
                <i class="bi bi-envelope-fill" style="color:var(--adv-blue);font-size:.9rem"></i>
                <span>Enviar por correo</span>
            </label>
        </div>
        <div class="fhint" style="margin-top:.5rem">
            <i class="bi bi-info-circle me-1"></i>
            WhatsApp abrirá wa.me al número del titular. Correo abrirá Gmail compose. Ambos incluirán el resumen de la reserva.
        </div>
    </div>
</div>

{{-- BARRA DE SUBMIT --}}
<div class="sbar">
    <div class="sbar-left">
        <div class="sbar-label">Total de la reserva</div>
        <div class="sbar-value" id="sb-total">S/ 0.00 <span id="sb-pasajeros"></span></div>
    </div>
    <div class="sbar-actions">
        <a href="{{ route('reservas.index') }}" class="btn-secondary">
            <i class="bi bi-x"></i> Cancelar
        </a>
        <button type="submit" class="btn-primary" id="btn-submit">
            <i class="bi bi-check-circle"></i> Guardar reserva
        </button>
    </div>
</div>

</form>
</div>{{-- fin form-main --}}

{{-- SUMMARY PANEL — bloque independiente, sin progreso interno --}}
<aside class="summary-panel">
    <div class="summary-panel-inner">
        <div class="sp-title"><i class="bi bi-receipt" style="font-size:.9rem"></i> Resumen financiero</div>

        <div class="sp-item">
            <div class="sp-label">Precio total</div>
            <div class="sp-value" id="sp-total">S/ 0.00</div>
        </div>
        <div class="sp-divider"></div>
        <div class="sp-item">
            <div class="sp-label">Adelanto (50%)</div>
            <div class="sp-value amber" id="sp-adel">S/ 0.00</div>
        </div>
        <div class="sp-item">
            <div class="sp-label">Saldo al embarque</div>
            <div class="sp-value red" id="sp-saldo">S/ 0.00</div>
        </div>
        <div class="sp-divider"></div>
        <div class="sp-item">
            <div class="sp-label">Estado actual</div>
            <div id="sp-estado-badge" class="sp-status-badge">
                <i class="bi bi-hourglass-split"></i> 50% Pagado
            </div>
            <div class="sp-pax-count" id="sp-pax-txt"></div>
        </div>
    </div>
</aside>

</div>{{-- fin page-wrap --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="{{ asset('js/politicas.js') }}"></script>
<script>
/* ════════════════════════════════════════════════════════════
   ADVENTURE — create.blade.php · JavaScript v7
   AJUSTES UI/UX:
   ✓ Búsqueda RUC corregida (autoRUC enganchado en oninput)
   ✓ Switch solo-pasajero invertido: inicia desactivado → muestra
   ✓ Switch salud-master invertido: inicia desactivado → SÍ tiene
                                    activado → NO tiene (oculta)
   ✓ Bloque 5 NO completo hasta interacción/datos válidos
   ✓ SIN cambios en lógica de negocio
════════════════════════════════════════════════════════════ */

/* ──────────────────────────────────────────────────────────
   1. FLATPICKR
────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {

    flatpickr('#titular_fecha_nacimiento', {
        locale: 'es', dateFormat: 'd/m/Y', maxDate: 'today', allowInput: false,
        onChange(sel, str, inst) {
            const iso = inst.formatDate(sel[0], 'Y-m-d');
            const hidden = document.getElementById('titular_fecha_nacimiento_iso');
            if (hidden) hidden.value = iso;
            calcEdad(iso);
        },
        onReady(sel, str, inst) {
            const v = document.getElementById('titular_fecha_nacimiento_iso')?.value;
            if (v) { inst.setDate(v, false, 'Y-m-d'); calcEdad(v); }
        }
    });

    flatpickr('#fecha_tour_display', {
        locale: 'es', dateFormat: 'd/m/Y', minDate: 'today', allowInput: false,
        onChange(sel, str, inst) {
            const h = document.getElementById('fecha_tour');
            if (h) h.value = inst.formatDate(sel[0], 'Y-m-d');
            updateProgressSteps();
        },
        onReady(sel, str, inst) {
            const v = document.getElementById('fecha_tour')?.value;
            if (v) inst.setDate(v, false, 'Y-m-d');
        }
    });

    flatpickr('#hora_salida_display', {
        enableTime: true, noCalendar: true,
        dateFormat: 'h:i K', time_24hr: false, allowInput: false,
        onChange(sel, str, inst) {
            const h = document.getElementById('hora_salida');
            if (h) h.value = inst.formatDate(sel[0], 'H:i');
            updateProgressSteps();
        },
        onReady(sel, str, inst) {
            const v = document.getElementById('hora_salida')?.value;
            if (v) inst.setDate(v);
        }
    });

    flatpickr('#hora_recojo_display', {
        enableTime: true, noCalendar: true,
        dateFormat: 'h:i K', time_24hr: false, allowInput: false,
        onChange(sel, str, inst) {
            const h = document.getElementById('hora_recojo');
            if (h) h.value = inst.formatDate(sel[0], 'H:i');
            updateProgressSteps();
        },
        onReady(sel, str, inst) {
            const v = document.getElementById('hora_recojo')?.value;
            if (v) inst.setDate(v);
        }
    });

    togFactura();
    calcTotal();
    loadEmailOld();
    updOpHint();

    // Estado inicial radio
    const estChecked = document.querySelector('[name="estado_inicial"]:checked');
    if (estChecked) {
        const lbl = estChecked.closest('.eo-compact');
        if (lbl) lbl.classList.add('sel');
    }

    // Nombre titular → salud
    const tiNombre = document.getElementById('titular_nombre');
    if (tiNombre) {
        actualizarNombreTitularSalud(tiNombre.value);
        tiNombre.addEventListener('input', () => actualizarNombreTitularSalud(tiNombre.value));
    }

    // Política old()
    const tipoOld = document.getElementById('politica_tipo')?.value;
    if (tipoOld) {
        document.getElementById('btn-politica-tour')?.classList.toggle('active',  tipoOld === 'tours');
        document.getElementById('btn-politica-viaje')?.classList.toggle('active', tipoOld === 'viajes');
    }

    // Auto-resize textareas
    document.querySelectorAll('textarea.fi').forEach(ta => {
        ta.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Validación blur
    document.querySelectorAll('[data-validate]').forEach(inp => {
        inp.addEventListener('blur', () => validateField(inp));
        inp.addEventListener('input', () => {
            if (inp.classList.contains('err')) validateField(inp);
            else updateProgressSteps();
        });
    });

    // Progreso reactivo
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('change', updateProgressSteps);
        el.addEventListener('input',  updateProgressSteps);
    });
    window.addEventListener('scroll', updateProgressSteps, { passive: true });

    // Notificaciones: estado visual inicial
    document.querySelectorAll('.notif-item').forEach(item => {
        const cb = item.querySelector('input[type="checkbox"]');
        if (cb && cb.checked) item.classList.add('checked');
    });

    // Solo pasajero: aplicar estado inicial — INICIA DESACTIVADO
    const soloChk = document.getElementById('solo-pasajero');
    if (soloChk) {
        // Aplicar estado visual según checked actual (puede venir del old())
        toggleSoloPasajero(soloChk);
    }

    // Salud master: aplicar estado inicial — INICIA DESACTIVADO (=> SÍ tiene)
    const saludChk = document.getElementById('salud-master-toggle');
    if (saludChk) {
        // Aplicar estado visual según checked actual
        _updateSaludMasterVisual(saludChk.checked);
        // Si el old() trajo el switch ON, marcar como touched
        if (saludChk.checked) saludChk.dataset.touched = '1';
    }

    updateProgressSteps();
});

/* ──────────────────────────────────────────────────────────
   2. HELPERS
────────────────────────────────────────────────────────── */
function soloNumeros(inp, max) {
    inp.value = (inp.value || '').replace(/\D/g, '').substring(0, max);
}

/* RUC: handler completo con auto-búsqueda al completar 11 dígitos */
let _rucTimer = null;
function onRucInput(inp) {
    // Solo dígitos, máx 11
    inp.value = (inp.value || '').replace(/\D/g, '').substring(0, 11);
    clearTimeout(_rucTimer);
    if (inp.value.length === 11) {
        _rucTimer = setTimeout(buscarRUC, 700);
    }
    updateProgressSteps();
}

/* Compatibilidad si algún punto antiguo aún llamaba autoRUC */
function autoRUC(val) {
    clearTimeout(_rucTimer);
    if ((val || '').length === 11) {
        _rucTimer = setTimeout(buscarRUC, 700);
    }
}

/* ──────────────────────────────────────────────────────────
   3. EDAD AUTOMÁTICA
────────────────────────────────────────────────────────── */
function calcEdad(isoDate) {
    const badge = document.getElementById('edad-badge');
    if (!badge) return;
    const fNac = isoDate || document.getElementById('titular_fecha_nacimiento_iso')?.value;
    if (!fNac) { badge.innerHTML = ''; return; }
    const hoy  = new Date();
    const nac  = new Date(fNac + 'T00:00:00');
    let edad   = hoy.getFullYear() - nac.getFullYear();
    const m    = hoy.getMonth() - nac.getMonth();
    if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) edad--;
    if (edad < 0 || edad > 120) { badge.innerHTML = ''; return; }
    const etapa = edad < 2 ? 'Bebé' : edad < 12 ? 'Niño/a' : edad < 18 ? 'Adolescente' : edad < 60 ? 'Adulto/a' : 'Adulto mayor';
    const ico   = edad < 18 ? 'bi-person' : 'bi-person-check';
    badge.innerHTML = `<span class="age-badge"><i class="bi ${ico}"></i><span class="age-num">${edad}</span><span style="font-size:.7rem;font-weight:600">&nbsp;${edad===1?'año':'años'} &middot; ${etapa}</span></span>`;
}

/* ──────────────────────────────────────────────────────────
   4. CÁLCULO DE TOTALES
────────────────────────────────────────────────────────── */
function calcTotal() {
    const precio   = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const estado   = document.querySelector('[name="estado_inicial"]:checked')?.value || 'mitad_pago';
    const adelanto = +(precio * 0.5).toFixed(2);
    const pagado   = estado === 'pagado' ? precio : adelanto;
    const saldo    = +(Math.max(0, precio - pagado)).toFixed(2);
    const fmt      = v => 'S/ ' + v.toFixed(2);

    _setText('sp-total', fmt(precio));
    _setText('sp-adel',  fmt(adelanto));
    _setText('sp-saldo', fmt(saldo));

    const sbTotal = document.getElementById('sb-total');
    if (sbTotal) {
        const spanHijo = sbTotal.querySelector('span');
        sbTotal.textContent = fmt(precio) + ' ';
        if (spanHijo) sbTotal.appendChild(spanHijo);
    }

    const mpInp = document.getElementById('monto_pagado_inicial');
    if (mpInp) mpInp.value = precio > 0 ? pagado.toFixed(2) : '';

    const hint = document.getElementById('calc-monto-txt');
    if (hint) hint.textContent = precio > 0
        ? (estado === 'pagado' ? '100' : '50') + '% del precio = ' + fmt(pagado)
        : 'Calculado automáticamente desde el precio';

    const badge = document.getElementById('sp-estado-badge');
    if (badge) {
        if (estado === 'pagado') {
            badge.className = 'sp-status-badge paid';
            badge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Pagado completo';
        } else {
            badge.className = 'sp-status-badge';
            badge.innerHTML = '<i class="bi bi-hourglass-split"></i> 50% Pagado';
        }
    }

    updateProgressSteps();
}

function _setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}

/* ──────────────────────────────────────────────────────────
   5. ESTADO DE PAGO
────────────────────────────────────────────────────────── */
function selEst(lbl) {
    document.querySelectorAll('.eo-compact').forEach(el => el.classList.remove('sel'));
    lbl.classList.add('sel');
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
    calcTotal();
}

/* ──────────────────────────────────────────────────────────
   6. DOC INPUT
────────────────────────────────────────────────────────── */
function onDocInput(inp) {
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    if (tipo === 'DNI')      inp.value = inp.value.replace(/\D/g,'').substring(0,8);
    else if (tipo === 'CE')  inp.value = inp.value.replace(/\D/g,'').substring(0,12);
    else if (tipo === 'RUC') inp.value = inp.value.replace(/\D/g,'').substring(0,11);
    else                     inp.value = inp.value.toUpperCase().substring(0,15);
    updateProgressSteps();
}

function onTipoDocChange() {
    const inp  = document.getElementById('titular_numero_documento');
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    inp.value  = '';
    const cfg  = { DNI:{max:8,mode:'numeric',ph:'8 dígitos'}, CE:{max:12,mode:'numeric',ph:'12 dígitos'}, RUC:{max:11,mode:'numeric',ph:'11 dígitos'}, PASAPORTE:{max:15,mode:'text',ph:'Alfanumérico'} };
    const c    = cfg[tipo] || cfg.PASAPORTE;
    inp.maxLength = c.max; inp.inputMode = c.mode; inp.placeholder = c.ph;
    hideDniResult();
}

/* ──────────────────────────────────────────────────────────
   7. SUNAT / RENIEC
────────────────────────────────────────────────────────── */
async function buscarPorDoc() {
    const doc  = (document.getElementById('titular_numero_documento')?.value || '').trim();
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    const btn  = document.getElementById('btn-lookup');
    if (!doc) { showDniResult('err', 'Ingresa el número antes de buscar.'); return; }
    if (tipo === 'DNI' && doc.length !== 8)  { showDniResult('err', 'El DNI debe tener 8 dígitos.'); return; }
    if (tipo === 'RUC' && doc.length !== 11) { showDniResult('err', 'El RUC debe tener 11 dígitos.'); return; }

    setLookupLoading(btn, true, 'btn-lookup');
    showDniResult('load', '<span class="spinner" style="margin-right:6px"></span> Consultando SUNAT/RENIEC...');

    try {
        const localUrl = tipo === 'RUC' ? '/api/buscar-ruc/' + doc : '/api/buscar-dni/' + doc;
        const localR   = await fetchJSON(localUrl);
        if (localR && localR.success && localR.nombre) {
            aplicarDatosPersona(localR); return;
        }
        const extUrl = tipo === 'RUC'
            ? 'https://api.apis.net.pe/v2/sunat/ruc?numero=' + doc
            : 'https://api.apis.net.pe/v2/reniec/dni?numero=' + doc;
        const extR = await fetchJSON(extUrl);
        if (!extR) throw new Error('sin respuesta');
        if (tipo === 'RUC' && extR.razonSocial) {
            aplicarDatosPersona({ nombre: extR.razonSocial, success: true });
            const rs = document.getElementById('razon_social');
            if (rs) rs.value = extR.razonSocial.toUpperCase();
        } else if (tipo === 'DNI' && (extR.nombres || extR.nombre)) {
            const nombre = extR.nombre
                || [extR.apellidoPaterno, extR.apellidoMaterno, extR.nombres].filter(Boolean).join(' ');
            aplicarDatosPersona({
                nombre,
                sexo: extR.sexo === 'MASCULINO' ? 'M' : (extR.sexo === 'FEMENINO' ? 'F' : ''),
                success: true
            });
        } else {
            showDniResult('err', 'No encontrado. Ingresa los datos manualmente.');
        }
    } catch {
        showDniResult('err', 'Sin conexión al servicio. Ingresa los datos manualmente.');
    } finally {
        setLookupLoading(btn, false, 'btn-lookup');
    }
}

/* RUC — búsqueda corregida */
async function buscarRUC() {
    const rucInp = document.getElementById('ruc_factura');
    const ruc    = (rucInp?.value || '').trim();
    const btn    = document.getElementById('btn-ruc-lookup');

    if (!ruc) { showRucResult('err', 'Ingresa el RUC antes de buscar.'); return; }
    if (ruc.length !== 11) { showRucResult('err', 'El RUC debe tener 11 dígitos.'); return; }

    setLookupLoading(btn, true, 'btn-ruc-lookup');
    showRucResult('load', '<span class="spinner" style="margin-right:6px"></span> Consultando SUNAT...');

    try {
        // 1. Intentar local primero
        const local = await fetchJSON('/api/buscar-ruc/' + ruc);
        if (local && (local.razon_social || local.nombre)) {
            const rs = (local.razon_social || local.nombre || '').toUpperCase();
            const rsInp = document.getElementById('razon_social');
            if (rsInp) rsInp.value = rs;
            showRucResult('ok', '<i class="bi bi-check-circle-fill me-1"></i>' + rs);
            updateProgressSteps();
            return;
        }
        // 2. Fallback API externa
        const ext = await fetchJSON('https://api.apis.net.pe/v2/sunat/ruc?numero=' + ruc);
        if (ext && ext.razonSocial) {
            const rs = ext.razonSocial.toUpperCase();
            const rsInp = document.getElementById('razon_social');
            if (rsInp) rsInp.value = rs;
            showRucResult('ok', '<i class="bi bi-check-circle-fill me-1"></i>' + rs);
            updateProgressSteps();
        } else {
            showRucResult('err', 'RUC no encontrado. Ingrésalo manualmente.');
        }
    } catch (e) {
        showRucResult('err', 'Error de conexión. Ingrésalo manualmente.');
    } finally {
        setLookupLoading(btn, false, 'btn-ruc-lookup');
    }
}

function aplicarDatosPersona(data) {
    const nombre = (data.nombre || '').toUpperCase();
    const ni = document.getElementById('titular_nombre');
    if (ni) { ni.value = nombre; actualizarNombreTitularSalud(nombre); }
    if (data.sexo) { const g = document.getElementById('titular_genero'); if (g) g.value = data.sexo; }
    if (data.cliente_id) { const c = document.getElementById('cliente_id'); if (c) c.value = data.cliente_id; }
    showDniResult('ok', '<i class="bi bi-check-circle-fill me-1"></i>' + nombre);
    updateProgressSteps();
}

async function fetchJSON(url) {
    try {
        const r = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        if (!r.ok) return null;
        return await r.json();
    } catch { return null; }
}

function setLookupLoading(btn, loading, id) {
    if (!btn) return;
    btn.disabled = loading;
    if (id === 'btn-ruc-lookup') {
        btn.innerHTML = loading
            ? '<span class="spinner" style="width:10px;height:10px;border-width:2px;margin-right:4px"></span> Buscando'
            : '<i class="bi bi-search"></i> SUNAT';
    } else {
        btn.innerHTML = loading
            ? '<span class="spinner" style="width:10px;height:10px;border-width:2px;margin-right:4px"></span> Buscando'
            : '<i class="bi bi-search"></i> Buscar';
    }
}

function showDniResult(type, msg) {
    const el = document.getElementById('dni-result');
    if (el) { el.className = 'dni-result visible ' + type; el.innerHTML = msg; }
}
function hideDniResult() { const el = document.getElementById('dni-result'); if (el) el.className = 'dni-result'; }
function showRucResult(type, msg) {
    const el = document.getElementById('ruc-result');
    if (el) { el.className = 'dni-result visible ' + type; el.innerHTML = msg; }
}

/* ──────────────────────────────────────────────────────────
   8. EMAIL WIDGET
────────────────────────────────────────────────────────── */
const DOMS = ['gmail.com','hotmail.com','outlook.com','yahoo.com','icloud.com','live.com'];
function emailInput() {
    const u = (document.getElementById('email-user')?.value || '').trim();
    if (!u) { closeDomains(); joinEmail(); return; }
    const dl = document.getElementById('domain-list');
    if (!dl) return;
    dl.innerHTML = DOMS.map(d => '<li onclick="pickDomain(\'' + d + '\')"><i class="bi bi-envelope me-1"></i>' + d + '</li>').join('')
        + '<li onclick="closeDomains()" style="color:var(--ink-4);font-style:italic;font-size:.78rem">Escribir dominio propio</li>';
    dl.classList.add('open');
    joinEmail();
}
function handleEmailPaste(e) {
    const p = (e.clipboardData||window.clipboardData).getData('text').trim();
    if (p.includes('@')) {
        e.preventDefault();
        const pts = p.split('@');
        if (pts.length === 2 && pts[0] && pts[1]) {
            const u = document.getElementById('email-user');
            const d = document.getElementById('email-domain');
            if (u) u.value = pts[0];
            if (d) d.value = pts[1];
            joinEmail(); closeDomains();
        }
    }
}
function pickDomain(v) {
    const d = document.getElementById('email-domain');
    if (d) d.value = v;
    closeDomains(); joinEmail();
}
function closeDomains() {
    document.getElementById('domain-list')?.classList.remove('open');
    joinEmail();
}
function joinEmail() {
    const u = (document.getElementById('email-user')?.value || '').trim();
    const d = (document.getElementById('email-domain')?.value || '').trim();
    const h = document.getElementById('titular_email');
    if (h) h.value = (u && d) ? u + '@' + d : '';
}
function loadEmailOld() {
    const raw = document.getElementById('titular_email')?.value || '';
    if (!raw || !raw.includes('@')) return;
    const pts = raw.split('@');
    if (pts.length === 2 && pts[0] && pts[1]) {
        const u = document.getElementById('email-user');
        const d = document.getElementById('email-domain');
        if (u) u.value = pts[0];
        if (d) d.value = pts[1];
    }
}

/* ──────────────────────────────────────────────────────────
   9. UPLOAD VOUCHER
────────────────────────────────────────────────────────── */
let voucherAdjunto = false;

function onFile(e) {
    const f = e.target.files && e.target.files[0];
    if (f) mostrarPreview(f);
}

function onDrop(e) {
    e.preventDefault();
    document.getElementById('uz')?.classList.remove('over');
    const f = e.dataTransfer?.files?.[0];
    if (!f) return;
    try {
        const dt = new DataTransfer();
        dt.items.add(f);
        document.getElementById('archivo_baucher').files = dt.files;
    } catch(_) {}
    mostrarPreview(f);
}

function mostrarPreview(f) {
    if (f.size > 5 * 1024 * 1024) { alert('El archivo supera 5 MB.'); return; }
    const uz    = document.getElementById('uz');
    const fprev = document.getElementById('fprev');
    const img   = document.getElementById('prev-img');
    const name  = document.getElementById('prev-name');
    if (uz)   uz.style.display = 'none';
    if (name) name.textContent = f.name;
    if (img) {
        if (f.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = ev => { img.src = ev.target.result; img.style.display = 'block'; };
            reader.readAsDataURL(f);
        } else { img.src = ''; img.style.display = 'none'; }
    }
    if (fprev) fprev.classList.add('visible');
    voucherAdjunto = true;
    updateProgressSteps();
}

function removeFile() {
    const inp   = document.getElementById('archivo_baucher');
    const uz    = document.getElementById('uz');
    const fprev = document.getElementById('fprev');
    const img   = document.getElementById('prev-img');
    if (inp)   inp.value = '';
    if (fprev) fprev.classList.remove('visible');
    if (uz)    { uz.style.display = ''; uz.style.border = ''; }
    if (img)   img.src = '';
    voucherAdjunto = false;
    updateProgressSteps();
}

/* ──────────────────────────────────────────────────────────
   10. NOMBRE TITULAR EN SALUD
────────────────────────────────────────────────────────── */
function actualizarNombreTitularSalud(n) {
    const el = document.getElementById('salud-titular-nombre');
    if (el) el.textContent = n || 'nombre del titular';
}

/* ──────────────────────────────────────────────────────────
   11. NOTIFICACIONES
────────────────────────────────────────────────────────── */
function toggleNotif(item, cbId) {
    const cb = document.getElementById(cbId);
    if (!cb) return;
    cb.checked = !cb.checked;
    item.classList.toggle('checked', cb.checked);
}

/* ──────────────────────────────────────────────────────────
   12. SOLO PASAJERO — Switch invertido
       Inicia DESACTIVADO → muestra sección de pasajeros
       Activado → solo titular (oculta sección)
────────────────────────────────────────────────────────── */
function toggleSoloPasajero(chk) {
    const seccion = document.getElementById('pax-seccion');
    const msg     = document.getElementById('solo-msg');
    if (chk.checked) {
        // Solo titular: ocultar sección y limpiar pasajeros
        if (seccion) seccion.style.display = 'none';
        if (msg)     msg.style.display     = 'block';
        document.querySelectorAll('#pax-lista .pax-card').forEach(c => {
            const idx = c.id.replace('pax-','');
            removePax(parseInt(idx));
        });
    } else {
        // Mostrar sección normal de pasajeros
        if (seccion) seccion.style.display = '';
        if (msg)     msg.style.display     = 'none';
    }
    updateProgressSteps();
}

/* ──────────────────────────────────────────────────────────
   12b. SALUD MASTER TOGGLE — Switch invertido
        Inicia DESACTIVADO → SÍ HAY alergias (muestra campos)
        Activado → NO HAY alergias (oculta y confirma)
        El bloque NO se marca completo si:
        - Está desactivado y los campos no tienen datos suficientes
        - El usuario no ha interactuado con el switch
────────────────────────────────────────────────────────── */
function toggleSaludMaster(chk) {
    chk.dataset.touched = '1';
    _updateSaludMasterVisual(chk.checked);
    const h = document.getElementById('salud-sin-condiciones-hidden');
    if (h) h.value = chk.checked ? '1' : '0';
    updateProgressSteps();
}

function _updateSaludMasterVisual(isOn) {
    // isOn = true  → switch activado → NO hay condiciones (oculta)
    // isOn = false → switch desactivado → SÍ hay condiciones (muestra)
    const banner    = document.getElementById('salud-master-banner');
    const label     = document.getElementById('salud-master-label');
    const badge     = document.getElementById('salud-master-badge');
    const icon      = document.getElementById('salud-master-icon');
    const lista     = document.getElementById('salud-lista');
    const confirmEl = document.getElementById('salud-confirm-msg');

    if (!banner) return;

    if (isOn) {
        // NO hay condiciones — oculta campos
        banner.classList.remove('has-conditions');
        if (label) label.textContent = 'Ningún pasajero tiene alergias o condiciones médicas';
        if (badge) badge.textContent = 'Sin condiciones';
        if (icon)  icon.className     = 'bi bi-shield-check';
        if (lista) lista.style.display = 'none';
        if (confirmEl) confirmEl.style.display = 'block';
    } else {
        // SÍ hay condiciones — muestra campos
        banner.classList.add('has-conditions');
        if (label) label.textContent = 'Algún pasajero tiene alergias o condiciones médicas';
        if (badge) badge.textContent = 'Con condiciones';
        if (icon)  icon.className     = 'bi bi-exclamation-triangle';
        if (lista) lista.style.display = '';
        if (confirmEl) confirmEl.style.display = 'none';
    }
}

/* ──────────────────────────────────────────────────────────
   13. PASAJEROS ADICIONALES
────────────────────────────────────────────────────────── */
let pN = 0;

function addPax() {
    const lista = document.getElementById('pax-lista');
    if (!lista) return;
    const i = pN++;
    const d = document.createElement('div');
    d.className = 'pax-card'; d.id = 'pax-' + i;
    d.innerHTML =
        '<div class="pax-head">' +
            '<span><i class="bi bi-person me-1"></i>Pasajero adicional ' + (i+1) + '</span>' +
            '<button type="button" class="pax-del" onclick="removePax(' + i + ')"><i class="bi bi-x"></i></button>' +
        '</div>' +
        '<div class="g3">' +
            '<div class="field">' +
                '<label class="lbl">Nombre completo <span class="req">*</span></label>' +
                '<input type="text" name="pasajeros[' + i + '][nombre_completo]" id="pax-nombre-' + i + '" class="fi" placeholder="NOMBRES APELLIDOS"' +
                ' oninput="this.value=this.value.toUpperCase();updateSaludNombre(' + i + ',this.value);updateProgressSteps()" required>' +
            '</div>' +
            '<div class="field">' +
                '<label class="lbl">Edad</label>' +
                '<input type="number" name="pasajeros[' + i + '][edad]" id="pax-edad-' + i + '" class="fi" min="0" max="120" placeholder="Ej: 25" inputmode="numeric"' +
                ' oninput="calcTipoPax(' + i + ',this.value)">' +
                '<div id="pax-tipo-badge-' + i + '" style="margin-top:.3rem"></div>' +
            '</div>' +
            '<div class="field">' +
                '<label class="lbl">Categoría</label>' +
                '<div id="pax-tipo-display-' + i + '" style="padding:0 12px;border:1.5px solid var(--border);border-radius:var(--r);font-size:.82rem;color:var(--ink-4);background:var(--adv-slate-l);height:40px;display:flex;align-items:center">— según edad —</div>' +
                '<input type="hidden" name="pasajeros[' + i + '][tipo]" id="pax-tipo-' + i + '" value="adulto">' +
            '</div>' +
            '<div class="field">' +
                '<label class="lbl">Tipo de doc.</label>' +
                '<select name="pasajeros[' + i + '][tipo_documento]" class="fi" onchange="paxDoc(this,' + i + ')">' +
                    '<option value="">Sin documento</option><option value="DNI">DNI</option><option value="CE">C.E.</option><option value="PASAPORTE">Pasaporte</option>' +
                '</select>' +
            '</div>' +
            '<div class="field">' +
                '<label class="lbl">N&deg; de documento</label>' +
                '<input type="text" name="pasajeros[' + i + '][numero_documento]" id="pd-' + i + '" class="fi" placeholder="Opcional">' +
            '</div>' +
            '<div class="field">' +
                '<label class="lbl">Teléfono</label>' +
                '<input type="text" name="pasajeros[' + i + '][telefono]" class="fi" placeholder="Opcional" maxlength="15">' +
            '</div>' +
        '</div>';
    lista.appendChild(d);
    addSaludPax(i);
    paxCnt();
    updateProgressSteps();
}

function calcTipoPax(i, edad) {
    const e       = parseInt(edad);
    const display = document.getElementById('pax-tipo-display-' + i);
    const hidden  = document.getElementById('pax-tipo-' + i);
    const badge   = document.getElementById('pax-tipo-badge-' + i);
    if (!display || !hidden) return;
    if (isNaN(e) || edad === '') {
        display.innerHTML = '<span style="color:var(--ink-4)">— según edad —</span>';
        hidden.value = 'adulto';
        if (badge) badge.innerHTML = '';
        return;
    }
    let tipo, etiqueta, color, ico;
    if      (e < 2)  { tipo='bebe';        etiqueta='Bebé';         color='#7C3AED'; ico='bi-person'; }
    else if (e < 12) { tipo='nino';         etiqueta='Niño/a';       color='#B45309'; ico='bi-person'; }
    else if (e < 18) { tipo='adolescente';  etiqueta='Adolescente';  color='#1A56DB'; ico='bi-person'; }
    else if (e < 60) { tipo='adulto';       etiqueta='Adulto/a';     color='#0B1E3D'; ico='bi-person-check'; }
    else             { tipo='adulto_mayor'; etiqueta='Adulto mayor'; color='#475569'; ico='bi-person-check-fill'; }
    hidden.value = tipo;
    display.innerHTML = '<i class="bi ' + ico + ' me-1" style="color:' + color + '"></i><strong style="color:' + color + '">' + etiqueta + '</strong>';
    if (badge) badge.innerHTML = '<span class="age-badge" style="font-size:.68rem;padding:3px 10px;background:' + color + '20;border-color:' + color + '50;color:' + color + '">' + e + (e===1?' año':' años') + ' &middot; ' + etiqueta + '</span>';
    updateProgressSteps();
}

function removePax(i) {
    document.getElementById('pax-' + i)?.remove();
    document.getElementById('salud-pax-' + i)?.remove();
    paxCnt(); updateProgressSteps();
}

function addSaludPax(i) {
    const lista = document.getElementById('salud-lista');
    if (!lista) return;
    const s = document.createElement('div');
    s.className = 'salud-block'; s.id = 'salud-pax-' + i;
    s.innerHTML =
        '<div class="salud-head">' +
            '<i class="bi bi-person" style="color:var(--ink-3)"></i>' +
            '<span>Pasajero ' + (i+1) + ' — <span id="salud-nombre-' + i + '" style="font-style:italic;color:var(--ink-4)">sin nombre</span></span>' +
            '<span class="salud-dot"></span>' +
        '</div>' +
        '<div class="salud-body">' +
            '<div class="salud-grid">' +
                '<div class="salud-col">' +
                    '<label class="lbl">Alergias o condiciones médicas</label>' +
                    '<div class="sw-group" style="margin-bottom:.6rem">' +
                        '<label class="sw-btn sw-no sel" onclick="togAlergPax(this,\'alerg-pax-' + i + '\')">' +
                            '<input type="radio" name="pasajeros[' + i + '][tiene_alergias]" value="no" checked>' +
                            '<i class="bi bi-check-circle"></i> No tiene' +
                        '</label>' +
                        '<label class="sw-btn sw-si" onclick="togAlergPax(this,\'alerg-pax-' + i + '\')">' +
                            '<input type="radio" name="pasajeros[' + i + '][tiene_alergias]" value="si">' +
                            '<i class="bi bi-exclamation-triangle"></i> Sí tiene' +
                        '</label>' +
                    '</div>' +
                    '<div id="alerg-pax-' + i + '-wrap" style="display:none">' +
                        '<div class="alerg-expand">' +
                            '<label class="lbl"><i class="bi bi-exclamation-triangle me-1"></i>Detalla las alergias</label>' +
                            '<textarea name="pasajeros[' + i + '][alergias_detalle]" id="alerg-pax-' + i + '" class="fi" rows="3" placeholder="Medicamentos, alimentos..."></textarea>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="salud-col">' +
                    '<label class="lbl">Restricciones alimentarias</label>' +
                    '<textarea name="pasajeros[' + i + '][restricciones]" class="fi" rows="3" placeholder="Sin gluten, vegano..."></textarea>' +
                '</div>' +
            '</div>' +
        '</div>';
    lista.appendChild(s);
}

function updateSaludNombre(i, n) {
    const el = document.getElementById('salud-nombre-' + i);
    if (el) el.textContent = n || 'sin nombre';
}

function paxCnt() {
    const n  = document.querySelectorAll('#pax-lista .pax-card').length;
    const el = document.getElementById('pax-cnt');
    if (el) el.textContent = n > 0 ? n + ' pasajero(s) adicional(es) registrado(s).' : '';
    const sp = document.getElementById('sp-pax-txt');
    if (sp) sp.textContent = n > 0 ? 'Total: ' + (n+1) + ' pasajeros incl. titular' : '';
    const sb = document.getElementById('sb-pasajeros');
    if (sb) sb.textContent = n > 0 ? '· ' + (n+1) + ' pasajeros' : '';
}

function paxDoc(sel, i) {
    const inp = document.getElementById('pd-' + i);
    if (!inp) return;
    const cfg = { DNI:{max:8,ph:'8 dígitos'}, CE:{max:12,ph:'12 dígitos'}, PASAPORTE:{max:15,ph:'Alfanumér.'} };
    const c   = cfg[sel.value] || {max:20,ph:'Opcional'};
    inp.maxLength = c.max; inp.placeholder = c.ph;
}

/* ──────────────────────────────────────────────────────────
   14. ALERGIAS TOGGLE
────────────────────────────────────────────────────────── */
function togAlergPax(lbl, taId) {
    lbl.closest('.sw-group')?.querySelectorAll('.sw-btn').forEach(b => b.classList.remove('sel'));
    lbl.classList.add('sel');
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
    const wrap = document.getElementById(taId + '-wrap');
    const ta   = document.getElementById(taId);
    const show = radio?.value === 'si';
    if (wrap) wrap.style.display = show ? 'block' : 'none';
    if (ta && !show) ta.value = '';
    updateProgressSteps();
}

function togSw(lbl, name) {
    lbl.closest('.sw-group')?.querySelectorAll('.sw-btn').forEach(b => b.classList.remove('sel'));
    lbl.classList.add('sel');
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
    updateProgressSteps();
}

/* ──────────────────────────────────────────────────────────
   15. FACTURA
────────────────────────────────────────────────────────── */
function togFactura() {
    const esF = document.getElementById('tipo_comprobante')?.value === 'factura';
    const cf  = document.getElementById('campos-factura');
    if (cf) cf.style.display = esF ? 'block' : 'none';
    const ruc = document.getElementById('ruc_factura');
    const rs  = document.getElementById('razon_social');
    if (ruc) ruc.required = esF;
    if (rs)  rs.required  = esF;
    updateProgressSteps();
}

/* ──────────────────────────────────────────────────────────
   16. HINT MÉTODO PAGO
────────────────────────────────────────────────────────── */
function updOpHint() {
    const v = document.getElementById('metodo_pago')?.value || '';
    const h = document.getElementById('op-hint');
    if (!h) return;
    if (['yape','plin','tunki'].includes(v))                  h.textContent = 'Número de operación visible en la app';
    else if (v.startsWith('transf') || v.startsWith('dep'))   h.textContent = 'N° de constancia o código bancario';
    else if (v.startsWith('tarjeta'))                         h.textContent = 'Últimos 4 dígitos o N° de voucher POS';
    else                                                      h.textContent = 'Código de referencia (opcional)';
}

/* ──────────────────────────────────────────────────────────
   17. POLÍTICAS
────────────────────────────────────────────────────────── */
function cargarPolitica(tipo) {
    const ta    = document.getElementById('politica_descripcion');
    const badge = document.getElementById('politica-loaded-badge');
    const hid   = document.getElementById('politica_tipo');
    document.getElementById('btn-politica-tour')?.classList.toggle('active',  tipo === 'tours');
    document.getElementById('btn-politica-viaje')?.classList.toggle('active', tipo === 'viajes');
    if (typeof window.POLITICAS_RESERVA !== 'undefined' && window.POLITICAS_RESERVA[tipo]) {
        if (ta) { ta.value = window.POLITICAS_RESERVA[tipo]; ta.classList.add('ok-val'); ta.style.height = 'auto'; ta.style.height = ta.scrollHeight + 'px'; }
        if (hid) hid.value = tipo;
        if (badge) { badge.classList.add('visible'); setTimeout(() => badge.classList.remove('visible'), 3000); }
        updateProgressSteps();
    } else {
        fetchJSON('/api/politicas/' + tipo).then(data => {
            const txt = data?.contenido || ('Escribe las políticas para el tipo: ' + tipo);
            if (ta) { ta.value = txt; ta.classList.add('ok-val'); ta.style.height = 'auto'; ta.style.height = ta.scrollHeight + 'px'; }
            if (hid) hid.value = tipo;
            if (badge) { badge.classList.add('visible'); setTimeout(() => badge.classList.remove('visible'), 3000); }
            updateProgressSteps();
        });
    }
}

/* ──────────────────────────────────────────────────────────
   18. ENVÍO WHATSAPP / GMAIL
────────────────────────────────────────────────────────── */
function _getDatosEnvio() {
    return {
        tel:      (document.getElementById('titular_telefono')?.value || '').trim(),
        email:    (document.getElementById('titular_email')?.value || '').trim(),
        nombre:   (document.getElementById('titular_nombre')?.value || 'Cliente').trim(),
        servicio: (document.getElementById('nombre_servicio')?.value || 'su reserva').trim(),
        fecha:    (document.getElementById('fecha_tour_display')?.value || ''),
        precio:   (document.getElementById('precio_tour')?.value || '0'),
    };
}

function enviarPorWhatsApp() {
    const d = _getDatosEnvio();
    if (!d.tel || d.tel.length !== 9) { alert('Ingresa un número WhatsApp válido (9 dígitos) en los datos del titular.'); return; }
    const msg = encodeURIComponent(
        '*Adventure — Agencia de Viajes*\n\n' +
        'Hola *' + d.nombre + '*, aquí el resumen de tu reserva:\n\n' +
        '[Servicio]: ' + d.servicio + '\n' +
        '[Fecha]: ' + d.fecha + '\n' +
        '[Precio total]: S/ ' + d.precio + '\n\n' +
        'Gracias por confiar en Adventure.'
    );
    window.open('https://wa.me/51' + d.tel + '?text=' + msg, '_blank');
}

function enviarPorGmail() {
    const d = _getDatosEnvio();
    if (!d.email) { alert('Ingresa un correo electrónico del titular.'); return; }
    const subject = encodeURIComponent('Adventure — Confirmación: ' + d.servicio);
    const body    = encodeURIComponent('Hola ' + d.nombre + ',\n\nTe confirmamos tu reserva:\n\nServicio: ' + d.servicio + '\nFecha: ' + d.fecha + '\nPrecio total: S/ ' + d.precio + '\n\nBuen viaje!\nEquipo Adventure');
    window.open('https://mail.google.com/mail/?view=cm&to=' + encodeURIComponent(d.email) + '&su=' + subject + '&body=' + body, '_blank');
}

/* ──────────────────────────────────────────────────────────
   19. NAVEGACIÓN SIDEBAR
────────────────────────────────────────────────────────── */
function scrollToBloque(n) {
    const b = document.getElementById('bloque-' + n);
    if (!b) return;
    b.scrollIntoView({ behavior: 'smooth', block: 'start' });
    b.style.transition = 'box-shadow .3s';
    b.style.boxShadow  = '0 0 0 3px rgba(26,86,219,.25), 0 4px 20px rgba(15,23,42,.09)';
    setTimeout(() => { b.style.boxShadow = ''; }, 1200);
}

/* ──────────────────────────────────────────────────────────
   20. SISTEMA DE PROGRESO
────────────────────────────────────────────────────────── */
const TOTAL_BLOQUES = 7;

function getBloqueStatus(n) {
    switch(n) {
        case 1: {
            const nombre = (document.getElementById('titular_nombre')?.value || '').trim();
            const tel    = (document.getElementById('titular_telefono')?.value || '').trim();
            if (!nombre || !tel) return 'incomplete';
            if (!/^9\d{8}$/.test(tel)) return 'error';
            return 'done';
        }
        case 2: {
            const srv = (document.getElementById('nombre_servicio')?.value || '').trim();
            const prc = parseFloat(document.getElementById('precio_tour')?.value) || 0;
            const fec = (document.getElementById('fecha_tour')?.value || '').trim();
            const hor = (document.getElementById('hora_salida')?.value || '').trim();
            const ciu = (document.getElementById('ciudad_procedencia')?.value || '').trim();
            if (!srv || !fec || !hor || !ciu) return 'incomplete';
            if (prc <= 0) return 'error';
            return 'done';
        }
        case 3: {
            const met = (document.getElementById('metodo_pago')?.value || '').trim();
            const mon = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
            if (!met || met === '') return 'incomplete';
            if (mon <= 0) return 'incomplete';
            if (!voucherAdjunto) return 'incomplete';
            return 'done';
        }
        case 4: {
            // Switch "solo titular" activado → siempre completo
            const soloCbk = document.getElementById('solo-pasajero');
            if (soloCbk && soloCbk.checked) return 'done';
            // Switch desactivado: validar pasajeros
            const cards = document.querySelectorAll('#pax-lista .pax-card');
            // Si no hay cards y el switch está desactivado, está incompleto
            // (debe agregar al menos uno o activar "solo titular")
            if (!cards.length) return 'incomplete';
            let ok = true;
            cards.forEach(card => {
                const idx = card.id.replace('pax-','');
                const val = (document.getElementById('pax-nombre-' + idx)?.value || '').trim();
                if (!val) { ok = false; card.classList.add('pax-incomplete'); }
                else card.classList.remove('pax-incomplete');
            });
            return ok ? 'done' : 'incomplete';
        }
        case 5: {
            // Switch INVERTIDO:
            //   ON  = NO hay condiciones → completo (confirmado)
            //   OFF = SÍ hay condiciones → debe llenar campos
            const masterChk = document.getElementById('salud-master-toggle');
            if (!masterChk) return 'incomplete';

            // Caso ON: confirmado sin condiciones, completo
            if (masterChk.checked) return 'done';

            // Caso OFF: requiere que se haya interactuado o que tenga datos
            // Verificar si hay alguna interacción con campos de salud
            const trRad     = document.querySelector('[name="titular_tiene_alergias"]:checked');
            const tieneAlerg = trRad?.value === 'si';
            const detalleAlerg = (document.getElementById('alerg-titular')?.value || '').trim();
            const restricciones = document.querySelector('[name="titular_restricciones"]')?.value.trim() || '';
            const obsMed = document.querySelector('[name="titular_obs_medicas"]')?.value.trim() || '';
            const dificMov = document.querySelector('[name="titular_dificultad_movilidad"]:checked')?.value;
            const usaMed = document.querySelector('[name="titular_usa_medicacion"]:checked')?.value;

            // Si el usuario tocó el switch (touched) → permitir completar según campos
            const wasTouched = masterChk.dataset.touched === '1';

            // Si dijo "Sí tiene" alergias pero no detalló → error
            if (tieneAlerg && !detalleAlerg) return 'error';

            // Si tocó algo significativo en salud → completo
            if (wasTouched) {
                // Touched: aceptar como completo si:
                //  - dijo "no tiene" alergias (default), o
                //  - dijo "sí tiene" y detalló
                if (tieneAlerg && detalleAlerg) return 'done';
                if (!tieneAlerg) {
                    // Si además dejó algún dato, OK
                    if (restricciones || obsMed || dificMov === 'si' || usaMed === 'si' || dificMov === 'no' || usaMed === 'no') return 'done';
                    // Sin datos extra y "no tiene" → considerarlo como pendiente de revisar
                    return 'incomplete';
                }
            }

            // No tocó nada → pendiente
            return 'incomplete';
        }
        case 6: {
            const vals = [
                document.getElementById('punto_encuentro')?.value.trim(),
                document.getElementById('hora_recojo')?.value.trim(),
                document.getElementById('guia_asignado')?.value.trim(),
                document.getElementById('observaciones_generales')?.value.trim(),
            ];
            return vals.some(v => v) ? 'done' : 'incomplete';
        }
        case 7: {
            const txt = (document.getElementById('politica_descripcion')?.value || '').trim();
            return txt.length >= 20 ? 'done' : 'incomplete';
        }
        default: return 'incomplete';
    }
}

function updateProgressSteps() {
    let doneCount  = 0;
    const activeIdx = getActiveBloqueIdx();
    for (let i = 1; i <= TOTAL_BLOQUES; i++) {
        const ps    = document.getElementById('ps-' + i);
        const fb    = document.getElementById('bloque-' + i);
        const badge = document.getElementById('fb-status-' + i);
        if (!ps || !fb) continue;
        const status = getBloqueStatus(i);

        ps.classList.remove('done','has-error','active');
        fb.classList.remove('has-errors','is-complete');

        if (badge) {
            badge.style.background = 'var(--adv-blue)';
            badge.innerHTML = String(i);
        }

        if (status === 'done') {
            ps.classList.add('done');
            fb.classList.add('is-complete');
            if (badge) {
                badge.style.background = 'var(--adv-blue)';
                badge.innerHTML = '<i class="bi bi-check2" style="font-size:.8rem"></i>';
            }
            doneCount++;
        } else if (status === 'error') {
            ps.classList.add('has-error');
            fb.classList.add('has-errors');
            if (badge) {
                badge.style.background = 'var(--adv-red)';
                badge.innerHTML = '<i class="bi bi-exclamation-lg" style="font-size:.7rem"></i>';
            }
        }

        if (i === activeIdx) ps.classList.add('active');
    }

    const pct   = Math.round((doneCount / TOTAL_BLOQUES) * 100);
    const pFill = document.getElementById('ps-fill');
    const pPct  = document.getElementById('ps-pct');
    const pDone = document.getElementById('ps-done-count');

    if (pFill) pFill.style.width = pct + '%';
    if (pPct)  pPct.textContent  = pct + '%';
    if (pDone) pDone.textContent = doneCount + '/' + TOTAL_BLOQUES;
}

function getActiveBloqueIdx() {
    let closest = 1, dist = Infinity;
    for (let i = 1; i <= TOTAL_BLOQUES; i++) {
        const el = document.getElementById('bloque-' + i);
        if (!el) continue;
        const r = el.getBoundingClientRect();
        if (r.top < window.innerHeight && r.bottom > 0) {
            const d = Math.abs(r.top - 100);
            if (d < dist) { dist = d; closest = i; }
        }
    }
    return closest;
}

/* ──────────────────────────────────────────────────────────
   21. VALIDACIÓN BLUR
────────────────────────────────────────────────────────── */
function validateField(input) {
    const rules = (input.dataset.validate || '').split('|');
    let error   = '';
    for (const rule of rules) {
        if (rule === 'required' && !input.value.trim())                            { error = 'Este campo es obligatorio.'; break; }
        if (rule === 'numeric'  && input.value && isNaN(parseFloat(input.value))) { error = 'Ingresa un valor numérico.'; break; }
        if (rule === 'positive' && input.value && parseFloat(input.value) <= 0)   { error = 'El valor debe ser mayor a cero.'; break; }
        if (rule === 'phone'    && input.value && !/^9\d{8}$/.test(input.value))  { error = 'Debe tener 9 dígitos comenzando con 9.'; break; }
    }
    input.classList.remove('err','ok-val');
    input.closest('.ig')?.classList.remove('err-group');
    let errEl = input.closest('.field')?.querySelector('.ferr.live-err');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'ferr live-err';
        (input.closest('.field') || input.parentElement).appendChild(errEl);
    }
    if (error) {
        input.classList.add('err');
        input.closest('.ig')?.classList.add('err-group');
        errEl.innerHTML = '<i class="bi bi-exclamation-circle"></i> ' + error;
        errEl.style.display = 'flex';
    } else if (input.value.trim()) {
        input.classList.add('ok-val');
        errEl.style.display = 'none';
    } else {
        errEl.style.display = 'none';
    }
    updateProgressSteps();
}

/* ──────────────────────────────────────────────────────────
   22. SUBMIT
────────────────────────────────────────────────────────── */
document.getElementById('form-reserva').addEventListener('submit', function(e) {
    joinEmail();

    const requeridos = [
        'titular_nombre','titular_telefono','nombre_tour','precio_tour',
        'fecha_tour','hora_salida','ciudad_procedencia','canal_contacto',
        'tipo_comprobante','politica_descripcion'
    ];

    let valid = true;

    requeridos.forEach(id => {
        const f = document.getElementById(id) || document.querySelector('[name="' + id + '"]');
        if (!f) return;
        const cf = document.getElementById('campos-factura');
        if (cf && cf.contains(f) && cf.style.display === 'none') return;
        if (!f.value || !f.value.trim()) {
            valid = false;
            f.classList.add('err');
        }
    });

    const metPago = document.getElementById('metodo_pago');
    if (!metPago || !metPago.value || metPago.value === '') {
        valid = false;
        if (metPago) metPago.classList.add('err');
    }

    const montoInp = document.getElementById('monto_pagado_inicial');
    if (montoInp && (!montoInp.value || parseFloat(montoInp.value) <= 0)) {
        calcTotal();
        if (!montoInp.value || parseFloat(montoInp.value) <= 0) {
            valid = false;
            montoInp.classList.add('err');
        }
    }

    if (!voucherAdjunto) {
        valid = false;
        const uz = document.getElementById('uz');
        if (uz) uz.style.border = '2px dashed var(--adv-red)';
    }

    updateProgressSteps();

    if (!valid) {
        e.preventDefault();
        const first = this.querySelector('.err, .has-errors');
        if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    const b = document.getElementById('btn-submit');
    if (b) {
        b.innerHTML = '<span class="spinner" style="width:12px;height:12px;border-width:2px;margin-right:6px"></span> Guardando...';
        b.disabled = true;
    }
});
</script>
@endpush