{{-- =====================================================================
     ARCHIVO: create.blade.php
     UBICACIÓN: resources/views/reservas/create.blade.php
     SERVICIO: Agencia de Viajes — Formulario de Nueva Reserva
     VERSIÓN MEJORADA: Barra de progreso lateral vertical sticky
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Nueva Reserva de Viaje')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<style>
:root {
    --bg:        #f0f3f8;
    --surface:   #ffffff;
    --border:    #dde2ec;
    --border-h:  #a8b4cc;

    --ink:       #0d1526;
    --ink-2:     #1e2d4a;
    --ink-3:     #5a6a85;
    --ink-4:     #9aaabb;

    --blue:      #1a4fa0;
    --blue-d:    #133a7a;
    --blue-l:    #eef3fb;
    --blue-m:    #c8d9f5;
    --blue-v:    #2563eb;

    --gold:      #e8a820;
    --gold-d:    #c48a0a;
    --gold-l:    #fdf6e3;
    --gold-m:    #fde68a;

    --green:     #0a7c52;
    --green-l:   #e8f7f1;
    --green-m:   #6ee7b7;
    --amber:     #b45309;
    --amber-l:   #fffbeb;
    --red:       #c0292e;
    --red-l:     #fef1f1;
    --red-m:     #fca5a5;

    --r:         8px;
    --r-lg:      14px;
    --sh:        0 1px 4px rgba(13,21,38,.07), 0 1px 2px rgba(13,21,38,.04);
    --sh-md:     0 4px 16px rgba(13,21,38,.10), 0 2px 6px rgba(13,21,38,.06);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Sora', sans-serif;
    background: var(--bg);
    color: var(--ink);
    -webkit-font-smoothing: antialiased;
}

/* ═══════════════════════════════
   LAYOUT PRINCIPAL CON SIDEBAR
═══════════════════════════════ */
.page-layout {
    display: flex;
    align-items: flex-start;
    max-width: 1220px;
    margin: 0 auto;
    padding: 2rem 1.25rem 5rem;
    gap: 1.5rem;
}

.pw {
    flex: 1;
    min-width: 0;
}

/* ═══════════════════════════════
   BARRA DE PROGRESO LATERAL
═══════════════════════════════ */
.progress-sidebar {
    width: 200px;
    flex-shrink: 0;
    position: sticky;
    top: 1.5rem;
    align-self: flex-start;
}

.progress-sidebar-inner {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 1.25rem 1rem;
    box-shadow: var(--sh-md);
}

.ps-title {
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--ink-4);
    margin-bottom: 1rem;
    padding-bottom: .6rem;
    border-bottom: 1px solid var(--border);
}

.progress-steps-v {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.ps-item {
    display: flex;
    align-items: center;
    gap: .65rem;
    padding: .5rem .3rem;
    cursor: pointer;
    position: relative;
    transition: all .2s;
    border-radius: var(--r);
}

.ps-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 13px;
    top: calc(50% + 14px);
    width: 2px;
    height: calc(100% - 4px);
    background: var(--border);
    z-index: 0;
}

.ps-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid var(--border);
    background: var(--surface);
    color: var(--ink-4);
    font-size: .68rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
    transition: all .25s;
}

.ps-info {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.ps-label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--ink-4);
    line-height: 1.2;
    transition: color .2s;
}

.ps-sublabel {
    font-size: .63rem;
    color: var(--ink-4);
    opacity: 0;
    transition: opacity .2s;
}

/* Estado activo */
.ps-item.active .ps-num {
    box-shadow: 0 0 0 4px rgba(232,168,32,.22);
}
.ps-item.active:not(.done):not(.has-error) .ps-num {
    border-color: var(--gold);
    background: var(--gold);
    color: #fff;
}
.ps-item.active:not(.done):not(.has-error) .ps-label { color: var(--ink-2); font-weight: 700; }
.ps-item.active:not(.done):not(.has-error) .ps-sublabel { opacity: 1; color: var(--gold-d); }
.ps-item.active:not(.done):not(.has-error) { background: var(--gold-l); }

/* Estado completado */
.ps-item.done .ps-num {
    border-color: var(--blue);
    background: var(--blue);
    color: #fff;
}
.ps-item.done .ps-label { color: var(--blue); }
.ps-item.done::after { background: var(--blue-m); }

/* Estado con error */
.ps-item.has-error .ps-num {
    border-color: var(--red);
    background: var(--red);
    color: #fff;
    animation: shakeNum .4s ease;
}
.ps-item.has-error .ps-label { color: var(--red); }
.ps-item.has-error { background: var(--red-l); }
.ps-item.has-error::after { background: var(--red-m); }

/* Tooltip de error en sidebar */
.ps-err-badge {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--red);
    margin-left: auto;
    flex-shrink: 0;
    display: none;
}
.ps-item.has-error .ps-err-badge { display: block; }

/* Mini progreso total */
.ps-footer {
    margin-top: 1rem;
    padding-top: .75rem;
    border-top: 1px solid var(--border);
}
.ps-footer-label {
    font-size: .62rem;
    color: var(--ink-4);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: .4rem;
}
.ps-progress-bar {
    height: 5px;
    background: var(--border);
    border-radius: 999px;
    overflow: hidden;
}
.ps-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--blue) 0%, var(--gold) 100%);
    border-radius: 999px;
    transition: width .4s ease;
    width: 0%;
}
.ps-pct {
    font-size: .68rem;
    font-weight: 700;
    color: var(--blue);
    margin-top: .3rem;
}

@keyframes shakeNum {
    0%,100% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    75% { transform: translateX(3px); }
}

@media(max-width: 900px) {
    .page-layout { flex-direction: column; }
    .progress-sidebar { width: 100%; position: static; }
    .progress-steps-v { flex-direction: row; flex-wrap: wrap; gap: .4rem; }
    .ps-item { flex: 1; min-width: 80px; justify-content: center; text-align: center; flex-direction: column; gap: .25rem; }
    .ps-item:not(:last-child)::after { display: none; }
    .ps-info { align-items: center; }
    .ps-sublabel { display: none; }
}

/* ═══════════════════════════════
   HEADER DE PÁGINA
═══════════════════════════════ */
.ph {
    background: linear-gradient(135deg, var(--blue-d) 0%, var(--blue) 55%, #2255b0 100%);
    border-radius: var(--r-lg);
    padding: 1.75rem 2rem;
    margin-bottom: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    box-shadow: var(--sh-md);
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.ph::before {
    content: '\F2F8';
    font-family: 'bootstrap-icons';
    position: absolute;
    right: 2rem; top: 50%;
    transform: translateY(-50%) rotate(15deg);
    font-size: 5.5rem;
    opacity: .07;
    pointer-events: none;
    line-height: 1;
}
.ph-eyebrow {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--gold-m);
    margin-bottom: .45rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.ph h1 {
    font-family: 'Lora', serif;
    font-size: 1.9rem;
    font-weight: 600;
    color: #fff;
    line-height: 1.15;
    letter-spacing: -.01em;
}
.ph p {
    font-size: .8rem;
    color: rgba(255,255,255,.65);
    margin-top: .4rem;
    line-height: 1.5;
}
.btn-back {
    display: inline-flex; align-items: center; gap: .4rem;
    color: rgba(255,255,255,.8);
    font-size: .8rem; font-weight: 600;
    text-decoration: none;
    padding: 8px 16px;
    border: 1.5px solid rgba(255,255,255,.3);
    border-radius: var(--r);
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(4px);
    transition: all .15s;
    white-space: nowrap;
    margin-top: .2rem;
    flex-shrink: 0;
}
.btn-back:hover {
    background: rgba(255,255,255,.2);
    border-color: rgba(255,255,255,.5);
    color: #fff;
}

/* ═══════════════════════════════
   BLOQUES DE SECCIÓN
═══════════════════════════════ */
.fb {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    margin-bottom: 1.25rem;
    overflow: hidden;
    box-shadow: var(--sh);
    transition: box-shadow .2s, border-color .2s;
}
.fb.has-errors {
    border-color: var(--red-m);
    box-shadow: var(--sh), 0 0 0 2px rgba(192,41,46,.08);
}
.fb-head {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border);
    background: #f7f9fd;
    display: flex;
    align-items: center;
    gap: .85rem;
}
.fb-head .ico {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: var(--blue-m);
    color: var(--blue);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.fb-head .ico.gold {
    background: var(--gold-m);
    color: var(--gold-d);
}
.fb-head h3 {
    font-size: .92rem;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -.01em;
}
.fb-head p {
    font-size: .72rem;
    color: var(--ink-4);
    margin-top: 2px;
}
.fb-num {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: var(--blue);
    color: #fff;
    font-size: .68rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-left: auto;
    transition: background .2s;
}
.fb.has-errors .fb-num { background: var(--red); }
.fb-body { padding: 1.5rem; }

/* ═══════════════════════════════
   SUBTÍTULOS DE SECCIÓN
═══════════════════════════════ */
.st {
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--blue);
    padding-bottom: .6rem;
    border-bottom: 2px solid var(--blue-l);
    margin: 1.5rem 0 1rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}
.st:first-child { margin-top: 0; }
.st::before {
    content: '';
    width: 3px; height: 12px;
    background: var(--gold);
    border-radius: 2px;
    display: inline-block;
    flex-shrink: 0;
}

/* ═══════════════════════════════
   CAMPOS
═══════════════════════════════ */
.field { margin-bottom: 1rem; }
.field:last-child { margin-bottom: 0; }

.lbl {
    display: block;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--ink-3);
    margin-bottom: .45rem;
    line-height: 1;
}
.lbl .req { color: var(--red); margin-left: 2px; }
.lbl .opt {
    color: var(--ink-4);
    font-weight: 500;
    text-transform: none;
    letter-spacing: 0;
    font-size: .67rem;
    margin-left: 4px;
}

.fi {
    width: 100%;
    padding: 9px 13px;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    font-size: .875rem;
    font-family: 'Sora', sans-serif;
    color: var(--ink);
    background: var(--surface);
    outline: none;
    transition: border-color .15s, box-shadow .15s, background .15s;
    -webkit-appearance: none;
    appearance: none;
    line-height: 1.4;
}
.fi:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(26,79,160,.12);
    background: #fafcff;
}
.fi::placeholder { color: var(--ink-4); }
.fi.err {
    border-color: var(--red);
    background: var(--red-l);
}
.fi.ok-val {
    border-color: var(--green);
    background: var(--green-l);
}
textarea.fi {
    resize: vertical;
    min-height: 76px;
    line-height: 1.55;
}
select.fi {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235a6a85' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 11px center;
    padding-right: 32px;
}

.ferr  { font-size: .71rem; color: var(--red); margin-top: .35rem; font-weight: 600; display: flex; align-items: center; gap: .3rem; }
.fhint { font-size: .71rem; color: var(--ink-4); margin-top: .3rem; line-height: 1.45; }

/* ═══════════════════════════════
   INPUT GROUP
═══════════════════════════════ */
.ig {
    display: flex;
    align-items: stretch;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    transition: border-color .15s, box-shadow .15s;
}
.ig:focus-within {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(26,79,160,.12);
}
.ig.err-group { border-color: var(--red); }
.ig .ia {
    padding: 0 12px;
    background: #f4f6fb;
    color: var(--ink-3);
    font-size: .8rem;
    font-weight: 600;
    display: flex; align-items: center; gap: .3rem;
    border-right: 1.5px solid var(--border);
    white-space: nowrap;
    flex-shrink: 0;
}
.ig .fi {
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    flex: 1;
    min-width: 0;
    background: transparent !important;
}

/* ═══════════════════════════════
   HORA CON AM/PM
═══════════════════════════════ */
.hora-wrap {
    display: flex;
    align-items: stretch;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    transition: border-color .15s, box-shadow .15s;
}
.hora-wrap:focus-within {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(26,79,160,.12);
}
.hora-wrap .ia {
    padding: 0 12px;
    background: #f4f6fb;
    color: var(--ink-3);
    font-size: .8rem;
    font-weight: 600;
    display: flex; align-items: center;
    border-right: 1.5px solid var(--border);
    white-space: nowrap;
    flex-shrink: 0;
}
.hora-wrap .fi-hora {
    flex: 1;
    border: none !important;
    box-shadow: none !important;
    border-radius: 0 !important;
    min-width: 0;
    background: transparent;
}
.hora-wrap .ampm-sel {
    border: none;
    border-left: 1.5px solid var(--border);
    background: #f4f6fb;
    color: var(--ink-2);
    font-size: .8rem;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    padding: 0 12px;
    cursor: pointer;
    outline: none;
    appearance: none;
    min-width: 58px;
    text-align: center;
}
.hora-wrap .ampm-sel:focus { color: var(--blue); }

/* ═══════════════════════════════
   GRILLAS
═══════════════════════════════ */
.g2  { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.g3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
.g12 { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; }
.g13 { display: grid; grid-template-columns: 1fr 3fr; gap: 1rem; }
@media(max-width: 680px) {
    .g2, .g3, .g12, .g13 { grid-template-columns: 1fr; }
}

/* ═══════════════════════════════
   PANEL FINANCIERO
═══════════════════════════════ */
.pp {
    background: linear-gradient(135deg, #0d1f40 0%, #1a3a70 50%, #1e4490 100%);
    border-radius: var(--r);
    padding: 1.25rem 1.5rem;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .75rem;
    position: relative;
    overflow: hidden;
}
.pp::after {
    content: 'S/';
    position: absolute;
    right: 1.5rem; bottom: -0.5rem;
    font-family: 'DM Mono', monospace;
    font-size: 4.5rem;
    font-weight: 500;
    color: rgba(255,255,255,.04);
    pointer-events: none;
    line-height: 1;
}
.pp-item .pp-l {
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.45);
    margin-bottom: .35rem;
}
.pp-item .pp-v {
    font-family: 'DM Mono', monospace;
    font-size: 1.3rem;
    font-weight: 500;
    color: #fff;
    letter-spacing: -.01em;
}
.pp-item .pp-v.a { color: var(--gold-m); }
.pp-item .pp-v.r { color: #f87171; }
@media(max-width: 680px) { .pp { grid-template-columns: 1fr; gap: .5rem; } }

/* ═══════════════════════════════
   SELECTOR DE ESTADOS
═══════════════════════════════ */
.eg {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .7rem;
    margin-top: .5rem;
}
.eg-cancel-row {
    margin-top: .5rem;
}
.eo {
    border: 2px solid var(--border);
    border-radius: var(--r);
    padding: .9rem .75rem;
    text-align: center;
    cursor: pointer;
    font-size: .77rem;
    font-weight: 600;
    color: var(--ink-3);
    transition: all .18s;
    user-select: none;
    line-height: 1.4;
    background: var(--surface);
    position: relative;
    display: block;
}
.eo input { display: none; }
.eo .eo-icon { font-size: 1.25rem; display: block; margin-bottom: .35rem; }
.eo:hover { transform: translateY(-2px); box-shadow: var(--sh-md); }

.eo.e-pagado.sel  {
    border-color: var(--green);
    background: var(--green-l);
    color: #065f46;
    box-shadow: 0 0 0 3px rgba(10,124,82,.15), 0 4px 16px rgba(10,124,82,.12);
    transform: translateY(-2px);
}
.eo.e-mitad.sel   {
    border-color: var(--gold);
    background: var(--gold-l);
    color: var(--gold-d);
    box-shadow: 0 0 0 3px rgba(232,168,32,.2), 0 4px 16px rgba(232,168,32,.12);
    transform: translateY(-2px);
}
.eo.e-cancel {
    border-color: var(--border);
    color: var(--ink-4);
    background: #fafafa;
}
.eo.e-cancel.sel  {
    border-color: var(--red);
    background: var(--red-l);
    color: #991b1b;
    box-shadow: 0 0 0 3px rgba(192,41,46,.15), 0 4px 16px rgba(192,41,46,.1);
    transform: translateY(-2px);
}
.eo-check {
    position: absolute;
    top: 6px; right: 8px;
    width: 16px; height: 16px;
    border-radius: 50%;
    opacity: 0;
    transition: opacity .18s;
    display: flex; align-items: center; justify-content: center;
    font-size: .6rem;
}
.eo.e-pagado.sel .eo-check { opacity: 1; background: var(--green); color: white; }
.eo.e-mitad.sel  .eo-check { opacity: 1; background: var(--gold); color: white; }
.eo.e-cancel.sel .eo-check { opacity: 1; background: var(--red); color: white; }

.cancel-label {
    font-size: .69rem;
    color: var(--ink-4);
    text-align: center;
    margin-top: .35rem;
    font-style: italic;
}

/* ═══════════════════════════════
   EMAIL WIDGET
═══════════════════════════════ */
.email-wrap { position: relative; }
.email-row {
    display: flex;
    align-items: stretch;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    transition: border-color .15s, box-shadow .15s;
}
.email-row:focus-within {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(26,79,160,.12);
}
.email-row .ea {
    padding: 0 11px;
    background: #f4f6fb;
    color: var(--ink-3);
    font-size: .8rem;
    font-weight: 600;
    display: flex; align-items: center; gap: .3rem;
    border-right: 1.5px solid var(--border);
    white-space: nowrap;
    flex-shrink: 0;
}
.email-row .email-user   { flex: 0 0 auto; min-width: 90px; max-width: 160px; border: none !important; box-shadow: none !important; border-radius: 0 !important; padding-right: 4px; background: transparent !important; }
.email-row .at-sign      { padding: 0 5px; display: flex; align-items: center; color: var(--ink-4); font-size: .9rem; font-weight: 700; flex-shrink: 0; user-select: none; }
.email-row .email-domain { flex: 1; border: none !important; box-shadow: none !important; border-radius: 0 !important; padding-left: 0; min-width: 0; -webkit-appearance: none; appearance: none; background: transparent !important; }
.domain-list {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0; right: 0;
    background: white;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    box-shadow: var(--sh-md);
    z-index: 200;
    overflow: hidden;
}
.domain-list.open { display: block; }
.domain-list li {
    padding: 9px 14px;
    font-size: .84rem;
    color: var(--ink-2);
    cursor: pointer;
    display: flex; align-items: center; gap: .5rem;
    transition: background .1s;
}
.domain-list li:hover { background: var(--blue-l); color: var(--blue); }

/* ═══════════════════════════════
   RESULTADO BÚSQUEDA CLIENTE
═══════════════════════════════ */
.cr {
    display: none;
    align-items: center;
    gap: .55rem;
    padding: .65rem 1rem;
    border-radius: var(--r);
    font-size: .81rem;
    font-weight: 500;
    margin-top: .75rem;
}
.cr.v  { display: flex; }
.cr.ok { background: var(--green-l);  color: #065f46; border: 1.5px solid var(--green-m); }
.cr.wa { background: var(--gold-l);   color: var(--gold-d); border: 1.5px solid var(--gold-m); }
.cr.er { background: var(--red-l);    color: #991b1b; border: 1.5px solid var(--red-m); }
.cr.ld { background: var(--bg);       color: var(--ink-3); border: 1.5px solid var(--border); }

/* ═══════════════════════════════
   PASAJEROS
═══════════════════════════════ */
.pax-card {
    background: var(--bg);
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 1rem;
    margin-bottom: .75rem;
    position: relative;
    animation: fu .2s ease;
}
.pax-head {
    font-size: .69rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--ink-3);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .9rem;
}
.pax-del {
    width: 28px; height: 28px;
    border: 1.5px solid var(--border);
    border-radius: 6px;
    background: white;
    color: var(--ink-3);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem;
    transition: all .15s;
    flex-shrink: 0;
}
.pax-del:hover { border-color: var(--red); color: var(--red); background: var(--red-l); }
.btn-add {
    width: 100%;
    padding: 10px;
    border: 2px dashed var(--border);
    border-radius: var(--r);
    background: transparent;
    color: var(--ink-3);
    font-size: .83rem;
    font-weight: 600;
    font-family: 'Sora', sans-serif;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: .4rem;
    transition: all .15s;
    margin-top: .5rem;
}
.btn-add:hover {
    border-color: var(--blue);
    color: var(--blue);
    background: var(--blue-l);
}

/* ═══════════════════════════════
   CHECKBOXES DE NOTIFICACIÓN
═══════════════════════════════ */
.notif-checks {
    display: flex;
    gap: .65rem;
    flex-wrap: wrap;
}
.notif-item {
    display: flex;
    align-items: center;
    gap: .55rem;
    padding: 9px 16px;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    cursor: pointer;
    font-size: .82rem;
    font-weight: 600;
    color: var(--ink-3);
    background: var(--surface);
    transition: all .15s;
    user-select: none;
    min-width: 160px;
}
.notif-item input[type="checkbox"] { display: none; }
.notif-box {
    width: 18px; height: 18px;
    border: 2px solid var(--border);
    border-radius: 5px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    color: transparent;
    background: var(--surface);
    transition: all .15s;
    font-size: 0;
}
.notif-ico { display: flex; align-items: center; flex-shrink: 0; }
.notif-text { line-height: 1; }
.notif-item:hover {
    border-color: var(--blue);
    color: var(--ink-2);
}
.notif-item.checked {
    border-color: var(--blue);
    background: var(--blue-l);
    color: var(--blue);
}
.notif-item.checked .notif-box {
    border-color: var(--blue);
    background: var(--blue);
    color: #fff;
    font-size: .85rem;
}

/* ═══════════════════════════════
   SALUD GRID
═══════════════════════════════ */
.salud-pasajero {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
    margin-bottom: .75rem;
}
.salud-pasajero-head {
    padding: .65rem 1rem;
    background: #f4f6fb;
    border-bottom: 1px solid var(--border);
    font-size: .73rem;
    font-weight: 700;
    color: var(--ink-2);
    display: flex; align-items: center; gap: .45rem;
}
.salud-pasajero-body { padding: 1rem; }
.salud-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    align-items: start;
}
.salud-alerg-col,
.salud-restrict-col {
    display: flex;
    flex-direction: column;
}
.salud-alerg-col .lbl,
.salud-restrict-col .lbl {
    height: 1rem;
    margin-bottom: .45rem;
}
.salud-alerg-col .pg {
    margin-bottom: .5rem;
    min-height: 34px;
}
.salud-alerg-col textarea.fi,
.salud-restrict-col textarea.fi {
    flex: 1;
    min-height: 76px;
    height: 76px;
    resize: none;
    overflow: hidden;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: pre-wrap;
}
.salud-restrict-col::before {
    content: '';
    display: block;
    height: calc(34px + .5rem);
}
@media(max-width: 680px) {
    .salud-grid { grid-template-columns: 1fr; }
    .salud-restrict-col::before { display: none; }
}

/* ═══════════════════════════════
   UPLOAD DE COMPROBANTE
═══════════════════════════════ */
.uz {
    border: 2px dashed var(--border);
    border-radius: var(--r);
    padding: 1.75rem;
    text-align: center;
    cursor: pointer;
    transition: all .2s;
    background: var(--bg);
    position: relative;
}
.uz:hover, .uz.over {
    border-color: var(--blue);
    background: var(--blue-l);
}
.uz input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}
.uz .uzi { font-size: 1.9rem; color: var(--ink-4); margin-bottom: .4rem; }
.uz .uzt { font-size: .82rem; color: var(--ink-3); font-weight: 500; }
.uz .uzs { font-size: .69rem; color: var(--ink-4); margin-top: .2rem; }
.fprev {
    display: none;
    margin-top: .75rem;
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    overflow: hidden;
}
.fprev.v { display: block; }
.fprev img { width: 100%; max-height: 200px; object-fit: contain; background: var(--bg); display: block; }
.fprev-ft {
    background: var(--ink);
    padding: .5rem .9rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.fprev-ft .fn { font-size: .72rem; color: rgba(255,255,255,.7); font-family: 'DM Mono', monospace; }
.fprev-ft .fr { background: none; border: none; color: rgba(255,255,255,.45); cursor: pointer; font-size: .72rem; transition: color .15s; }
.fprev-ft .fr:hover { color: #f87171; }

/* ═══════════════════════════════
   ALERTAS INFORMATIVAS
═══════════════════════════════ */
.alerta {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
    background: var(--gold-l);
    border: 1.5px solid var(--gold-m);
    border-left: 4px solid var(--gold);
    border-radius: var(--r);
    padding: .85rem 1rem;
    margin-bottom: 1rem;
}
.alerta.info {
    background: var(--blue-l);
    border-color: var(--blue-m);
    border-left-color: var(--blue);
}
.alerta .ai { font-size: 1rem; color: var(--gold); flex-shrink: 0; margin-top: .1rem; }
.alerta.info .ai { color: var(--blue); }
.alerta .at { font-size: .8rem; color: #92400e; line-height: 1.5; }
.alerta.info .at { color: var(--ink-2); }
.alerta .at strong { display: block; margin-bottom: .1rem; font-weight: 700; }

/* ═══════════════════════════════
   PILLS / TOGGLES
═══════════════════════════════ */
.pg { display: flex; gap: .45rem; flex-wrap: wrap; }
.pill {
    display: flex;
    align-items: center;
    gap: .4rem;
    padding: 6px 14px;
    border: 1.5px solid var(--border);
    border-radius: 999px;
    cursor: pointer;
    font-size: .77rem;
    font-weight: 500;
    color: var(--ink-3);
    transition: all .15s;
    user-select: none;
}
.pill input { display: none; }
.pill:hover { border-color: var(--blue); color: var(--blue); }
.pill.sel { border-color: var(--blue); background: var(--blue-l); color: var(--blue); font-weight: 700; }

/* ═══════════════════════════════
   BOTONES PRINCIPALES
═══════════════════════════════ */
.btn-p {
    background: var(--gold);
    color: var(--ink);
    border: none;
    border-radius: var(--r);
    padding: 10px 28px;
    font-size: .875rem;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    cursor: pointer;
    display: inline-flex; align-items: center; gap: .5rem;
    transition: all .15s;
    text-decoration: none;
    letter-spacing: -.01em;
}
.btn-p:hover {
    background: var(--gold-d);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(232,168,32,.35);
}
.btn-p:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }

.btn-s {
    background: transparent;
    color: var(--ink-3);
    border: 1.5px solid var(--border);
    border-radius: var(--r);
    padding: 9px 20px;
    font-size: .875rem;
    font-weight: 600;
    font-family: 'Sora', sans-serif;
    cursor: pointer;
    display: inline-flex; align-items: center; gap: .5rem;
    transition: all .15s;
    text-decoration: none;
}
.btn-s:hover { border-color: var(--border-h); color: var(--ink); }

/* ═══════════════════════════════
   BARRA DE SUBMIT
═══════════════════════════════ */
.sbar {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--r-lg);
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--sh-md);
    gap: 1rem;
    flex-wrap: wrap;
    position: sticky;
    bottom: 1.25rem;
    z-index: 50;
}
.sbar-left { display: flex; flex-direction: column; gap: .2rem; }
.sbar .si-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--ink-4); }
.sbar .si-val { font-family: 'DM Mono', monospace; font-size: 1.2rem; font-weight: 500; color: var(--ink); }
.sbar .si-val span { color: var(--gold-d); font-size: .75rem; font-weight: 700; margin-left: .3rem; }
.sbar .sr { display: flex; gap: .65rem; align-items: center; flex-wrap: wrap; }

/* ═══════════════════════════════
   BLOQUE DE ERRORES
═══════════════════════════════ */
.lerr {
    background: var(--red-l);
    border: 1.5px solid var(--red-m);
    border-left: 4px solid var(--red);
    border-radius: var(--r);
    padding: 1rem 1.25rem;
    margin-bottom: 1.3rem;
    font-size: .82rem;
    color: #991b1b;
}
.lerr strong { display: block; margin-bottom: .45rem; font-weight: 700; font-size: .85rem; }
.lerr ul { padding-left: 1.25rem; }
.lerr li { margin-bottom: .2rem; line-height: 1.5; }

/* ═══════════════════════════════
   VALIDACIÓN EN TIEMPO REAL
═══════════════════════════════ */
.fi.validating { border-color: var(--gold); }
.field-status {
    font-size: .69rem;
    margin-top: .3rem;
    display: flex;
    align-items: center;
    gap: .25rem;
    font-weight: 600;
}
.field-status.s-ok  { color: var(--green); }
.field-status.s-err { color: var(--red); }

/* ═══════════════════════════════
   ANIMACIONES
═══════════════════════════════ */
@keyframes fu {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes slideDown {
    from { opacity: 0; max-height: 0; }
    to   { opacity: 1; max-height: 400px; }
}
.salud-collapsible {
    transition: max-height .3s ease, opacity .3s ease;
}

/* Scroll suave al navegar desde sidebar */
html { scroll-behavior: smooth; }
</style>
@endpush

@section('contenido')
<div class="page-layout">

{{-- ══════════════════════════════════
     BARRA DE PROGRESO LATERAL (STICKY)
══════════════════════════════════ --}}
<aside class="progress-sidebar">
    <div class="progress-sidebar-inner">
        <div class="ps-title"><i class="bi bi-list-check me-1"></i> Progreso</div>
        <div class="progress-steps-v">
            <div class="ps-item active" id="ps-1" onclick="scrollToBloque(1)" title="Datos del viaje">
                <div class="ps-num">1</div>
                <div class="ps-info">
                    <span class="ps-label">Viaje</span>
                    <span class="ps-sublabel">Servicio y fechas</span>
                </div>
                <span class="ps-err-badge"></span>
            </div>
            <div class="ps-item" id="ps-2" onclick="scrollToBloque(2)" title="Pasajero titular">
                <div class="ps-num">2</div>
                <div class="ps-info">
                    <span class="ps-label">Titular</span>
                    <span class="ps-sublabel">Contacto principal</span>
                </div>
                <span class="ps-err-badge"></span>
            </div>
            <div class="ps-item" id="ps-3" onclick="scrollToBloque(3)" title="Pasajeros adicionales">
                <div class="ps-num">3</div>
                <div class="ps-info">
                    <span class="ps-label">Pasajeros</span>
                    <span class="ps-sublabel">Grupo de viaje</span>
                </div>
                <span class="ps-err-badge"></span>
            </div>
            <div class="ps-item" id="ps-4" onclick="scrollToBloque(4)" title="Salud">
                <div class="ps-num">4</div>
                <div class="ps-info">
                    <span class="ps-label">Salud</span>
                    <span class="ps-sublabel">Info médica</span>
                </div>
                <span class="ps-err-badge"></span>
            </div>
            <div class="ps-item" id="ps-5" onclick="scrollToBloque(5)" title="Pago">
                <div class="ps-num">5</div>
                <div class="ps-info">
                    <span class="ps-label">Pago</span>
                    <span class="ps-sublabel">Comprobante</span>
                </div>
                <span class="ps-err-badge"></span>
            </div>
            <div class="ps-item" id="ps-6" onclick="scrollToBloque(6)" title="Logística">
                <div class="ps-num">6</div>
                <div class="ps-info">
                    <span class="ps-label">Logística</span>
                    <span class="ps-sublabel">Encuentro y guía</span>
                </div>
                <span class="ps-err-badge"></span>
            </div>
        </div>
        <div class="ps-footer">
            <div class="ps-footer-label">Completado</div>
            <div class="ps-progress-bar">
                <div class="ps-progress-fill" id="ps-fill"></div>
            </div>
            <div class="ps-pct" id="ps-pct">0%</div>
        </div>
    </div>
</aside>

{{-- ══════════════════════════════════
     CONTENIDO PRINCIPAL
══════════════════════════════════ --}}
<div class="pw">

{{-- HEADER DE PÁGINA --}}
<div class="ph">
    <div>
        <div class="ph-eyebrow"><i class="bi bi-airplane"></i> Agencia de Viajes — Nueva Reserva</div>
        <h1>Registro de Reserva</h1>
        <p>Completa los datos del viaje, pasajeros, pago y logística para crear la reserva.</p>
    </div>
    <a href="{{ route('reservas.index') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Volver a Reservas
    </a>
</div>

@if($errors->any())
<div class="lerr">
    <strong><i class="bi bi-exclamation-triangle me-1"></i> Corrige los siguientes errores antes de guardar:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST" action="{{ route('reservas.store') }}" enctype="multipart/form-data" id="form-reserva" novalidate>
@csrf

{{-- ══════════════════════════════════
     BLOQUE 1 · DATOS DEL VIAJE
══════════════════════════════════ --}}
<div class="fb" id="bloque-1">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-airplane"></i></div>
        <div>
            <h3>Datos del viaje</h3>
            <p>Servicio, precio, fechas y estado de la reserva</p>
        </div>
        <div class="fb-num">1</div>
    </div>
    <div class="fb-body">

        <div class="st">Información del servicio</div>

        <div class="g3" style="margin-bottom:1rem">
            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="nombre_servicio">Nombre del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('nombre_tour')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-briefcase"></i></span>
                    <input type="text" name="nombre_tour" id="nombre_servicio"
                        value="{{ old('nombre_tour') }}"
                        class="fi {{ $errors->has('nombre_tour')?'err':'' }}"
                        placeholder="Ej: Paquete Lima – Cusco 5D/4N"
                        required maxlength="200"
                        data-validate="required">
                </div>
                @error('nombre_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="precio_tour">Precio del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('precio_tour')?'err-group':'' }}">
                    <span class="ia">S/</span>
                    <input type="number" name="precio_tour" id="precio_tour"
                        value="{{ old('precio_tour') }}"
                        class="fi {{ $errors->has('precio_tour')?'err':'' }}"
                        step="0.01" min="0" placeholder="0.00"
                        required inputmode="decimal"
                        oninput="this.value=this.value.replace(/[^0-9.]/g,'');calcTotal()"
                        data-validate="required|numeric">
                </div>
                @error('precio_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="ciudad_procedencia">Ciudad de origen <span class="req">*</span></label>
                <div class="ig {{ $errors->has('ciudad_procedencia')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="ciudad_procedencia" id="ciudad_procedencia"
                        value="{{ old('ciudad_procedencia') }}"
                        class="fi {{ $errors->has('ciudad_procedencia')?'err':'' }}"
                        placeholder="Lima, Cusco, Arequipa..."
                        required maxlength="100"
                        data-validate="required">
                </div>
                @error('ciudad_procedencia')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="pp">
            <div class="pp-item">
                <div class="pp-l">Precio total</div>
                <div class="pp-v" id="pp-total">S/ 0.00</div>
            </div>
            <div class="pp-item">
                <div class="pp-l">Adelanto mínimo (50%)</div>
                <div class="pp-v a" id="pp-adel">S/ 0.00</div>
            </div>
            <div class="pp-item">
                <div class="pp-l">Saldo al embarque</div>
                <div class="pp-v r" id="pp-saldo">S/ 0.00</div>
            </div>
        </div>

        <div class="st" style="margin-top:1.25rem">Fechas y canal</div>

        {{-- ELIMINADO: "Ciudad de destino" del bloque de Fechas y canal --}}
        <div class="g2">
            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="fecha_tour">Fecha de salida <span class="req">*</span></label>
                <div class="ig {{ $errors->has('fecha_tour')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_tour" id="fecha_tour"
                        value="{{ old('fecha_tour') }}"
                        class="fi {{ $errors->has('fecha_tour')?'err':'' }}"
                        required
                        data-validate="required">
                </div>
                @error('fecha_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
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
                @error('hora_salida')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="field" style="margin-top:1rem;max-width:340px">
            <label class="lbl" for="canal_contacto">Canal de venta <span class="req">*</span></label>
            <select name="canal_contacto" id="canal_contacto" class="fi" required>
                <option value="whatsapp"       {{ old('canal_contacto','whatsapp')=='whatsapp'       ?'selected':'' }}>WhatsApp</option>
                <option value="presencial"     {{ old('canal_contacto')=='presencial'     ?'selected':'' }}>Presencial</option>
                <option value="llamada"        {{ old('canal_contacto')=='llamada'        ?'selected':'' }}>Llamada telefónica</option>
                <option value="redes_sociales" {{ old('canal_contacto')=='redes_sociales' ?'selected':'' }}>Redes Sociales</option>
                <option value="web"            {{ old('canal_contacto')=='web'            ?'selected':'' }}>Página web</option>
                <option value="referido"       {{ old('canal_contacto')=='referido'       ?'selected':'' }}>Referido / Recomendación</option>
            </select>
        </div>

        <div class="st">Estado inicial de la reserva</div>

        <div class="eg">
            <label class="eo e-mitad {{ old('estado_inicial','mitad_pago')=='mitad_pago'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="mitad_pago" {{ old('estado_inicial','mitad_pago')=='mitad_pago'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-hourglass-split" style="font-size:1.1rem;color:var(--gold-d)"></i></span>
                50% Pagado
            </label>
            <label class="eo e-pagado {{ old('estado_inicial')=='pagado'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="pagado" {{ old('estado_inicial')=='pagado'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-patch-check-fill" style="font-size:1.1rem;color:var(--green)"></i></span>
                Pagado completo
            </label>
        </div>

        <div class="eg-cancel-row">
            <label class="eo e-cancel {{ old('estado_inicial')=='cancelada'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="cancelada" {{ old('estado_inicial')=='cancelada'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-x-circle-fill" style="font-size:1.1rem;color:var(--red)"></i></span>
                Cancelada
            </label>
            <p class="cancel-label">Selecciona solo si la reserva fue anulada antes de confirmarse.</p>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 2 · DATOS DEL TITULAR
══════════════════════════════════ --}}
<div class="fb" id="bloque-2">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-person-badge"></i></div>
        <div>
            <h3>Pasajero titular</h3>
            <p>Responsable principal y datos de contacto de la reserva</p>
        </div>
        <div class="fb-num">2</div>
    </div>
    <div class="fb-body">

        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id') }}">

        <div class="st">Información personal</div>

        <div class="field">
            <label class="lbl" for="titular_nombre">Nombre completo <span class="req">*</span></label>
            <div class="ig {{ $errors->has('titular_nombre')?'err-group':'' }}">
                <span class="ia"><i class="bi bi-person"></i></span>
                <input type="text" name="titular_nombre" id="titular_nombre"
                    value="{{ old('titular_nombre') }}"
                    class="fi {{ $errors->has('titular_nombre')?'err':'' }}"
                    placeholder="NOMBRES Y APELLIDOS COMPLETOS"
                    required maxlength="200"
                    oninput="this.value=this.value.toUpperCase();actualizarNombreTitularSalud(this.value)"
                    data-validate="required">
            </div>
            @error('titular_nombre')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
        </div>

        <div class="g13">
            <div class="field">
                <label class="lbl" for="titular_tipo_documento">Tipo de doc.</label>
                <select name="titular_tipo_documento" id="titular_tipo_documento" class="fi">
                    <option value="DNI"       {{ old('titular_tipo_documento','DNI')=='DNI'       ?'selected':'' }}>DNI</option>
                    <option value="CE"        {{ old('titular_tipo_documento')=='CE'        ?'selected':'' }}>C. Extranjería</option>
                    <option value="PASAPORTE" {{ old('titular_tipo_documento')=='PASAPORTE' ?'selected':'' }}>Pasaporte</option>
                    <option value="RUC"       {{ old('titular_tipo_documento')=='RUC'       ?'selected':'' }}>RUC</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_numero_documento">Número de documento</label>
                <input type="text" name="titular_numero_documento" id="titular_numero_documento"
                    value="{{ old('titular_numero_documento') }}"
                    class="fi" placeholder="Número de documento" maxlength="15"
                    inputmode="numeric"
                    oninput="restricDocTitular(this)">
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
                <label class="lbl" for="titular_genero">Género</label>
                <select name="titular_genero" id="titular_genero" class="fi">
                    <option value="">— No especificar —</option>
                    <option value="M"    {{ old('titular_genero')=='M'   ?'selected':'' }}>Masculino</option>
                    <option value="F"    {{ old('titular_genero')=='F'   ?'selected':'' }}>Femenino</option>
                    <option value="otro" {{ old('titular_genero')=='otro'?'selected':'' }}>Otro</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_nacionalidad">Nacionalidad</label>
                <input type="text" name="titular_nacionalidad" id="titular_nacionalidad"
                    value="{{ old('titular_nacionalidad', 'Peruana') }}"
                    class="fi" placeholder="Peruana, Americana..." maxlength="80">
            </div>
        </div>

        <div class="st">Contacto</div>

        <div class="g2">
            <div class="field">
                <label class="lbl" for="titular_telefono">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="#25d366" style="margin-right:3px;vertical-align:middle"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Celular / WhatsApp <span class="req">*</span>
                </label>
                <div class="ig {{ $errors->has('titular_telefono')?'err-group':'' }}">
                    <span class="ia">+51</span>
                    <input type="text" name="titular_telefono" id="titular_telefono"
                        value="{{ old('titular_telefono') }}"
                        class="fi {{ $errors->has('titular_telefono')?'err':'' }}"
                        placeholder="9XXXXXXXX" maxlength="9" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').substring(0,9)"
                        required
                        data-validate="required|phone">
                </div>
                @error('titular_telefono')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
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
                            onblur="setTimeout(closeDomains,200)"
                            onpaste="handleEmailPaste(event)">
                        <span class="at-sign">@</span>
                        <input type="text" id="email-domain" class="fi email-domain"
                            placeholder="dominio.com" maxlength="80" autocomplete="off"
                            oninput="joinEmail()"
                            onblur="setTimeout(closeDomains,200)">
                    </div>
                    <ul class="domain-list" id="domain-list"></ul>
                    <input type="hidden" name="titular_email" id="titular_email" value="{{ old('titular_email') }}">
                </div>
                <div class="fhint">Escribe el usuario y elige o escribe el dominio. Puedes pegar el correo completo.</div>
            </div>
        </div>

        <div class="field" style="max-width:300px">
            <label class="lbl">
                <i class="bi bi-telephone" style="margin-right:3px"></i>
                Teléfono secundario
                <span class="opt">(opcional)</span>
            </label>
            <div class="ig">
                <span class="ia"><i class="bi bi-telephone"></i></span>
                <input type="text" name="titular_telefono2"
                    value="{{ old('titular_telefono2') }}"
                    class="fi" placeholder="076-XXXXXX o 9XXXXXXXX" maxlength="15"
                    inputmode="tel"
                    oninput="this.value=this.value.replace(/[^0-9\-]/g,'')">
            </div>
            <div class="fhint">Si no se indica, las notificaciones van solo al número principal.</div>
        </div>

        <div class="st">Notificaciones</div>
        <div class="notif-checks">
            <label class="notif-item" id="p-wa">
                <input type="checkbox" name="notif_whatsapp" value="1" checked id="cb-wa">
                <span class="notif-box">
                    <i class="bi bi-check2" style="font-size:.85rem"></i>
                </span>
                <span class="notif-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </span>
                <span class="notif-text">WhatsApp</span>
            </label>
            <label class="notif-item" id="p-em">
                <input type="checkbox" name="notif_email" value="1" id="cb-em">
                <span class="notif-box">
                    <i class="bi bi-check2" style="font-size:.85rem"></i>
                </span>
                <span class="notif-ico">
                    <i class="bi bi-envelope-fill" style="color:var(--blue);font-size:.95rem"></i>
                </span>
                <span class="notif-text">Correo electrónico</span>
            </label>
        </div>
        <div class="fhint" style="margin-top:.5rem"><i class="bi bi-info-circle me-1"></i>Puedes seleccionar uno o ambos canales. La confirmación se enviará al guardar.</div>

    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 3 · PASAJEROS ADICIONALES
══════════════════════════════════ --}}
<div class="fb" id="bloque-3">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-people"></i></div>
        <div>
            <h3>Pasajeros adicionales</h3>
            <p>Añade el resto del grupo (el titular ya está incluido)</p>
        </div>
        <div class="fb-num">3</div>
    </div>
    <div class="fb-body">
        <div class="alerta info">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>El titular se registra como pasajero principal automáticamente.</strong>
                Agrega aquí a los demás viajeros del grupo. El documento es opcional.
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
<div class="fb" id="bloque-4">
    <div class="fb-head">
        <div class="ico gold"><i class="bi bi-heart-pulse"></i></div>
        <div>
            <h3>Salud y seguridad de los pasajeros</h3>
            <p>Información médica de cada integrante del grupo</p>
        </div>
        <div class="fb-num">4</div>
    </div>
    <div class="fb-body">
        <div id="salud-lista">
            <div class="salud-pasajero" id="salud-titular">
                <div class="salud-pasajero-head">
                    <i class="bi bi-person-badge" style="color:var(--blue)"></i>
                    <span>Titular —
                        <span id="salud-titular-nombre" style="font-style:italic;color:var(--ink-3)">nombre del titular</span>
                    </span>
                </div>
                <div class="salud-pasajero-body">
                    <div class="salud-grid">
                        <div class="salud-alerg-col">
                            <label class="lbl">Alergias o condiciones médicas</label>
                            <div class="pg" style="margin-bottom:.5rem">
                                <label class="pill sel" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="no" {{ old('titular_tiene_alergias','no')=='no'?'checked':'' }}>
                                    <i class="bi bi-check-circle"></i> No tiene
                                </label>
                                <label class="pill {{ old('titular_tiene_alergias')=='si'?'sel':'' }}" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="si" {{ old('titular_tiene_alergias')=='si'?'checked':'' }}>
                                    <i class="bi bi-exclamation-triangle"></i> Sí, tiene
                                </label>
                            </div>
                            <div id="alerg-titular-wrap" style="{{ old('titular_tiene_alergias')=='si'?'':'display:none' }}">
                                <textarea name="titular_alergias_detalle" id="alerg-titular" class="fi" rows="3"
                                    placeholder="Describe alergias, medicamentos o condiciones...">{{ old('titular_alergias_detalle') }}</textarea>
                            </div>
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
                            placeholder="Discapacidades, movilidad reducida, condiciones que el guía deba conocer...">{{ old('titular_obs_medicas') }}</textarea>
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
<div class="fb" id="bloque-5">
    <div class="fb-head">
        <div class="ico gold"><i class="bi bi-credit-card"></i></div>
        <div>
            <h3>Pago y comprobante</h3>
            <p>Método de pago, monto y comprobante adjunto</p>
        </div>
        <div class="fb-num">5</div>
    </div>
    <div class="fb-body">
        <div class="alerta">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Se requiere el 50% de adelanto para confirmar la reserva.</strong>
                El saldo restante se abona antes o al inicio del servicio.
            </div>
        </div>

        <div class="st">Comprobante fiscal</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="tipo_comprobante">Tipo de comprobante <span class="req">*</span></label>
                <select name="tipo_comprobante" id="tipo_comprobante" class="fi" required onchange="togFactura()">
                    <option value="boleta"  {{ old('tipo_comprobante','boleta')=='boleta' ?'selected':'' }}>Boleta de venta</option>
                    <option value="factura" {{ old('tipo_comprobante')=='factura'         ?'selected':'' }}>Factura</option>
                </select>
            </div>
            <div id="campos-factura" style="display:{{ old('tipo_comprobante')=='factura'?'grid':'none' }};grid-template-columns:1fr 2fr;gap:1rem;align-items:start">
                <div class="field" id="campo-ruc">
                    <label class="lbl" for="ruc_factura">RUC <span class="req">*</span></label>
                    <input type="text" name="ruc_factura" id="ruc_factura"
                        value="{{ old('ruc_factura') }}"
                        class="fi" placeholder="20XXXXXXXXX" maxlength="11"
                        inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').substring(0,11)">
                </div>
                <div class="field" id="campo-razon">
                    <label class="lbl" for="razon_social">Razón social <span class="req">*</span></label>
                    <input type="text" name="razon_social" id="razon_social"
                        value="{{ old('razon_social') }}"
                        class="fi" placeholder="EMPRESA S.A.C." maxlength="200"
                        oninput="this.value=this.value.toUpperCase()">
                </div>
            </div>
        </div>

        <div class="st">Registro del pago</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="metodo_pago">Método de pago <span class="req">*</span></label>
                <select name="metodo_pago" id="metodo_pago" class="fi" required onchange="updOpHint()" data-validate="required">
                    <option value="">Seleccionar método...</option>
                    <optgroup label="Efectivo">
                        <option value="efectivo" {{ old('metodo_pago')=='efectivo'?'selected':'' }}>Efectivo</option>
                    </optgroup>
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
                @error('metodo_pago')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl" for="tipo_pago">Tipo de pago</label>
                <select name="tipo_pago" id="tipo_pago" class="fi" onchange="onTipoPago()">
                    <option value="adelanto">Adelanto (50%)</option>
                    <option value="pago_completo">Pago completo (100%)</option>
                </select>
            </div>
        </div>

        <div class="g2">
            <div class="field">
                <label class="lbl" for="monto_pagado_inicial">Monto pagado (S/) <span class="req">*</span></label>
                <div class="ig {{ $errors->has('monto_pagado_inicial')?'err-group':'' }}">
                    <span class="ia">S/</span>
                    <input type="number" name="monto_pagado_inicial" id="monto_pagado_inicial"
                        value="{{ old('monto_pagado_inicial') }}"
                        class="fi {{ $errors->has('monto_pagado_inicial')?'err':'' }}"
                        step="0.01" min="0" placeholder="0.00"
                        required inputmode="decimal"
                        oninput="this.value=this.value.replace(/[^0-9.]/g,'');calcTotal()"
                        data-validate="required|numeric">
                </div>
                <div class="fhint" id="hint-adel"></div>
                @error('monto_pagado_inicial')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
            <div class="field">
                <label class="lbl">Fecha de pago</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_pago"
                        value="{{ old('fecha_pago', date('Y-m-d')) }}" class="fi">
                </div>
            </div>
        </div>

        <div class="field">
            <label class="lbl">N° operación / referencia
                <span class="opt">(opcional — identificable con el comprobante)</span>
            </label>
            <input type="text" name="numero_operacion"
                value="{{ old('numero_operacion') }}"
                class="fi" placeholder="Código de transacción..." maxlength="100"
                style="max-width:380px">
            <div class="fhint" id="op-hint">Código visible en Yape, Plin o constancia bancaria</div>
        </div>

        <div class="st">Comprobante adjunto</div>
        <div class="uz" id="uz"
             ondragover="event.preventDefault();this.classList.add('over')"
             ondragleave="this.classList.remove('over')"
             ondrop="onDrop(event)">
            <input type="file" name="archivo_baucher" id="archivo_baucher"
                accept=".jpg,.jpeg,.png,.pdf,.webp"
                onchange="onFile(event)">
            <div class="uzi"><i class="bi bi-cloud-arrow-up"></i></div>
            <div class="uzt">Arrastra el comprobante aquí o <strong style="color:var(--blue)">haz clic para seleccionar</strong></div>
            <div class="uzs">JPG · PNG · PDF · WEBP — máx. 5 MB</div>
        </div>
        <div class="fprev" id="fprev">
            <img id="prev-img" src="" alt="">
            <div class="fprev-ft">
                <span class="fn" id="prev-name">—</span>
                <button type="button" class="fr" onclick="removeFile()">
                    <i class="bi bi-x-circle me-1"></i> Quitar archivo
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 6 · LOGÍSTICA
     (eliminado: N° de confirmación del proveedor)
══════════════════════════════════ --}}
<div class="fb" id="bloque-6">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-pin-map"></i></div>
        <div>
            <h3>Logística del viaje</h3>
            <p>Punto de encuentro, recojo, guía y observaciones finales</p>
        </div>
        <div class="fb-num">6</div>
    </div>
    <div class="fb-body">
        <div class="g2">
            <div class="field">
                <label class="lbl">Punto de encuentro</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-pin-map"></i></span>
                    <input type="text" name="punto_encuentro"
                        value="{{ old('punto_encuentro') }}"
                        class="fi" placeholder="Hotel, terminal, dirección..." maxlength="200">
                </div>
            </div>
            <div class="field">
                <label class="lbl">Hora de recojo</label>
                <div class="hora-wrap">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="time" name="hora_recojo" id="hora_recojo_24"
                        value="{{ old('hora_recojo') }}"
                        class="fi fi-hora"
                        oninput="syncAmPmRecojo()">
                    <select class="ampm-sel" id="ampm-sel-recojo" onchange="syncHoraRecojo()">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Solo guía asignado — eliminado: N° de confirmación del proveedor --}}
        <div class="field" style="max-width:400px">
            <label class="lbl">Guía / asesor asignado</label>
            <div class="ig">
                <span class="ia"><i class="bi bi-person-badge"></i></span>
                <input type="text" name="guia_asignado"
                    value="{{ old('guia_asignado') }}"
                    class="fi" placeholder="Nombre del guía o asesor..." maxlength="150">
            </div>
        </div>

        <div class="field">
            <label class="lbl">Observaciones generales</label>
            <textarea name="observaciones" class="fi" rows="3"
                placeholder="Notas internas, requerimientos especiales, indicaciones para el guía, información adicional del viaje...">{{ old('observaciones') }}</textarea>
        </div>
    </div>
</div>

{{-- BARRA DE SUBMIT --}}
<div class="sbar">
    <div class="sbar-left">
        <div class="si-label">Total de la reserva</div>
        <div class="si-val" id="sb-total">S/ 0.00 <span id="sb-pasajeros"></span></div>
    </div>
    <div class="sr">
        <a href="{{ route('reservas.index') }}" class="btn-s">
            <i class="bi bi-x"></i> Cancelar
        </a>
        <button type="submit" class="btn-p" id="btn-submit">
            <i class="bi bi-check-circle"></i> Guardar reserva
        </button>
    </div>
</div>

</form>
</div>{{-- fin .pw --}}
</div>{{-- fin .page-layout --}}
@endsection

@push('scripts')
<script>
/* ══════════════════════════════════════════════════════
   CÁLCULO DE TOTALES
══════════════════════════════════════════════════════ */
function calcTotal() {
    const precio = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const pagado = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
    const adel   = precio * 0.5;
    const saldo  = Math.max(0, precio - pagado);
    const fmt    = v => `S/ ${v.toFixed(2)}`;

    document.getElementById('pp-total').textContent  = fmt(precio);
    document.getElementById('pp-adel').textContent   = fmt(adel);
    document.getElementById('pp-saldo').textContent  = fmt(saldo);
    document.getElementById('sb-total').childNodes[0].textContent = fmt(precio) + ' ';

    const h = document.getElementById('hint-adel');
    if (h) h.textContent = precio > 0 ? `Adelanto mínimo sugerido: ${fmt(adel)}` : '';
}

function onTipoPago() {
    const tipo   = document.getElementById('tipo_pago').value;
    const precio = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const c      = document.getElementById('monto_pagado_inicial');
    c.value = tipo === 'pago_completo' ? precio.toFixed(2) : (precio * 0.5).toFixed(2);
    calcTotal();
}

/* ══════════════════════════════════════════════════════
   HORA AM/PM
══════════════════════════════════════════════════════ */
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
    if (sel.value === 'PM' && hh < 12)  hh += 12;
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
    if (sel.value === 'PM' && hh < 12)  hh += 12;
    if (sel.value === 'AM' && hh === 12) hh = 0;
    inp.value = `${String(hh).padStart(2,'0')}:${String(mm).padStart(2,'0')}`;
}

/* ══════════════════════════════════════════════════════
   ESTADOS DE RESERVA
══════════════════════════════════════════════════════ */
function selEst(lbl) {
    document.querySelectorAll('.eo').forEach(e => e.classList.remove('sel'));
    lbl.classList.add('sel');
    lbl.querySelector('input').checked = true;
}

/* ══════════════════════════════════════════════════════
   WIDGET DE EMAIL
══════════════════════════════════════════════════════ */
const DOMS = [
    {icon:'✉', v:'gmail.com'},
    {icon:'✉', v:'hotmail.com'},
    {icon:'✉', v:'outlook.com'},
    {icon:'✉', v:'yahoo.com'},
    {icon:'✉', v:'icloud.com'},
    {icon:'✉', v:'live.com'},
];

function emailInput() {
    const u = document.getElementById('email-user').value.trim();
    if (!u) { closeDomains(); joinEmail(); return; }
    const dl = document.getElementById('domain-list');
    dl.innerHTML = DOMS
        .map(d => `<li onclick="pickDomain('${d.v}')">${d.icon} ${d.v}</li>`)
        .join('')
        + `<li onclick="closeDomains()" style="color:var(--ink-4);font-style:italic;font-size:.79rem">Escribir dominio propio</li>`;
    dl.classList.add('open');
    joinEmail();
}

function handleEmailPaste(event) {
    const pasted = (event.clipboardData || window.clipboardData).getData('text').trim();
    if (pasted.includes('@')) {
        event.preventDefault();
        const parts = pasted.split('@');
        if (parts.length === 2 && parts[0] && parts[1]) {
            document.getElementById('email-user').value   = parts[0];
            document.getElementById('email-domain').value = parts[1];
            joinEmail();
            closeDomains();
        }
    }
}

function pickDomain(v) {
    document.getElementById('email-domain').value = v;
    closeDomains();
    joinEmail();
}
function closeDomains() {
    document.getElementById('domain-list').classList.remove('open');
    joinEmail();
}
function joinEmail() {
    const u = document.getElementById('email-user').value.trim();
    const d = document.getElementById('email-domain').value.trim();
    document.getElementById('titular_email').value = (u && d) ? `${u}@${d}` : '';
}
function loadEmailOld() {
    const raw = '{{ old("titular_email") }}';
    if (!raw) return;
    const parts = raw.split('@');
    if (parts.length === 2 && parts[0] && parts[1]) {
        document.getElementById('email-user').value   = parts[0];
        document.getElementById('email-domain').value = parts[1];
        joinEmail();
    }
}

/* ══════════════════════════════════════════════════════
   SALUD — nombre del titular
══════════════════════════════════════════════════════ */
function actualizarNombreTitularSalud(nombre) {
    const el = document.getElementById('salud-titular-nombre');
    if (el) el.textContent = nombre || 'nombre del titular';
}

/* ══════════════════════════════════════════════════════
   PASAJEROS ADICIONALES
══════════════════════════════════════════════════════ */
let pN = 0;

function addPax() {
    const lista = document.getElementById('pax-lista');
    const i = pN++;
    const d = document.createElement('div');
    d.className = 'pax-card';
    d.id = `pax-${i}`;
    d.innerHTML = `
        <div class="pax-head">
            <span><i class="bi bi-person me-1"></i>Pasajero adicional ${i + 1}</span>
            <button type="button" class="pax-del" onclick="removePax(${i})" title="Eliminar pasajero">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div class="g3">
            <div class="field">
                <label class="lbl">Nombre completo <span class="req">*</span></label>
                <input type="text" name="pasajeros[${i}][nombre_completo]" id="pax-nombre-${i}" class="fi"
                    placeholder="NOMBRES APELLIDOS"
                    oninput="this.value=this.value.toUpperCase();updateSaludNombre(${i},this.value)"
                    required>
            </div>
            <div class="field">
                <label class="lbl">Tipo</label>
                <select name="pasajeros[${i}][tipo]" class="fi">
                    <option value="adulto">Adulto</option>
                    <option value="nino">Niño</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl">Edad</label>
                <input type="number" name="pasajeros[${i}][edad]" class="fi" min="0" max="120" placeholder="—" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').substring(0,3)">
            </div>
            <div class="field">
                <label class="lbl">Tipo de doc.</label>
                <select name="pasajeros[${i}][tipo_documento]" class="fi" onchange="paxDoc(this,${i})">
                    <option value="">Sin documento</option>
                    <option value="DNI">DNI</option>
                    <option value="CE">C.E.</option>
                    <option value="PASAPORTE">Pasaporte</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl">N° de documento</label>
                <input type="text" name="pasajeros[${i}][numero_documento]" id="pd-${i}" class="fi" placeholder="Opcional">
            </div>
            <div class="field">
                <label class="lbl">Teléfono</label>
                <input type="text" name="pasajeros[${i}][telefono]" class="fi" placeholder="Opcional" maxlength="15">
            </div>
        </div>`;
    lista.appendChild(d);
    addSaludPax(i);
    paxCnt();
    updateProgressSteps();
}

function removePax(i) {
    document.getElementById(`pax-${i}`)?.remove();
    document.getElementById(`salud-pax-${i}`)?.remove();
    paxCnt();
    updateProgressSteps();
}

function addSaludPax(i) {
    const lista = document.getElementById('salud-lista');
    const s = document.createElement('div');
    s.className = 'salud-pasajero';
    s.id = `salud-pax-${i}`;
    s.innerHTML = `
        <div class="salud-pasajero-head">
            <i class="bi bi-person" style="color:var(--ink-3)"></i>
            <span>Pasajero ${i + 1} —
                <span id="salud-nombre-${i}" style="font-style:italic;color:var(--ink-3)">sin nombre</span>
            </span>
        </div>
        <div class="salud-pasajero-body">
            <div class="salud-grid">
                <div class="salud-alerg-col">
                    <label class="lbl">Alergias o condiciones médicas</label>
                    <div class="pg" style="margin-bottom:.5rem">
                        <label class="pill sel" onclick="togAlergPax(this,'alerg-pax-${i}')">
                            <input type="radio" name="pasajeros[${i}][tiene_alergias]" value="no" checked>
                            <i class="bi bi-check-circle"></i> No tiene
                        </label>
                        <label class="pill" onclick="togAlergPax(this,'alerg-pax-${i}')">
                            <input type="radio" name="pasajeros[${i}][tiene_alergias]" value="si">
                            <i class="bi bi-exclamation-triangle"></i> Sí, tiene
                        </label>
                    </div>
                    <div id="alerg-pax-${i}-wrap" style="display:none">
                        <textarea name="pasajeros[${i}][alergias_detalle]" id="alerg-pax-${i}" class="fi" rows="3"
                            placeholder="Describe alergias, medicamentos..."></textarea>
                    </div>
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
                    placeholder="Discapacidades, movilidad reducida, condiciones que el guía deba conocer..."></textarea>
            </div>
        </div>`;
    lista.appendChild(s);
}

function updateSaludNombre(i, nombre) {
    const el = document.getElementById(`salud-nombre-${i}`);
    if (el) el.textContent = nombre || 'sin nombre';
}

function paxDoc(sel, i) {
    const inp = document.getElementById(`pd-${i}`);
    if (!inp) return;
    const t = sel.value;
    if (t === 'DNI')       { inp.maxLength = 8;  inp.placeholder = '8 dígitos';  inp.oninput = () => { inp.value = inp.value.replace(/\D/g,'').substring(0, 8); }; }
    else if (t === 'CE')   { inp.maxLength = 12; inp.placeholder = 'Hasta 12';   inp.oninput = () => { inp.value = inp.value.replace(/\D/g,'').substring(0,12); }; }
    else if (t === 'PASAPORTE') { inp.maxLength = 15; inp.placeholder = 'Alfanumér.'; inp.oninput = () => { inp.value = inp.value.toUpperCase().substring(0,15); }; }
    else { inp.maxLength = 20; inp.placeholder = 'Opcional'; inp.oninput = null; }
}

function paxCnt() {
    const n = document.querySelectorAll('#pax-lista .pax-card').length;
    const el = document.getElementById('pax-cnt');
    el.textContent = n > 0 ? `${n} pasajero(s) adicional(es) registrado(s).` : '';
    const sb = document.getElementById('sb-pasajeros');
    if (sb) sb.textContent = n > 0 ? `· ${n + 1} pasajeros` : '';
}

/* ══════════════════════════════════════════════════════
   ALERGIAS TOGGLE
══════════════════════════════════════════════════════ */
function togAlergPax(lbl, taId) {
    lbl.closest('.pg').querySelectorAll('.pill').forEach(p => p.classList.remove('sel'));
    lbl.classList.add('sel');
    const radio = lbl.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;

    const wrapId = taId + '-wrap';
    const wrap = document.getElementById(wrapId);
    const ta   = document.getElementById(taId);
    if (wrap) {
        const show = radio && radio.value === 'si';
        wrap.style.display = show ? 'block' : 'none';
        if (ta && !show) ta.value = '';
    } else if (ta) {
        const show = radio && radio.value === 'si';
        ta.style.display = show ? 'block' : 'none';
        if (!show) ta.value = '';
    }
}

/* ══════════════════════════════════════════════════════
   RESTRICCIONES DE CAMPOS NUMÉRICOS
══════════════════════════════════════════════════════ */
function restricDocTitular(inp) {
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    if (tipo === 'DNI' || tipo === 'RUC') {
        inp.value = inp.value.replace(/\D/g, '');
        if (tipo === 'DNI') inp.value = inp.value.substring(0, 8);
        if (tipo === 'RUC') inp.value = inp.value.substring(0, 11);
    } else if (tipo === 'CE') {
        inp.value = inp.value.replace(/\D/g, '').substring(0, 12);
    } else {
        /* Pasaporte — alfanumérico, solo mayúsculas */
        inp.value = inp.value.toUpperCase().substring(0, 15);
    }
}

/* Sincronizar placeholder/inputmode al cambiar tipo doc titular */
document.addEventListener('DOMContentLoaded', () => {
    const tipoSel = document.getElementById('titular_tipo_documento');
    const docInp  = document.getElementById('titular_numero_documento');
    if (tipoSel && docInp) {
        tipoSel.addEventListener('change', function() {
            const t = this.value;
            if (t === 'DNI')       { docInp.maxLength = 8;  docInp.placeholder = '8 dígitos';        docInp.inputMode = 'numeric'; }
            else if (t === 'CE')   { docInp.maxLength = 12; docInp.placeholder = '12 dígitos';       docInp.inputMode = 'numeric'; }
            else if (t === 'RUC')  { docInp.maxLength = 11; docInp.placeholder = '11 dígitos (RUC)'; docInp.inputMode = 'numeric'; }
            else                   { docInp.maxLength = 15; docInp.placeholder = 'Alfanumérico';     docInp.inputMode = 'text'; }
            docInp.value = '';
        });
    }
});

/* ══════════════════════════════════════════════════════
   FACTURA
══════════════════════════════════════════════════════ */
function togFactura() {
    const es = document.getElementById('tipo_comprobante').value === 'factura';
    document.getElementById('campos-factura').style.display = es ? 'grid' : 'none';
    document.getElementById('ruc_factura').required  = es;
    document.getElementById('razon_social').required = es;
}

/* ══════════════════════════════════════════════════════
   NOTIFICACIONES
══════════════════════════════════════════════════════ */
function initNotifChecks() {
    document.querySelectorAll('.notif-item').forEach(item => {
        const cb = item.querySelector('input[type="checkbox"]');
        if (!cb) return;
        if (cb.checked) item.classList.add('checked');
        item.addEventListener('click', function() {
            cb.checked = !cb.checked;
            this.classList.toggle('checked', cb.checked);
        });
    });
}

/* ══════════════════════════════════════════════════════
   HINT OPERACIÓN POR MÉTODO
══════════════════════════════════════════════════════ */
function updOpHint() {
    const v = document.getElementById('metodo_pago').value;
    const h = document.getElementById('op-hint');
    if (!h) return;
    if      (v === 'yape' || v === 'plin' || v === 'tunki')  h.textContent = 'Número de operación visible en la app (opcional si adjunta imagen)';
    else if (v.startsWith('transf') || v.startsWith('dep'))  h.textContent = 'N° de constancia o código bancario (opcional si adjunta imagen)';
    else if (v.startsWith('tarjeta'))                         h.textContent = 'Últimos 4 dígitos o N° de voucher (opcional)';
    else                                                       h.textContent = 'Código de referencia (opcional — identificable con el comprobante adjunto)';
}

/* ══════════════════════════════════════════════════════
   UPLOAD DE COMPROBANTE
══════════════════════════════════════════════════════ */
function onFile(e) {
    const f = e.target.files[0];
    if (f) showPrev(f);
}
function onDrop(e) {
    e.preventDefault();
    document.getElementById('uz').classList.remove('over');
    const f = e.dataTransfer.files[0];
    if (!f) return;
    try { document.getElementById('archivo_baucher').files = e.dataTransfer.files; } catch(_) {}
    showPrev(f);
}
function showPrev(f) {
    if (f.size > 5 * 1024 * 1024) {
        alert('El archivo supera el límite de 5 MB. Por favor selecciona un archivo más pequeño.');
        return;
    }
    document.getElementById('uz').style.display = 'none';
    document.getElementById('prev-name').textContent = f.name;
    const img = document.getElementById('prev-img');
    if (f.type.startsWith('image/')) {
        const r = new FileReader();
        r.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
        r.readAsDataURL(f);
    } else {
        img.style.display = 'none';
    }
    document.getElementById('fprev').classList.add('v');
}
function removeFile() {
    document.getElementById('archivo_baucher').value = '';
    document.getElementById('fprev').classList.remove('v');
    document.getElementById('uz').style.display = '';
    document.getElementById('prev-img').src = '';
}

/* ══════════════════════════════════════════════════════
   NAVEGACIÓN DESDE SIDEBAR
══════════════════════════════════════════════════════ */
function scrollToBloque(n) {
    const bloque = document.getElementById(`bloque-${n}`);
    if (bloque) {
        bloque.scrollIntoView({ behavior: 'smooth', block: 'start' });
        /* Highlight visual temporal */
        bloque.style.transition = 'box-shadow .3s';
        bloque.style.boxShadow  = '0 0 0 3px rgba(26,79,160,.25), var(--sh-md)';
        setTimeout(() => { bloque.style.boxShadow = ''; }, 1200);
    }
}

/* ══════════════════════════════════════════════════════
   BARRA DE PROGRESO LATERAL — LÓGICA COMPLETA
══════════════════════════════════════════════════════ */

/* Campos obligatorios por bloque — los bloques sin lista son 100% opcionales */
const BLOQUE_REQS = {
    1: ['nombre_tour', 'precio_tour', 'ciudad_procedencia', 'fecha_tour', 'hora_salida'],
    2: ['titular_nombre', 'titular_telefono'],
    3: null,   /* completamente opcional */
    4: null,   /* completamente opcional */
    5: ['metodo_pago', 'monto_pagado_inicial'],
    6: null,   /* completamente opcional */
};

function getBloqueStatus(bloqueNum) {
    const reqs = BLOQUE_REQS[bloqueNum];

    /* Bloques opcionales (null): siempre "done" — se pintan azul desde el inicio */
    if (reqs === null) return 'done';

    let hasError   = false;
    let allFilled  = true;

    reqs.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`) || document.getElementById(name);
        if (!el) return;
        const val = el.value ? el.value.trim() : '';
        if (!val) {
            allFilled = false;
        } else {
            if (name === 'titular_telefono' && !/^9\d{8}$/.test(val)) hasError = true;
            if ((name === 'precio_tour' || name === 'monto_pagado_inicial') && isNaN(parseFloat(val))) hasError = true;
        }
    });

    if (hasError) return 'error';
    if (!allFilled) return 'incomplete';
    return 'done';
}

function updateProgressSteps() {
    let doneCount = 0;
    const total   = 6;

    /* Detectar bloque activo ANTES de asignar clases */
    const activeIdx = getActiveBloqueIdx();

    for (let i = 1; i <= total; i++) {
        const psItem = document.getElementById(`ps-${i}`);
        const bloque = document.getElementById(`bloque-${i}`);
        if (!psItem) continue;

        const status = getBloqueStatus(i);

        /* Limpiar clases anteriores */
        psItem.classList.remove('done', 'has-error', 'active');
        if (bloque) bloque.classList.remove('has-errors');

        if (status === 'done') {
            psItem.classList.add('done');
            doneCount++;
        } else if (status === 'error') {
            psItem.classList.add('has-error');
            if (bloque) bloque.classList.add('has-errors');
        }
        /* 'incomplete' → sin clase especial (gris neutro, aún no completado) */

        /* Bloque activo: marcarlo aunque ya esté done o error */
        if (i === activeIdx) {
            psItem.classList.add('active');
        }
    }

    /* Barra de progreso total */
    const pct   = Math.round((doneCount / total) * 100);
    const fill  = document.getElementById('ps-fill');
    const pctEl = document.getElementById('ps-pct');
    if (fill)  fill.style.width   = pct + '%';
    if (pctEl) pctEl.textContent  = pct + '%';
}

function getActiveBloqueIdx() {
    let closestIdx = 1;
    let closestTop = Infinity;
    for (let i = 1; i <= 6; i++) {
        const el = document.getElementById(`bloque-${i}`);
        if (!el) continue;
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            const dist = Math.abs(rect.top - 100);
            if (dist < closestTop) { closestTop = dist; closestIdx = i; }
        }
    }
    return closestIdx;
}

/* ══════════════════════════════════════════════════════
   VALIDACIÓN AL PERDER FOCO (blur)
══════════════════════════════════════════════════════ */
function validateField(input) {
    const rules = (input.dataset.validate || '').split('|');
    let error   = '';

    for (const rule of rules) {
        if (rule === 'required' && !input.value.trim()) {
            error = 'Este campo es obligatorio.';
            break;
        }
        if (rule === 'numeric' && input.value && isNaN(parseFloat(input.value))) {
            error = 'Ingresa un valor numérico válido.';
            break;
        }
        if (rule === 'phone' && input.value && !/^9\d{8}$/.test(input.value)) {
            error = 'Debe ser 9 dígitos comenzando con 9.';
            break;
        }
    }

    input.classList.remove('err', 'ok-val');
    const wrapper = input.closest('.ig') || input.closest('.hora-wrap');
    if (wrapper) wrapper.classList.remove('err-group');

    let errEl = input.closest('.field')?.querySelector('.ferr.live-err');
    if (!errEl) {
        errEl = document.createElement('div');
        errEl.className = 'ferr live-err';
        const parent = input.closest('.field') || input.parentElement;
        parent.appendChild(errEl);
    }

    if (error) {
        input.classList.add('err');
        if (wrapper) wrapper.classList.add('err-group');
        errEl.innerHTML = `<i class="bi bi-exclamation-circle"></i> ${error}`;
        errEl.style.display = 'flex';
    } else if (input.value.trim()) {
        input.classList.add('ok-val');
        errEl.style.display = 'none';
    } else {
        errEl.style.display = 'none';
    }

    /* Actualizar barra de progreso tras validar */
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════
   SUBMIT
══════════════════════════════════════════════════════ */
document.getElementById('form-reserva').addEventListener('submit', function(e) {
    joinEmail();

    const requiredFields = this.querySelectorAll('[required]');
    let formValid = true;

    requiredFields.forEach(field => {
        if (!field.value || !field.value.trim()) {
            formValid = false;
            field.classList.add('err');
        }
    });

    /* Actualizar sidebar con errores antes de abortar */
    updateProgressSteps();

    if (!formValid) {
        e.preventDefault();
        const firstErr = this.querySelector('.err');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    const b = document.getElementById('btn-submit');
    b.innerHTML = '<span style="display:inline-block;width:13px;height:13px;border:2px solid currentColor;border-top-color:transparent;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle;margin-right:.3rem"></span> Guardando...';
    b.disabled = true;
});

/* ══════════════════════════════════════════════════════
   INICIALIZACIÓN
══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    calcTotal();
    loadEmailOld();
    updOpHint();
    initNotifChecks();

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

    document.querySelectorAll('textarea.fi').forEach(ta => {
        ta.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    document.querySelectorAll('[data-validate]').forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('err')) validateField(input);
            else updateProgressSteps();
        });
    });

    /* Actualizar progreso al cambiar cualquier campo */
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('change', updateProgressSteps);
        el.addEventListener('input', updateProgressSteps);
    });

    /* Actualizar bloque activo al hacer scroll */
    window.addEventListener('scroll', () => {
        updateProgressSteps();
    }, { passive: true });

    /* Spinner CSS */
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);

    /* Inicializar progreso */
    updateProgressSteps();
});
</script>
@endpush