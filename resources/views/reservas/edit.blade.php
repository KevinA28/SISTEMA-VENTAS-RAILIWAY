{{-- =====================================================================
     ARCHIVO: edit.blade.php
     UBICACIÓN: resources/views/reservas/edit.blade.php
     SERVICIO: Agencia de Viajes — Formulario de Edición de Reserva
     Basado en create.blade.php — valores precargados desde $reserva
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Editar Reserva #' . $reserva->codigo_reserva)

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

    --orange:    #d97706;
    --orange-l:  #fff7ed;
    --orange-m:  #fed7aa;

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
    width: 210px;
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
    display: flex;
    align-items: center;
    gap: .4rem;
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
    flex: 1;
    min-width: 0;
}

.ps-label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--ink-4);
    line-height: 1.2;
    transition: color .2s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ps-sublabel {
    font-size: .63rem;
    color: var(--ink-4);
    opacity: 0;
    transition: opacity .2s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Estado activo */
.ps-item.active .ps-num {
    box-shadow: 0 0 0 4px rgba(252, 174, 8, 0.96);
}
.ps-item.active:not(.done):not(.has-error) .ps-num {
    border-color: var(--gold);
    background: var(--gold);
    color: #fff;
}
.ps-item.active:not(.done):not(.has-error) .ps-label { color: var(--ink-2); font-weight: 700; }
.ps-item.active:not(.done):not(.has-error) .ps-sublabel { opacity: 1; color: var(--gold-d); }
.ps-item.active:not(.done):not(.has-error) { background: var(--gold-l); }

/* Estado activo + completado */
.ps-item.active.done { background: var(--blue-l); }
.ps-item.active.done .ps-sublabel { opacity: 1; color: var(--blue); }
.ps-item.active.done .ps-label { color: var(--blue-d); font-weight: 700; }

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

.ps-ok-badge {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: var(--blue);
    color: #fff;
    font-size: .55rem;
    display: none;
    align-items: center;
    justify-content: center;
    margin-left: auto;
    flex-shrink: 0;
}
.ps-item.done .ps-ok-badge { display: flex; }
.ps-item.has-error .ps-ok-badge { display: none; }

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
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ps-progress-bar {
    height: 6px;
    background: var(--border);
    border-radius: 999px;
    overflow: hidden;
}
.ps-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--blue) 0%, var(--blue) 60%, var(--gold) 100%);
    border-radius: 999px;
    transition: width .5s cubic-bezier(.4,0,.2,1);
    width: 0%;
}
.ps-pct {
    font-size: .72rem;
    font-weight: 700;
    color: var(--blue);
    margin-top: .3rem;
    text-align: right;
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
    .ps-item { flex: 1; min-width: 70px; justify-content: center; text-align: center; flex-direction: column; gap: .25rem; padding: .4rem .2rem; }
    .ps-item:not(:last-child)::after { display: none; }
    .ps-info { align-items: center; }
    .ps-sublabel { display: none; }
    .ps-ok-badge, .ps-err-badge { display: none !important; }
}

/* ═══════════════════════════════
   HEADER DE PÁGINA — modo edición (ámbar/naranja)
═══════════════════════════════ */
.ph {
    background: linear-gradient(135deg, #7c3a00 0%, #b45309 55%, #d97706 100%);
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
    content: '\F4CA';
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
.ph-meta {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
    margin-top: .6rem;
}
.ph-badge {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 700;
    background: rgba(255,255,255,.15);
    color: #fff;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,.25);
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
   ALERTA MODO EDICIÓN
═══════════════════════════════ */
.alerta-edit {
    display: flex;
    gap: .75rem;
    align-items: flex-start;
    background: var(--orange-l);
    border: 1.5px solid var(--orange-m);
    border-left: 4px solid var(--orange);
    border-radius: var(--r);
    padding: .85rem 1rem;
    margin-bottom: 1.25rem;
}
.alerta-edit .ae-ico { font-size: 1.1rem; color: var(--orange); flex-shrink: 0; margin-top: .1rem; }
.alerta-edit .ae-txt { font-size: .8rem; color: #92400e; line-height: 1.5; }
.alerta-edit .ae-txt strong { display: block; margin-bottom: .1rem; font-weight: 700; font-size: .82rem; }

/* ═══════════════════════════════
   BLOQUE NUEVO PAGO (solo edición)
═══════════════════════════════ */
.pago-extra-toggle {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: 1rem;
    background: var(--green-l);
    border: 1.5px solid var(--green-m);
    border-radius: var(--r);
    margin-bottom: 1rem;
    cursor: pointer;
    user-select: none;
    transition: all .2s;
}
.pago-extra-toggle:hover { background: #d1fae5; }
.pago-extra-toggle .pet-check {
    width: 22px; height: 22px;
    border: 2px solid var(--green-m);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    background: white;
    flex-shrink: 0;
    transition: all .15s;
    font-size: .85rem;
    color: transparent;
}
.pago-extra-toggle.active .pet-check {
    background: var(--green);
    border-color: var(--green);
    color: white;
}
.pago-extra-toggle .pet-txt {
    font-size: .84rem;
    font-weight: 700;
    color: var(--green);
}
.pago-extra-toggle .pet-sub {
    font-size: .74rem;
    color: #065f46;
    font-weight: 400;
}
.pago-extra-panel {
    display: none;
    background: var(--green-l);
    border: 1.5px solid var(--green-m);
    border-top: none;
    border-radius: 0 0 var(--r) var(--r);
    padding: 1rem;
    margin-top: -1px;
    margin-bottom: 1rem;
    animation: slideDown .25s ease;
}
.pago-extra-panel.open { display: block; }

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
.fb.is-complete {
    border-color: var(--blue-m);
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
.fb-head .ico.green {
    background: var(--blue-l);
    color: var(--blue);
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
.fb-status {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--border);
    color: var(--ink-4);
    font-size: .75rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-left: auto;
    transition: all .3s;
}
.fb.is-complete .fb-status {
    background: var(--blue);
    color: #fff;
}
.fb.has-errors .fb-status {
    background: var(--red);
    color: #fff;
}
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
    border-color: var(--blue);
    background: var(--blue-l);
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
.eg-cancel-row { margin-top: .5rem; }
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
    border-color: var(--blue);
    background: var(--blue-l);
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
.eo.e-confirmada.sel {
    border-color: var(--blue);
    background: var(--blue-l);
    color: var(--blue-d);
    box-shadow: 0 0 0 3px rgba(26,79,160,.18), 0 4px 16px rgba(26,79,160,.12);
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
.eo.e-pagado.sel .eo-check   { opacity: 1; background: var(--green); color: white; }
.eo.e-confirmada.sel .eo-check { opacity: 1; background: var(--blue); color: white; }
.eo.e-mitad.sel  .eo-check   { opacity: 1; background: var(--gold); color: white; }
.eo.e-cancel.sel .eo-check   { opacity: 1; background: var(--red); color: white; }

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
.pax-card.pax-incomplete { border-color: var(--red-m); }
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
.btn-add:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }

/* ═══════════════════════════════
   CHECKBOXES DE NOTIFICACIÓN
═══════════════════════════════ */
.notif-checks { display: flex; gap: .65rem; flex-wrap: wrap; }
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
.notif-item:hover { border-color: var(--blue); color: var(--ink-2); }
.notif-item.checked { border-color: var(--blue); background: var(--blue-l); color: var(--blue); }
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
.salud-status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--blue);
    margin-left: auto;
    flex-shrink: 0;
    transition: background .3s;
}
.salud-pasajero.salud-error .salud-status-dot { background: var(--red); }
.salud-pasajero.salud-ok    .salud-status-dot { background: var(--green); }
.salud-pasajero-body { padding: 1rem; }
.salud-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; align-items: start; }
.salud-alerg-col, .salud-restrict-col { display: flex; flex-direction: column; }
.salud-alerg-col .lbl, .salud-restrict-col .lbl { height: 1rem; margin-bottom: .45rem; }
.salud-alerg-col .pg { margin-bottom: .5rem; min-height: 34px; }
.salud-alerg-col textarea.fi, .salud-restrict-col textarea.fi {
    flex: 1;
    min-height: 76px;
    height: 76px;
    resize: none;
    overflow: hidden;
}
.salud-restrict-col::before { content: ''; display: block; height: calc(34px + .5rem); }
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
.uz:hover, .uz.over { border-color: var(--blue); background: var(--blue-l); }
.uz input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.uz .uzi { font-size: 1.9rem; color: var(--ink-4); margin-bottom: .4rem; }
.uz .uzt { font-size: .82rem; color: var(--ink-3); font-weight: 500; }
.uz .uzs { font-size: .69rem; color: var(--ink-4); margin-top: .2rem; }
.fprev { display: none; margin-top: .75rem; border: 1.5px solid var(--border); border-radius: var(--r); overflow: hidden; }
.fprev.v { display: block; }
.fprev img { width: 100%; max-height: 200px; object-fit: contain; background: var(--bg); display: block; }
.fprev-ft { background: var(--ink); padding: .5rem .9rem; display: flex; justify-content: space-between; align-items: center; }
.fprev-ft .fn { font-size: .72rem; color: rgba(255,255,255,.7); font-family: 'DM Mono', monospace; }
.fprev-ft .fr { background: none; border: none; color: rgba(255,255,255,.45); cursor: pointer; font-size: .72rem; transition: color .15s; }
.fprev-ft .fr:hover { color: #f87171; }

/* Voucher existente (en edición) */
.voucher-actual {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1rem;
    background: var(--blue-l);
    border: 1.5px solid var(--blue-m);
    border-radius: var(--r);
    margin-bottom: .75rem;
}
.voucher-actual .va-ico { font-size: 1.3rem; color: var(--blue); flex-shrink: 0; }
.voucher-actual .va-info { flex: 1; }
.voucher-actual .va-label { font-size: .73rem; font-weight: 700; color: var(--blue-d); }
.voucher-actual .va-sub { font-size: .7rem; color: var(--ink-3); margin-top: 2px; }
.voucher-actual .va-link {
    font-size: .75rem;
    font-weight: 600;
    color: var(--blue);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: 4px 10px;
    border: 1px solid var(--blue-m);
    border-radius: 6px;
    background: white;
    transition: all .15s;
}
.voucher-actual .va-link:hover { background: var(--blue); color: white; }

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
    background: var(--orange);
    color: #fff;
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
    background: #b45309;
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(217,119,6,.35);
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
   BOTONES DE POLÍTICA
═══════════════════════════════ */
.politica-btns { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem; }
.btn-politica {
    display: inline-flex;
    align-items: center;
    gap: .55rem;
    padding: 10px 20px;
    border: 2px solid var(--blue-m);
    border-radius: var(--r);
    background: var(--blue-l);
    color: var(--blue);
    font-size: .82rem;
    font-weight: 700;
    font-family: 'Sora', sans-serif;
    cursor: pointer;
    transition: all .2s;
    user-select: none;
    flex-shrink: 0;
}
.btn-politica:hover { background: var(--blue); color: #fff; border-color: var(--blue); transform: translateY(-1px); box-shadow: var(--sh-md); }
.btn-politica.active { background: var(--blue); color: #fff; border-color: var(--blue-d); box-shadow: 0 0 0 3px rgba(26,79,160,.2); }
.btn-politica .bp-ico { font-size: 1.1rem; flex-shrink: 0; }
.politica-textarea-wrap { position: relative; }
.politica-loaded-badge {
    display: none;
    align-items: center;
    gap: .4rem;
    font-size: .7rem;
    font-weight: 700;
    color: var(--blue);
    background: var(--blue-l);
    border: 1px solid var(--blue-m);
    border-radius: 999px;
    padding: 3px 10px;
    position: absolute;
    top: -10px;
    right: 8px;
    z-index: 1;
}
.politica-loaded-badge.visible { display: inline-flex; }
#politica_descripcion { min-height: 180px; font-size: .82rem; line-height: 1.65; }

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
.sbar .si-val span { color: var(--orange); font-size: .75rem; font-weight: 700; margin-left: .3rem; }
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

/* Validación en tiempo real */
.fi.validating { border-color: var(--gold); }
.field-status { font-size: .69rem; margin-top: .3rem; display: flex; align-items: center; gap: .25rem; font-weight: 600; }
.field-status.s-ok  { color: var(--blue); }
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

html { scroll-behavior: smooth; }
</style>
@endpush

@section('contenido')

{{-- Preparar datos del titular desde $reserva->cliente --}}
@php
    $cliente      = $reserva->cliente;
    $estadoSlug   = $reserva->estado?->slug ?? 'mitad_pago';
    $tienePago    = $reserva->pagos?->first();
    $metodoSlug   = $tienePago?->metodoPago?->slug ?? '';
    $pasajeros    = $reserva->pasajeros ?? collect();
    // Email split para widget
    $emailCompleto = $cliente?->email ?? '';
    $emailParts    = explode('@', $emailCompleto, 2);
    $emailUser     = $emailParts[0] ?? '';
    $emailDomain   = $emailParts[1] ?? '';
    // Voucher existente
    $voucherExiste = $reserva->archivo_baucher ?? null;
    $voucherUrl    = $voucherExiste ? Storage::url($voucherExiste) : null;
    // Alergias titular
    $titularAlergias = $reserva->alergias_titular ?? '';
    $titularTieneAlergias = !empty($titularAlergias) ? 'si' : 'no';
@endphp

<div class="page-layout">

{{-- ══════════════════════════════════
     BARRA DE PROGRESO LATERAL (STICKY)
══════════════════════════════════ --}}
<aside class="progress-sidebar">
    <div class="progress-sidebar-inner">
        <div class="ps-title"><i class="bi bi-pencil-square"></i> Editando</div>
        <div class="progress-steps-v">

            <div class="ps-item active" id="ps-1" onclick="scrollToBloque(1)" title="Datos del viaje">
                <div class="ps-num">1</div>
                <div class="ps-info">
                    <span class="ps-label">Viaje</span>
                    <span class="ps-sublabel">Servicio y fechas</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

            <div class="ps-item" id="ps-2" onclick="scrollToBloque(2)" title="Pasajero titular">
                <div class="ps-num">2</div>
                <div class="ps-info">
                    <span class="ps-label">Titular</span>
                    <span class="ps-sublabel">Contacto principal</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

            <div class="ps-item" id="ps-3" onclick="scrollToBloque(3)" title="Pasajeros adicionales">
                <div class="ps-num">3</div>
                <div class="ps-info">
                    <span class="ps-label">Pasajeros</span>
                    <span class="ps-sublabel">Grupo de viaje</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

            <div class="ps-item" id="ps-4" onclick="scrollToBloque(4)" title="Salud y seguridad">
                <div class="ps-num">4</div>
                <div class="ps-info">
                    <span class="ps-label">Salud</span>
                    <span class="ps-sublabel">Info médica</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

            <div class="ps-item" id="ps-5" onclick="scrollToBloque(5)" title="Pago y comprobante">
                <div class="ps-num">5</div>
                <div class="ps-info">
                    <span class="ps-label">Pago</span>
                    <span class="ps-sublabel">Comprobante</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

            <div class="ps-item" id="ps-6" onclick="scrollToBloque(6)" title="Logística">
                <div class="ps-num">6</div>
                <div class="ps-info">
                    <span class="ps-label">Logística</span>
                    <span class="ps-sublabel">Encuentro y guía</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

            <div class="ps-item" id="ps-7" onclick="scrollToBloque(7)" title="Políticas y Privacidad">
                <div class="ps-num">7</div>
                <div class="ps-info">
                    <span class="ps-label">Políticas</span>
                    <span class="ps-sublabel">Términos</span>
                </div>
                <span class="ps-ok-badge"><i class="bi bi-check2"></i></span>
                <span class="ps-err-badge"></span>
            </div>

        </div>
        <div class="ps-footer">
            <div class="ps-footer-label">
                <span>Completado</span>
                <span id="ps-done-count" style="font-weight:700;color:var(--blue)">0/7</span>
            </div>
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

{{-- HEADER DE PÁGINA — modo edición --}}
<div class="ph">
    <div>
        <div class="ph-eyebrow"><i class="bi bi-pencil-square"></i> Agencia de Viajes — Editar Reserva</div>
        <h1>Editar Reserva</h1>
        <p>Modifica los datos del viaje, pasajeros, pago y logística.</p>
        <div class="ph-meta">
            <span class="ph-badge"><i class="bi bi-hash"></i> {{ $reserva->codigo_reserva }}</span>
            <span class="ph-badge"><i class="bi bi-person"></i> {{ $cliente?->nombre_completo ?? 'Sin titular' }}</span>
            <span class="ph-badge"><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y') }}</span>
            <span class="ph-badge"><i class="bi bi-circle-fill" style="font-size:.5rem"></i> {{ ucfirst(str_replace('_', ' ', $estadoSlug)) }}</span>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:.5rem;align-items:flex-end">
        <a href="{{ route('reservas.show', $reserva) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Ver detalle
        </a>
        <a href="{{ route('reservas.index') }}" class="btn-back" style="font-size:.73rem;padding:6px 12px;opacity:.8">
            <i class="bi bi-list-ul"></i> Todas las reservas
        </a>
    </div>
</div>

{{-- ALERTA MODO EDICIÓN --}}
<div class="alerta-edit">
    <i class="bi bi-pencil-square ae-ico"></i>
    <div class="ae-txt">
        <strong>Modo edición — Reserva #{{ $reserva->codigo_reserva }}</strong>
        Estás modificando una reserva existente. Los campos precargados provienen de los datos guardados.
        Si agregas un nuevo pago, este se registrará en el historial de pagos.
        El comprobante solo es obligatorio si adjuntas uno nuevo.
    </div>
</div>

@if($errors->any())
<div class="lerr">
    <strong><i class="bi bi-exclamation-triangle me-1"></i> Corrige los siguientes errores antes de guardar:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="POST"
      action="{{ route('reservas.update', $reserva) }}"
      enctype="multipart/form-data"
      id="form-reserva"
      novalidate>
@csrf
@method('PUT')

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
        <div class="fb-status" id="fb-status-1" title="Estado de sección">1</div>
    </div>
    <div class="fb-body">

        <div class="st">Información del servicio</div>

        <div class="g3" style="margin-bottom:1rem">
            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="nombre_servicio">Nombre del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('nombre_tour')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-briefcase"></i></span>
                    <input type="text" name="nombre_tour" id="nombre_servicio"
                        value="{{ old('nombre_tour', $reserva->nombre_tour) }}"
                        class="fi {{ $errors->has('nombre_tour')?'err':'' }}"
                        placeholder="Ej: Paquete Lima – Cusco 5D/4N"
                        required maxlength="200"
                        data-validate="required"
                        data-bloque="1">
                </div>
                @error('nombre_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="precio_tour">Precio del servicio <span class="req">*</span></label>
                <div class="ig {{ $errors->has('precio_tour')?'err-group':'' }}">
                    <span class="ia">S/</span>
                    <input type="number" name="precio_tour" id="precio_tour"
                        value="{{ old('precio_tour', $reserva->precio_total) }}"
                        class="fi {{ $errors->has('precio_tour')?'err':'' }}"
                        step="0.01" min="0" placeholder="0.00"
                        required inputmode="decimal"
                        oninput="this.value=this.value.replace(/[^0-9.]/g,'');calcTotal()"
                        data-validate="required|numeric"
                        data-bloque="1">
                </div>
                @error('precio_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="ciudad_procedencia">Ciudad de origen <span class="req">*</span></label>
                <div class="ig {{ $errors->has('ciudad_procedencia')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-geo-alt"></i></span>
                    <input type="text" name="ciudad_procedencia" id="ciudad_procedencia"
                        value="{{ old('ciudad_procedencia', $reserva->ciudad_procedencia) }}"
                        class="fi {{ $errors->has('ciudad_procedencia')?'err':'' }}"
                        placeholder="Lima, Cusco, Arequipa..."
                        required maxlength="100"
                        data-validate="required"
                        data-bloque="1">
                </div>
                @error('ciudad_procedencia')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="pp">
            <div class="pp-item">
                <div class="pp-l">Precio total</div>
                <div class="pp-v" id="pp-total">S/ {{ number_format($reserva->precio_total, 2) }}</div>
            </div>
            <div class="pp-item">
                <div class="pp-l">Ya pagado</div>
                <div class="pp-v a" id="pp-adel">S/ {{ number_format($reserva->monto_pagado, 2) }}</div>
            </div>
            <div class="pp-item">
                <div class="pp-l">Saldo pendiente</div>
                <div class="pp-v r" id="pp-saldo">S/ {{ number_format(max(0, $reserva->precio_total - $reserva->monto_pagado), 2) }}</div>
            </div>
        </div>

        <div class="st" style="margin-top:1.25rem">Fechas y canal</div>

        <div class="g2">
            <div class="field" style="margin-bottom:0">
                <label class="lbl" for="fecha_tour">Fecha de salida <span class="req">*</span></label>
                <div class="ig {{ $errors->has('fecha_tour')?'err-group':'' }}">
                    <span class="ia"><i class="bi bi-calendar3"></i></span>
                    <input type="date" name="fecha_tour" id="fecha_tour"
                        value="{{ old('fecha_tour', \Carbon\Carbon::parse($reserva->fecha_tour)->format('Y-m-d')) }}"
                        class="fi {{ $errors->has('fecha_tour')?'err':'' }}"
                        required
                        data-validate="required"
                        data-bloque="1">
                </div>
                @error('fecha_tour')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
            </div>

            <div class="field" style="margin-bottom:0">
                <label class="lbl">Hora de salida <span class="req">*</span></label>
                <div class="hora-wrap">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="time" name="hora_salida" id="hora_salida_24"
                        value="{{ old('hora_salida', $reserva->hora_salida) }}"
                        class="fi fi-hora {{ $errors->has('hora_salida')?'err':'' }}"
                        required oninput="syncAmPm()"
                        data-validate="required"
                        data-bloque="1">
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
            <select name="canal_contacto" id="canal_contacto" class="fi" required
                    data-validate="required" data-bloque="1">
                @php $canalActual = old('canal_contacto', $reserva->canal_contacto); @endphp
                <option value="whatsapp"       {{ $canalActual=='whatsapp'       ?'selected':'' }}>WhatsApp</option>
                <option value="presencial"     {{ $canalActual=='presencial'     ?'selected':'' }}>Presencial</option>
                <option value="llamada"        {{ $canalActual=='llamada'        ?'selected':'' }}>Llamada telefónica</option>
                <option value="redes_sociales" {{ $canalActual=='redes_sociales' ?'selected':'' }}>Redes Sociales</option>
                <option value="web"            {{ $canalActual=='web'            ?'selected':'' }}>Página web</option>
                <option value="referido"       {{ $canalActual=='referido'       ?'selected':'' }}>Referido / Recomendación</option>
            </select>
        </div>

        <div class="st">Estado de la reserva</div>

        @php $estadoActual = old('estado_inicial', $estadoSlug); @endphp

        <div class="eg">
            <label class="eo e-mitad {{ $estadoActual=='mitad_pago'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="mitad_pago" {{ $estadoActual=='mitad_pago'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-hourglass-split" style="font-size:1.1rem;color:var(--gold-d)"></i></span>
                50% Pagado
            </label>
            <label class="eo e-pagado {{ $estadoActual=='pagado'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="pagado" {{ $estadoActual=='pagado'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-patch-check-fill" style="font-size:1.1rem;color:var(--blue)"></i></span>
                Pagado completo
            </label>
            <label class="eo e-confirmada {{ $estadoActual=='confirmada'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="confirmada" {{ $estadoActual=='confirmada'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-send-check-fill" style="font-size:1.1rem;color:var(--blue-d)"></i></span>
                Confirmada
            </label>
        </div>

        <div class="eg-cancel-row" style="margin-top:.5rem">
            <label class="eo e-cancel {{ $estadoActual=='cancelada'?'sel':'' }}" onclick="selEst(this)">
                <input type="radio" name="estado_inicial" value="cancelada" {{ $estadoActual=='cancelada'?'checked':'' }}>
                <span class="eo-check"><i class="bi bi-check-lg"></i></span>
                <span class="eo-icon"><i class="bi bi-x-circle-fill" style="font-size:1.1rem;color:var(--red)"></i></span>
                Cancelada
            </label>
            <p class="cancel-label">Selecciona solo si la reserva fue anulada.</p>
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
        <div class="fb-status" id="fb-status-2" title="Estado de sección">2</div>
    </div>
    <div class="fb-body">

        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id', $reserva->cliente_id) }}">

        <div class="st">Información personal</div>

        <div class="field">
            <label class="lbl" for="titular_nombre">Nombre completo <span class="req">*</span></label>
            <div class="ig {{ $errors->has('titular_nombre')?'err-group':'' }}">
                <span class="ia"><i class="bi bi-person"></i></span>
                <input type="text" name="titular_nombre" id="titular_nombre"
                    value="{{ old('titular_nombre', $cliente?->nombre_completo) }}"
                    class="fi {{ $errors->has('titular_nombre')?'err':'' }}"
                    placeholder="NOMBRES Y APELLIDOS COMPLETOS"
                    required maxlength="200"
                    oninput="this.value=this.value.toUpperCase();actualizarNombreTitularSalud(this.value)"
                    data-validate="required"
                    data-bloque="2">
            </div>
            @error('titular_nombre')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
        </div>

        <div class="g13">
            <div class="field">
                <label class="lbl" for="titular_tipo_documento">Tipo de doc.</label>
                @php $tipoDoc = old('titular_tipo_documento', $cliente?->tipo_documento ?? 'DNI'); @endphp
                <select name="titular_tipo_documento" id="titular_tipo_documento" class="fi">
                    <option value="DNI"       {{ $tipoDoc=='DNI'       ?'selected':'' }}>DNI</option>
                    <option value="CE"        {{ $tipoDoc=='CE'        ?'selected':'' }}>C. Extranjería</option>
                    <option value="PASAPORTE" {{ $tipoDoc=='PASAPORTE' ?'selected':'' }}>Pasaporte</option>
                    <option value="RUC"       {{ $tipoDoc=='RUC'       ?'selected':'' }}>RUC</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_numero_documento">Número de documento</label>
                <input type="text" name="titular_numero_documento" id="titular_numero_documento"
                    value="{{ old('titular_numero_documento', $cliente?->numero_documento) }}"
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
                        value="{{ old('titular_fecha_nacimiento', $cliente?->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('Y-m-d') : '') }}"
                        class="fi">
                </div>
            </div>
            <div class="field">
                <label class="lbl" for="titular_genero">Género</label>
                @php $genero = old('titular_genero', $cliente?->genero ?? ''); @endphp
                <select name="titular_genero" id="titular_genero" class="fi">
                    <option value="">— No especificar —</option>
                    <option value="M"    {{ $genero=='M'   ?'selected':'' }}>Masculino</option>
                    <option value="F"    {{ $genero=='F'   ?'selected':'' }}>Femenino</option>
                    <option value="otro" {{ $genero=='otro'?'selected':'' }}>Otro</option>
                </select>
            </div>
            <div class="field">
                <label class="lbl" for="titular_nacionalidad">Nacionalidad</label>
                <input type="text" name="titular_nacionalidad" id="titular_nacionalidad"
                    value="{{ old('titular_nacionalidad', $cliente?->nacionalidad ?? 'Peruana') }}"
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
                        value="{{ old('titular_telefono', $cliente?->telefono) }}"
                        class="fi {{ $errors->has('titular_telefono')?'err':'' }}"
                        placeholder="9XXXXXXXX" maxlength="9" inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').substring(0,9)"
                        required
                        data-validate="required|phone"
                        data-bloque="2">
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
                            value="{{ $emailUser }}"
                            placeholder="usuario" maxlength="80" autocomplete="off"
                            oninput="emailInput()" onfocus="emailInput()"
                            onblur="setTimeout(closeDomains,200)"
                            onpaste="handleEmailPaste(event)">
                        <span class="at-sign">@</span>
                        <input type="text" id="email-domain" class="fi email-domain"
                            value="{{ $emailDomain }}"
                            placeholder="dominio.com" maxlength="80" autocomplete="off"
                            oninput="joinEmail()"
                            onblur="setTimeout(closeDomains,200)">
                    </div>
                    <ul class="domain-list" id="domain-list"></ul>
                    <input type="hidden" name="titular_email" id="titular_email" value="{{ old('titular_email', $emailCompleto) }}">
                </div>
                <div class="fhint">Puedes pegar el correo completo o editarlo parte a parte.</div>
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
                    value="{{ old('titular_telefono2', $cliente?->telefono2) }}"
                    class="fi" placeholder="076-XXXXXX o 9XXXXXXXX" maxlength="15"
                    inputmode="tel"
                    oninput="this.value=this.value.replace(/[^0-9\-]/g,'')">
            </div>
        </div>

        <div class="st">Notificaciones</div>
        <div class="notif-checks">
            <label class="notif-item" id="p-wa">
                <input type="checkbox" name="notif_whatsapp" value="1" checked id="cb-wa">
                <span class="notif-box"><i class="bi bi-check2" style="font-size:.85rem"></i></span>
                <span class="notif-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </span>
                <span class="notif-text">WhatsApp</span>
            </label>
            <label class="notif-item" id="p-em">
                <input type="checkbox" name="notif_email" value="1" id="cb-em">
                <span class="notif-box"><i class="bi bi-check2" style="font-size:.85rem"></i></span>
                <span class="notif-ico"><i class="bi bi-envelope-fill" style="color:var(--blue);font-size:.95rem"></i></span>
                <span class="notif-text">Correo electrónico</span>
            </label>
        </div>
        <div class="fhint" style="margin-top:.5rem"><i class="bi bi-info-circle me-1"></i>Puedes seleccionar uno o ambos canales.</div>

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
            <p>Grupo de viaje (el titular ya está incluido). Al guardar, se reemplazarán por los que aparecen aquí.</p>
        </div>
        <div class="fb-status" id="fb-status-3" title="Estado de sección">3</div>
    </div>
    <div class="fb-body">
        <div class="alerta info">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Los pasajeros actuales se reemplazarán al guardar.</strong>
                Si necesitas conservar los mismos pasajeros, vuelve a agregarlos. El titular siempre se registra como pasajero principal automáticamente.
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
            <p>Información médica del titular del viaje</p>
        </div>
        <div class="fb-status" id="fb-status-4" title="Estado de sección">4</div>
    </div>
    <div class="fb-body">
        <div id="salud-lista">
            <div class="salud-pasajero salud-ok" id="salud-titular">
                <div class="salud-pasajero-head">
                    <i class="bi bi-person-badge" style="color:var(--blue)"></i>
                    <span>Titular —
                        <span id="salud-titular-nombre" style="font-style:italic;color:var(--ink-3)">{{ $cliente?->nombre_completo ?? 'nombre del titular' }}</span>
                    </span>
                    <span class="salud-status-dot" title="Estado: completo"></span>
                </div>
                <div class="salud-pasajero-body">
                    <div class="salud-grid">
                        <div class="salud-alerg-col">
                            <label class="lbl">Alergias o condiciones médicas</label>
                            <div class="pg" style="margin-bottom:.5rem">
                                @php $tieneAlerg = old('titular_tiene_alergias', $titularTieneAlergias); @endphp
                                <label class="pill {{ $tieneAlerg=='no'?'sel':'' }}" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="no" {{ $tieneAlerg=='no'?'checked':'' }}>
                                    <i class="bi bi-check-circle"></i> No tiene
                                </label>
                                <label class="pill {{ $tieneAlerg=='si'?'sel':'' }}" onclick="togAlergPax(this,'alerg-titular')">
                                    <input type="radio" name="titular_tiene_alergias" value="si" {{ $tieneAlerg=='si'?'checked':'' }}>
                                    <i class="bi bi-exclamation-triangle"></i> Sí, tiene
                                </label>
                            </div>
                            <div id="alerg-titular-wrap" style="{{ $tieneAlerg=='si'?'':'display:none' }}">
                                <textarea name="titular_alergias_detalle" id="alerg-titular" class="fi" rows="3"
                                    placeholder="Describe alergias, medicamentos o condiciones..."
                                    data-bloque="4">{{ old('titular_alergias_detalle', $reserva->alergias_titular) }}</textarea>
                            </div>
                        </div>
                        <div class="salud-restrict-col">
                            <label class="lbl">Restricciones alimentarias</label>
                            <textarea name="titular_restricciones" class="fi" rows="3"
                                placeholder="Vegetariano, vegano, sin gluten, halal...">{{ old('titular_restricciones', $reserva->restricciones_alimentarias_titular) }}</textarea>
                        </div>
                    </div>
                    <div class="field" style="margin-top:.75rem;margin-bottom:0">
                        <label class="lbl">Observaciones médicas adicionales</label>
                        <textarea name="titular_obs_medicas" class="fi" rows="2"
                            placeholder="Discapacidades, movilidad reducida...">{{ old('titular_obs_medicas', $reserva->observaciones_medicas_titular ?? '') }}</textarea>
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
            <p>Comprobante fiscal y registro de nuevo pago (opcional en edición)</p>
        </div>
        <div class="fb-status" id="fb-status-5" title="Estado de sección">5</div>
    </div>
    <div class="fb-body">

        <div class="st">Comprobante fiscal</div>
        <div class="g2">
            <div class="field">
                <label class="lbl" for="tipo_comprobante">Tipo de comprobante <span class="req">*</span></label>
                @php $tipoComp = old('tipo_comprobante', $reserva->tipo_comprobante ?? 'boleta'); @endphp
                <select name="tipo_comprobante" id="tipo_comprobante" class="fi" required
                        onchange="togFactura()" data-validate="required" data-bloque="5">
                    <option value="boleta"  {{ $tipoComp=='boleta' ?'selected':'' }}>Boleta de venta</option>
                    <option value="factura" {{ $tipoComp=='factura'?'selected':'' }}>Factura</option>
                </select>
            </div>
            <div id="campos-factura" style="display:{{ $tipoComp=='factura'?'grid':'none' }};grid-template-columns:1fr 2fr;gap:1rem;align-items:start">
                <div class="field" id="campo-ruc">
                    <label class="lbl" for="ruc_factura">RUC <span class="req">*</span></label>
                    <input type="text" name="ruc_factura" id="ruc_factura"
                        value="{{ old('ruc_factura', $reserva->ruc_factura) }}"
                        class="fi" placeholder="20XXXXXXXXX" maxlength="11"
                        inputmode="numeric"
                        oninput="this.value=this.value.replace(/\D/g,'').substring(0,11)">
                </div>
                <div class="field" id="campo-razon">
                    <label class="lbl" for="razon_social">Razón social <span class="req">*</span></label>
                    <input type="text" name="razon_social" id="razon_social"
                        value="{{ old('razon_social', $reserva->razon_social) }}"
                        class="fi" placeholder="EMPRESA S.A.C." maxlength="200"
                        oninput="this.value=this.value.toUpperCase()">
                </div>
            </div>
        </div>

        {{-- ── NUEVO PAGO (opcional en edición) ── --}}
        <div class="st">Registrar nuevo pago <span style="font-weight:400;color:var(--ink-4);text-transform:none;letter-spacing:0;font-size:.72rem">(opcional)</span></div>

        <div class="pago-extra-toggle" id="toggle-nuevo-pago" onclick="toggleNuevoPago()">
            <span class="pet-check" id="toggle-check"><i class="bi bi-check2"></i></span>
            <div>
                <div class="pet-txt">¿Agregar un nuevo pago?</div>
                <div class="pet-sub">Actívalo si el cliente realizó un pago adicional que deseas registrar.</div>
            </div>
        </div>

        <div class="pago-extra-panel" id="panel-nuevo-pago">
            <div class="alerta">
                <i class="bi bi-info-circle ai"></i>
                <div class="at">
                    <strong>Este pago se sumará al monto ya pagado.</strong>
                    Monto actual pagado: <strong>S/ {{ number_format($reserva->monto_pagado, 2) }}</strong>
                </div>
            </div>
            <div class="g2" style="margin-bottom:1rem">
                <div class="field">
                    <label class="lbl" for="metodo_pago">Método de pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="fi"
                            onchange="updOpHint();updateProgressSteps()">
                        <option value="">Sin nuevo pago</option>
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
                </div>
                <div class="field">
                    <label class="lbl" for="tipo_pago">Tipo de pago</label>
                    <select name="tipo_pago" id="tipo_pago" class="fi" onchange="onTipoPago()">
                        <option value="adelanto">Adelanto parcial</option>
                        <option value="pago_completo">Pago completo (saldo)</option>
                    </select>
                </div>
            </div>
            <div class="g2">
                <div class="field">
                    <label class="lbl" for="monto_pagado_inicial">Monto del nuevo pago (S/)</label>
                    <div class="ig {{ $errors->has('monto_pagado_inicial')?'err-group':'' }}">
                        <span class="ia">S/</span>
                        <input type="number" name="monto_pagado_inicial" id="monto_pagado_inicial"
                            value="{{ old('monto_pagado_inicial') }}"
                            class="fi {{ $errors->has('monto_pagado_inicial')?'err':'' }}"
                            step="0.01" min="0.01" placeholder="0.00"
                            inputmode="decimal"
                            oninput="this.value=this.value.replace(/[^0-9.]/g,'');calcTotal();updateProgressSteps()">
                    </div>
                    <div class="fhint" id="hint-adel">Saldo pendiente: S/ {{ number_format(max(0, $reserva->precio_total - $reserva->monto_pagado), 2) }}</div>
                    @error('monto_pagado_inicial')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label class="lbl">Fecha del pago</label>
                    <div class="ig">
                        <span class="ia"><i class="bi bi-calendar3"></i></span>
                        <input type="date" name="fecha_pago"
                            value="{{ old('fecha_pago', date('Y-m-d')) }}" class="fi">
                    </div>
                </div>
            </div>
            <div class="field">
                <label class="lbl">N° operación / referencia <span class="opt">(opcional)</span></label>
                <input type="text" name="numero_operacion"
                    value="{{ old('numero_operacion') }}"
                    class="fi" placeholder="Código de transacción..." maxlength="100"
                    style="max-width:380px">
                <div class="fhint" id="op-hint">Código visible en Yape, Plin o constancia bancaria</div>
            </div>
        </div>

        {{-- Voucher existente --}}
        <div class="st">Comprobante adjunto</div>

        @if($voucherUrl)
        <div class="voucher-actual">
            <i class="bi bi-file-earmark-check-fill va-ico"></i>
            <div class="va-info">
                <div class="va-label">Comprobante actual guardado</div>
                <div class="va-sub">{{ basename($reserva->archivo_baucher) }}</div>
            </div>
            <a href="{{ $voucherUrl }}" target="_blank" class="va-link">
                <i class="bi bi-eye"></i> Ver
            </a>
        </div>
        @endif

        <div class="fhint" style="margin-bottom:.75rem">
            <i class="bi bi-info-circle me-1"></i>
            @if($voucherUrl)
                Para reemplazar el comprobante, sube uno nuevo. Si no subes ninguno, se conserva el actual.
            @else
                Esta reserva no tiene comprobante. Puedes adjuntar uno aquí.
            @endif
        </div>

        <div class="uz" id="uz"
             ondragover="event.preventDefault();this.classList.add('over')"
             ondragleave="this.classList.remove('over')"
             ondrop="onDrop(event)">
            <input type="file" name="archivo_baucher" id="archivo_baucher"
                accept=".jpg,.jpeg,.png,.pdf,.webp"
                onchange="onFile(event)">
            <div class="uzi"><i class="bi bi-cloud-arrow-up"></i></div>
            <div class="uzt">Arrastra el nuevo comprobante aquí o <strong style="color:var(--blue)">haz clic para seleccionar</strong></div>
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
══════════════════════════════════ --}}
<div class="fb" id="bloque-6">
    <div class="fb-head">
        <div class="ico"><i class="bi bi-pin-map"></i></div>
        <div>
            <h3>Logística del viaje</h3>
            <p>Punto de encuentro, recojo, guía y observaciones finales</p>
        </div>
        <div class="fb-status" id="fb-status-6" title="Estado de sección">6</div>
    </div>
    <div class="fb-body">
        <div class="g2">
            <div class="field">
                <label class="lbl">Punto de encuentro</label>
                <div class="ig">
                    <span class="ia"><i class="bi bi-pin-map"></i></span>
                    <input type="text" name="punto_encuentro" id="punto_encuentro"
                        value="{{ old('punto_encuentro', $reserva->punto_encuentro) }}"
                        class="fi" placeholder="Hotel, terminal, dirección..." maxlength="200"
                        oninput="updateProgressSteps()"
                        data-bloque="6" data-logistica="true">
                </div>
            </div>
            <div class="field">
                <label class="lbl">Hora de recojo</label>
                <div class="hora-wrap">
                    <span class="ia"><i class="bi bi-clock"></i></span>
                    <input type="time" name="hora_recojo" id="hora_recojo_24"
                        value="{{ old('hora_recojo', $reserva->hora_recojo) }}"
                        class="fi fi-hora"
                        oninput="syncAmPmRecojo();updateProgressSteps()"
                        data-bloque="6" data-logistica="true">
                    <select class="ampm-sel" id="ampm-sel-recojo" onchange="syncHoraRecojo()">
                        <option value="AM">AM</option>
                        <option value="PM">PM</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field" style="max-width:400px">
            <label class="lbl">Guía / asesor asignado</label>
            <div class="ig">
                <span class="ia"><i class="bi bi-person-badge"></i></span>
                <input type="text" name="guia_asignado" id="guia_asignado"
                    value="{{ old('guia_asignado', $reserva->guia_asignado ?? '') }}"
                    class="fi" placeholder="Nombre del guía o asesor..." maxlength="150"
                    oninput="updateProgressSteps()"
                    data-bloque="6" data-logistica="true">
            </div>
        </div>

        <div class="field">
            <label class="lbl">Observaciones generales</label>
            <textarea name="observaciones" id="observaciones_generales" class="fi" rows="3"
                placeholder="Notas internas, requerimientos especiales..."
                oninput="updateProgressSteps()"
                data-bloque="6" data-logistica="true">{{ old('observaciones', $reserva->observaciones) }}</textarea>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════
     BLOQUE 7 · POLÍTICAS Y PRIVACIDAD
══════════════════════════════════ --}}
<div class="fb" id="bloque-7">
    <div class="fb-head">
        <div class="ico green"><i class="bi bi-shield-check"></i></div>
        <div>
            <h3>Políticas y Privacidad</h3>
            <p>Edita o reemplaza las políticas aplicables a esta reserva</p>
        </div>
        <div class="fb-status" id="fb-status-7" title="Estado de sección">7</div>
    </div>
    <div class="fb-body">

        <div class="alerta info">
            <i class="bi bi-info-circle ai"></i>
            <div class="at">
                <strong>Las políticas actuales están precargadas.</strong>
                Puedes editar el contenido manualmente o reemplazarlo usando los botones de tipo de política.
            </div>
        </div>

        <div class="st">Tipo de política</div>

        <div class="politica-btns">
            @php $politicaTipo = old('politica_tipo', $reserva->politica_tipo ?? ''); @endphp
            <button type="button" class="btn-politica {{ $politicaTipo=='tours'?'active':'' }}" id="btn-politica-tour" onclick="cargarPolitica('tours')">
                <span class="bp-ico"><i class="bi bi-map"></i></span>
                Políticas y Privacidad – Tours
            </button>
            <button type="button" class="btn-politica {{ $politicaTipo=='viajes'?'active':'' }}" id="btn-politica-viaje" onclick="cargarPolitica('viajes')">
                <span class="bp-ico"><i class="bi bi-airplane"></i></span>
                Políticas y Privacidad – Viajes
            </button>
        </div>

        <div class="field">
            <label class="lbl" for="politica_descripcion">
                Descripción de Políticas y Privacidad <span class="req">*</span>
            </label>
            <div class="politica-textarea-wrap">
                <span class="politica-loaded-badge" id="politica-loaded-badge">
                    <i class="bi bi-check-circle-fill"></i> Cargado automáticamente
                </span>
                <textarea name="politica_descripcion" id="politica_descripcion"
                    class="fi" rows="10"
                    placeholder="Selecciona un tipo de política arriba, o escribe el contenido manualmente..."
                    required
                    data-validate="required"
                    data-bloque="7"
                    oninput="updateProgressSteps()">{{ old('politica_descripcion', $reserva->politica_descripcion) }}</textarea>
            </div>
            <div class="fhint">
                <i class="bi bi-info-circle me-1"></i>
                Puedes editar este texto antes de guardar. Se incluirá en el PDF enviado al cliente.
            </div>
            @error('politica_descripcion')<div class="ferr"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>@enderror
        </div>

        <input type="hidden" name="politica_tipo" id="politica_tipo" value="{{ old('politica_tipo', $reserva->politica_tipo) }}">

    </div>
</div>

{{-- BARRA DE SUBMIT --}}
<div class="sbar">
    <div class="sbar-left">
        <div class="si-label">Total de la reserva</div>
        <div class="si-val" id="sb-total">S/ {{ number_format($reserva->precio_total, 2) }} <span id="sb-pasajeros"></span></div>
    </div>
    <div class="sr">
        <a href="{{ route('reservas.show', $reserva) }}" class="btn-s">
            <i class="bi bi-x"></i> Cancelar
        </a>
        <button type="submit" class="btn-p" id="btn-submit">
            <i class="bi bi-pencil-square"></i> Guardar cambios
        </button>
    </div>
</div>

</form>
</div>{{-- fin .pw --}}
</div>{{-- fin .page-layout --}}
@endsection

@push('scripts')
<script src="{{ asset('js/politicas.js') }}"></script>
<script>
/* ══════════════════════════════════════════════════════
   MODO EDICIÓN — Variables globales
══════════════════════════════════════════════════════ */

// El comprobante es opcional en edición si ya existe uno
const VOUCHER_EXISTENTE = {{ $voucherUrl ? 'true' : 'false' }};
let voucherAdjunto = false; // nuevo voucher subido en esta sesión

/* ══════════════════════════════════════════════════════
   TOGGLE NUEVO PAGO
══════════════════════════════════════════════════════ */
let nuevoPagoActivo = false;

function toggleNuevoPago() {
    nuevoPagoActivo = !nuevoPagoActivo;
    const toggle  = document.getElementById('toggle-nuevo-pago');
    const panel   = document.getElementById('panel-nuevo-pago');
    const check   = document.getElementById('toggle-check');

    toggle.classList.toggle('active', nuevoPagoActivo);
    panel.classList.toggle('open', nuevoPagoActivo);

    if (!nuevoPagoActivo) {
        // Limpiar campos de pago
        const metodo  = document.getElementById('metodo_pago');
        const monto   = document.getElementById('monto_pagado_inicial');
        if (metodo) metodo.value = '';
        if (monto)  monto.value  = '';
    }
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════
   POLÍTICAS Y PRIVACIDAD
══════════════════════════════════════════════════════ */
function cargarPolitica(tipo) {
    const ta    = document.getElementById('politica_descripcion');
    const badge = document.getElementById('politica-loaded-badge');
    const hid   = document.getElementById('politica_tipo');
    const btnT  = document.getElementById('btn-politica-tour');
    const btnV  = document.getElementById('btn-politica-viaje');

    btnT.classList.toggle('active', tipo === 'tours');
    btnV.classList.toggle('active', tipo === 'viajes');

    if (typeof window.POLITICAS_RESERVA !== 'undefined' && window.POLITICAS_RESERVA[tipo]) {
        ta.value = window.POLITICAS_RESERVA[tipo];
        hid.value = tipo;
        badge.classList.add('visible');
        ta.style.height = 'auto';
        ta.style.height = ta.scrollHeight + 'px';
        ta.classList.add('ok-val');
        updateProgressSteps();
        setTimeout(() => badge.classList.remove('visible'), 3000);
    } else {
        fetch(`/api/politicas/${tipo}`)
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (data && data.contenido) {
                    ta.value = data.contenido;
                    hid.value = tipo;
                    badge.classList.add('visible');
                    ta.style.height = 'auto';
                    ta.style.height = ta.scrollHeight + 'px';
                    ta.classList.add('ok-val');
                    updateProgressSteps();
                    setTimeout(() => badge.classList.remove('visible'), 3000);
                } else {
                    ta.value = `[No se encontró politicas.js para tipo: ${tipo}]\n\nEscribe las políticas manualmente.`;
                    hid.value = tipo;
                    updateProgressSteps();
                }
            })
            .catch(() => {
                ta.value = `[Error al cargar políticas de tipo: ${tipo}]`;
                hid.value = tipo;
                updateProgressSteps();
            });
    }
}

/* ══════════════════════════════════════════════════════
   CÁLCULO DE TOTALES
══════════════════════════════════════════════════════ */
function calcTotal() {
    const precio  = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const pagado  = {{ $reserva->monto_pagado }};
    const nuevo   = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
    const saldo   = Math.max(0, precio - pagado - nuevo);
    const fmt     = v => `S/ ${v.toFixed(2)}`;

    document.getElementById('pp-total').textContent  = fmt(precio);
    document.getElementById('pp-adel').textContent   = fmt(pagado + nuevo);
    document.getElementById('pp-saldo').textContent  = fmt(saldo);

    // Actualizar el valor visible en sbar
    const sbTotal = document.getElementById('sb-total');
    if (sbTotal) sbTotal.childNodes[0].textContent = fmt(precio) + ' ';
}

function onTipoPago() {
    const tipo   = document.getElementById('tipo_pago').value;
    const precio = parseFloat(document.getElementById('precio_tour')?.value) || 0;
    const pagado = {{ $reserva->monto_pagado }};
    const c      = document.getElementById('monto_pagado_inicial');
    if (tipo === 'pago_completo') {
        c.value = Math.max(0, precio - pagado).toFixed(2);
    } else {
        c.value = '';
    }
    calcTotal();
    updateProgressSteps();
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
            joinEmail(); closeDomains();
        }
    }
}
function pickDomain(v) { document.getElementById('email-domain').value = v; closeDomains(); joinEmail(); }
function closeDomains() { document.getElementById('domain-list').classList.remove('open'); joinEmail(); }
function joinEmail() {
    const u = document.getElementById('email-user').value.trim();
    const d = document.getElementById('email-domain').value.trim();
    document.getElementById('titular_email').value = (u && d) ? `${u}@${d}` : '';
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
                    oninput="this.value=this.value.toUpperCase();updateSaludNombre(${i},this.value);updateProgressSteps()"
                    data-pax-idx="${i}" data-pax-required="true" required>
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
                <input type="number" name="pasajeros[${i}][edad]" class="fi" min="0" max="120" placeholder="—"
                    inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').substring(0,3)">
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
    s.className = 'salud-pasajero salud-ok';
    s.id = `salud-pax-${i}`;
    s.innerHTML = `
        <div class="salud-pasajero-head">
            <i class="bi bi-person" style="color:var(--ink-3)"></i>
            <span>Pasajero ${i + 1} —
                <span id="salud-nombre-${i}" style="font-style:italic;color:var(--ink-3)">sin nombre</span>
            </span>
            <span class="salud-status-dot" title="Estado"></span>
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
                    placeholder="Discapacidades, movilidad reducida..."></textarea>
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
    if (t === 'DNI')            { inp.maxLength = 8;  inp.placeholder = '8 dígitos';   inp.oninput = () => { inp.value = inp.value.replace(/\D/g,'').substring(0, 8); }; }
    else if (t === 'CE')        { inp.maxLength = 12; inp.placeholder = 'Hasta 12';    inp.oninput = () => { inp.value = inp.value.replace(/\D/g,'').substring(0,12); }; }
    else if (t === 'PASAPORTE') { inp.maxLength = 15; inp.placeholder = 'Alfanumér.';  inp.oninput = () => { inp.value = inp.value.toUpperCase().substring(0,15); }; }
    else                        { inp.maxLength = 20; inp.placeholder = 'Opcional';    inp.oninput = null; }
}

function paxCnt() {
    const n  = document.querySelectorAll('#pax-lista .pax-card').length;
    const el = document.getElementById('pax-cnt');
    if (el) el.textContent = n > 0 ? `${n} pasajero(s) adicional(es) registrado(s).` : '';
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
    const wrap   = document.getElementById(wrapId);
    const ta     = document.getElementById(taId);
    if (wrap) {
        const show = radio && radio.value === 'si';
        wrap.style.display = show ? 'block' : 'none';
        if (ta && !show) ta.value = '';
    }
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════
   RESTRICCIONES DE CAMPOS NUMÉRICOS
══════════════════════════════════════════════════════ */
function restricDocTitular(inp) {
    const tipo = document.getElementById('titular_tipo_documento')?.value || 'DNI';
    if (tipo === 'DNI')       inp.value = inp.value.replace(/\D/g,'').substring(0,8);
    else if (tipo === 'RUC')  inp.value = inp.value.replace(/\D/g,'').substring(0,11);
    else if (tipo === 'CE')   inp.value = inp.value.replace(/\D/g,'').substring(0,12);
    else                      inp.value = inp.value.toUpperCase().substring(0,15);
}

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
    updateProgressSteps();
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
    const v = document.getElementById('metodo_pago')?.value || '';
    const h = document.getElementById('op-hint');
    if (!h) return;
    if      (v === 'yape' || v === 'plin' || v === 'tunki') h.textContent = 'Número de operación visible en la app';
    else if (v.startsWith('transf') || v.startsWith('dep')) h.textContent = 'N° de constancia o código bancario';
    else if (v.startsWith('tarjeta'))                        h.textContent = 'Últimos 4 dígitos o N° de voucher';
    else                                                      h.textContent = 'Código de referencia (opcional)';
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
        alert('El archivo supera el límite de 5 MB.');
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
    voucherAdjunto = true;
    updateProgressSteps();
}
function removeFile() {
    document.getElementById('archivo_baucher').value = '';
    document.getElementById('fprev').classList.remove('v');
    document.getElementById('uz').style.display = '';
    document.getElementById('prev-img').src = '';
    voucherAdjunto = false;
    updateProgressSteps();
}

/* ══════════════════════════════════════════════════════
   NAVEGACIÓN DESDE SIDEBAR
══════════════════════════════════════════════════════ */
function scrollToBloque(n) {
    const bloque = document.getElementById(`bloque-${n}`);
    if (bloque) {
        bloque.scrollIntoView({ behavior: 'smooth', block: 'start' });
        bloque.style.transition = 'box-shadow .3s';
        bloque.style.boxShadow  = '0 0 0 3px rgba(217,119,6,.3), var(--sh-md)';
        setTimeout(() => { bloque.style.boxShadow = ''; }, 1200);
    }
}

/* ══════════════════════════════════════════════════════
   SISTEMA DE PROGRESO — LÓGICA ADAPTADA A EDICIÓN
══════════════════════════════════════════════════════ */
const TOTAL_BLOQUES = 7;

function getBloqueStatus(n) {
    switch(n) {

        case 1: {
            const campos = [
                document.getElementById('nombre_servicio'),
                document.getElementById('precio_tour'),
                document.getElementById('ciudad_procedencia'),
                document.getElementById('fecha_tour'),
                document.getElementById('hora_salida_24'),
            ];
            const todoLleno = campos.every(el => el && el.value.trim() !== '');
            if (!todoLleno) return 'incomplete';
            const precioOk = parseFloat(document.getElementById('precio_tour')?.value) > 0;
            if (!precioOk) return 'error';
            return 'done';
        }

        case 2: {
            const nombre = document.getElementById('titular_nombre')?.value.trim() || '';
            const tel    = document.getElementById('titular_telefono')?.value.trim() || '';
            if (!nombre || !tel) return 'incomplete';
            if (!/^9\d{8}$/.test(tel)) return 'error';
            return 'done';
        }

        case 3: {
            const cards = document.querySelectorAll('#pax-lista .pax-card');
            if (cards.length === 0) return 'done';
            let todosCompletos = true;
            cards.forEach(card => {
                const idx = card.id.replace('pax-','');
                const inp = document.getElementById(`pax-nombre-${idx}`);
                const val = inp ? inp.value.trim() : '';
                if (!val) { todosCompletos = false; card.classList.add('pax-incomplete'); }
                else      { card.classList.remove('pax-incomplete'); }
            });
            return todosCompletos ? 'done' : 'incomplete';
        }

        case 4: {
            const titularRadio = document.querySelector('[name="titular_tiene_alergias"]:checked');
            if (titularRadio && titularRadio.value === 'si') {
                const detalle = document.getElementById('alerg-titular')?.value.trim() || '';
                if (!detalle) return 'error';
            }
            const cards = document.querySelectorAll('#pax-lista .pax-card');
            for (const card of cards) {
                const idx   = card.id.replace('pax-','');
                const radio = document.querySelector(`[name="pasajeros[${idx}][tiene_alergias]"]:checked`);
                if (radio && radio.value === 'si') {
                    const det = document.getElementById(`alerg-pax-${idx}`)?.value.trim() || '';
                    if (!det) {
                        const sp = document.getElementById(`salud-pax-${idx}`);
                        if (sp) { sp.classList.add('salud-error'); sp.classList.remove('salud-ok'); }
                        return 'error';
                    } else {
                        const sp = document.getElementById(`salud-pax-${idx}`);
                        if (sp) { sp.classList.remove('salud-error'); sp.classList.add('salud-ok'); }
                    }
                }
            }
            return 'done';
        }

        case 5: {
            // En edición: bloque 5 es done si tiene comprobante (existente o nuevo)
            // y si hay nuevo pago activo, debe tener método y monto
            const tieneVoucher = VOUCHER_EXISTENTE || voucherAdjunto;
            if (!tieneVoucher) return 'incomplete';

            if (nuevoPagoActivo) {
                const metodo = document.getElementById('metodo_pago')?.value.trim() || '';
                const monto  = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
                if (!metodo || monto <= 0) return 'incomplete';
            }
            return 'done';
        }

        case 6: {
            const campos = [
                document.getElementById('punto_encuentro')?.value.trim()        || '',
                document.getElementById('hora_recojo_24')?.value.trim()          || '',
                document.getElementById('guia_asignado')?.value.trim()           || '',
                document.getElementById('observaciones_generales')?.value.trim() || '',
            ];
            return campos.some(v => v !== '') ? 'done' : 'incomplete';
        }

        case 7: {
            const texto = document.getElementById('politica_descripcion')?.value.trim() || '';
            if (!texto) return 'incomplete';
            if (texto.length < 20) return 'error';
            return 'done';
        }

        default: return 'incomplete';
    }
}

function updateProgressSteps() {
    let doneCount  = 0;
    const activeIdx = getActiveBloqueIdx();

    for (let i = 1; i <= TOTAL_BLOQUES; i++) {
        const psItem = document.getElementById(`ps-${i}`);
        const bloque = document.getElementById(`bloque-${i}`);
        const fbStat = document.getElementById(`fb-status-${i}`);
        if (!psItem || !bloque) continue;

        const status = getBloqueStatus(i);

        psItem.classList.remove('done', 'has-error', 'active');
        bloque.classList.remove('has-errors', 'is-complete');
        if (fbStat) fbStat.textContent = i;

        switch(status) {
            case 'done':
                psItem.classList.add('done');
                bloque.classList.add('is-complete');
                if (fbStat) fbStat.innerHTML = '<i class="bi bi-check2"></i>';
                doneCount++;
                break;
            case 'error':
                psItem.classList.add('has-error');
                bloque.classList.add('has-errors');
                if (fbStat) fbStat.innerHTML = '<i class="bi bi-exclamation-lg"></i>';
                break;
        }

        if (i === activeIdx) psItem.classList.add('active');
    }

    const pct    = Math.round((doneCount / TOTAL_BLOQUES) * 100);
    const fill   = document.getElementById('ps-fill');
    const pctEl  = document.getElementById('ps-pct');
    const doneEl = document.getElementById('ps-done-count');
    if (fill)   fill.style.width   = pct + '%';
    if (pctEl)  pctEl.textContent  = pct + '%';
    if (doneEl) doneEl.textContent = `${doneCount}/${TOTAL_BLOQUES}`;
}

function getActiveBloqueIdx() {
    let closestIdx = 1;
    let closestTop = Infinity;
    for (let i = 1; i <= TOTAL_BLOQUES; i++) {
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
   VALIDACIÓN EN TIEMPO REAL
══════════════════════════════════════════════════════ */
function validateField(input) {
    const rules = (input.dataset.validate || '').split('|');
    let error   = '';
    for (const rule of rules) {
        if (rule === 'required' && !input.value.trim()) { error = 'Este campo es obligatorio.'; break; }
        if (rule === 'numeric' && input.value && isNaN(parseFloat(input.value))) { error = 'Ingresa un valor numérico válido.'; break; }
        if (rule === 'positive' && input.value && parseFloat(input.value) <= 0) { error = 'El valor debe ser mayor a cero.'; break; }
        if (rule === 'phone' && input.value && !/^9\d{8}$/.test(input.value)) { error = 'Debe ser 9 dígitos comenzando con 9.'; break; }
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

    // Validar nuevo pago si está activo
    if (nuevoPagoActivo) {
        const metodo = document.getElementById('metodo_pago')?.value || '';
        const monto  = parseFloat(document.getElementById('monto_pagado_inicial')?.value) || 0;
        if (!metodo || monto <= 0) {
            formValid = false;
            document.getElementById('metodo_pago')?.classList.add('err');
            document.getElementById('monto_pagado_inicial')?.classList.add('err');
        }
    }

    updateProgressSteps();

    if (!formValid) {
        e.preventDefault();
        const firstErr = this.querySelector('.err, .has-errors');
        if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    const b = document.getElementById('btn-submit');
    b.innerHTML = '<span style="display:inline-block;width:13px;height:13px;border:2px solid currentColor;border-top-color:transparent;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle;margin-right:.3rem"></span> Guardando cambios...';
    b.disabled = true;
});

/* ══════════════════════════════════════════════════════
   INICIALIZACIÓN
══════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    // Email: ya viene precargado en los inputs HTML, solo sincronizar hidden
    joinEmail();

    updOpHint();
    initNotifChecks();

    // Hora de salida AM/PM
    const horaVal = document.getElementById('hora_salida_24').value;
    if (horaVal) syncAmPm();

    // Hora de recojo AM/PM
    const horaRecojo = document.getElementById('hora_recojo_24')?.value;
    if (horaRecojo) syncAmPmRecojo();

    // Precio
    document.getElementById('precio_tour')?.addEventListener('input', calcTotal);
    document.getElementById('monto_pagado_inicial')?.addEventListener('input', calcTotal);
    calcTotal();

    // Estados
    const est = document.querySelector('[name="estado_inicial"]:checked');
    if (est) est.closest('.eo')?.classList.add('sel');

    // Titular salud
    const titularInput = document.getElementById('titular_nombre');
    if (titularInput) {
        titularInput.addEventListener('input', () => actualizarNombreTitularSalud(titularInput.value));
        actualizarNombreTitularSalud(titularInput.value);
    }

    // Factura si viene precargado
    togFactura();

    // Auto-resize textareas
    document.querySelectorAll('textarea.fi').forEach(ta => {
        ta.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
        // Forzar resize inicial
        ta.dispatchEvent(new Event('input'));
    });

    // Validación blur
    document.querySelectorAll('[data-validate]').forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('err')) validateField(input);
            else updateProgressSteps();
        });
    });

    // Progreso en cambios
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('change', updateProgressSteps);
        el.addEventListener('input',  updateProgressSteps);
    });

    // Progreso al scroll
    window.addEventListener('scroll', updateProgressSteps, { passive: true });

    // Spinner CSS
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);

    // Inicializar progreso
    updateProgressSteps();
});
</script>
@endpush