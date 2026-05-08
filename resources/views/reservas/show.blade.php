{{-- =====================================================================
     ARCHIVO: show.blade.php
     UBICACIÓN: resources/views/reservas/show.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Reserva ' . $reserva->codigo_reserva)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
    --ink: #0d1117; --ink-2: #374151; --ink-3: #6b7280; --ink-4: #9ca3af;
    --line: #e5e7eb; --line-2: #f3f4f6;
    --blue: #1d4ed8; --blue-l: #eff6ff; --blue-m: #dbeafe;
    --green: #059669; --green-l: #ecfdf5; --green-m: #6ee7b7;
    --amber: #d97706; --amber-l: #fffbeb;
    --red: #dc2626; --red-l: #fef2f2;
    --purple: #7c3aed; --purple-l: #f5f3ff;
}
body { font-family: 'DM Sans', sans-serif; }

/* ── HEADER ── */
.reserva-header {
    background: white; border: 1px solid var(--line); border-radius: 14px;
    padding: 1.25rem 1.5rem; margin-bottom: 1.25rem;
    display: flex; justify-content: space-between; align-items: center;
    gap: 1rem; flex-wrap: wrap;
}
.reserva-codigo {
    font-family: 'DM Mono', monospace; font-size: 1.35rem; font-weight: 700;
    color: var(--blue); letter-spacing: .03em;
}
.reserva-meta { font-size: .76rem; color: var(--ink-4); margin-top: .2rem; }
.reserva-meta strong { color: var(--ink-3); }

/* ── ESTADO BADGE ── */
.estado-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 6px 14px; border-radius: 999px; font-size: .8rem; font-weight: 700;
}
.est-consulta    { background:#f1f5f9;color:#475569; }
.est-pre_reserva { background:#fffbeb;color:#92400e; }
.est-confirmada  { background:var(--green-l);color:#065f46; }
.est-mitad_pago  { background:var(--blue-l);color:#1e40af; }
.est-pagado      { background:#f0fdf4;color:#15803d; }
.est-cancelada   { background:var(--red-l);color:#991b1b; }
.est-finalizada  { background:var(--purple-l);color:#5b21b6; }

/* ── CARDS ── */
.info-card {
    background:white;border:1px solid var(--line);
    border-radius:14px;overflow:hidden;margin-bottom:1.25rem;
}
.info-card-header {
    padding:.8rem 1.25rem;border-bottom:1px solid var(--line);
    font-size:.75rem;font-weight:700;color:var(--ink-3);
    text-transform:uppercase;letter-spacing:.07em;
    display:flex;align-items:center;justify-content:space-between;gap:.5rem;
    background:var(--line-2);
}
.info-card-header .hdr-left { display:flex;align-items:center;gap:.45rem; }
.info-card-body { padding:1.1rem 1.25rem; }

/* ── DATO LABEL / VALUE ── */
.dato { display:flex;flex-direction:column;gap:3px; }
.dato .lbl {
    font-size:.67rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.07em;color:var(--ink-4);
}
.dato .val { font-size:.86rem;font-weight:600;color:var(--ink);line-height:1.4; }
.dato .val.mono { font-family:'DM Mono',monospace; }
.dato .val.nd   { color:var(--ink-4);font-style:italic;font-weight:400; }

.datos-grid {
    display:grid;grid-template-columns:1fr 1fr;gap:.85rem 1.25rem;
}
.datos-grid .dato.full { grid-column:1/-1; }
.datos-grid .dato.third { grid-column:span 1; }

/* ── SECTION DIVIDER ── */
.sec-div {
    font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.09em;
    color:var(--ink-4);margin:.9rem 0 .5rem;padding-bottom:.35rem;
    border-bottom:1.5px solid var(--line);display:flex;align-items:center;gap:.4rem;
}

/* ── CLIENTE AVATAR ── */
.cliente-avatar {
    width:42px;height:42px;border-radius:50%;
    background:var(--blue-l);color:var(--blue);
    display:flex;align-items:center;justify-content:center;
    font-size:1.1rem;font-weight:700;flex-shrink:0;border:2px solid var(--blue-m);
}

/* ── TABLA INTERNA ── */
.inner-table { width:100%;border-collapse:collapse;font-size:.83rem; }
.inner-table thead th {
    padding:.55rem 1rem;font-size:.67rem;font-weight:700;
    letter-spacing:.07em;text-transform:uppercase;
    color:var(--ink-4);background:var(--line-2);
    border-bottom:1px solid var(--line);white-space:nowrap;text-align:left;
}
.inner-table thead th:first-child { padding-left:1.25rem; }
.inner-table thead th.th-center   { text-align:center; }
.inner-table tbody td {
    padding:.75rem 1rem;border-bottom:1px solid var(--line);
    color:var(--ink-2);vertical-align:middle;
}
.inner-table tbody td:first-child { padding-left:1.25rem; }
.inner-table tbody td.td-center   { text-align:center; }
.inner-table tbody tr:last-child td { border-bottom:none; }
.inner-table tbody tr:hover td     { background:#fafbff; }

/* ── PROGRESO PAGO ── */
.pago-resumen {
    display:grid;grid-template-columns:repeat(3,1fr);
    gap:0;border-top:1px solid var(--line);background:var(--line-2);
}
.pago-res-item { padding:.875rem 1.25rem;border-right:1px solid var(--line); }
.pago-res-item:last-child { border-right:none; }
.pago-res-item .pr-label {
    font-size:.67rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.07em;color:var(--ink-4);margin-bottom:.2rem;
}
.pago-res-item .pr-val {
    font-family:'DM Mono',monospace;font-size:1rem;font-weight:700;
}
.progress-wrap {
    padding:.875rem 1.25rem .5rem;display:flex;flex-direction:column;gap:.4rem;
}
.progress-header {
    display:flex;justify-content:space-between;align-items:center;
}
.progress-track {
    background:var(--line);border-radius:999px;height:10px;overflow:hidden;
}
.progress-fill {
    height:100%;border-radius:999px;transition:width .6s cubic-bezier(.4,0,.2,1);
}

/* ── TIPO / VALIDACIÓN BADGES ── */
.tipo-badge {
    display:inline-flex;align-items:center;gap:.3rem;
    padding:3px 9px;border-radius:6px;font-size:.71rem;font-weight:600;white-space:nowrap;
}
.tipo-adelanto      { background:var(--amber-l);color:#92400e; }
.tipo-saldo         { background:var(--blue-l);color:#1e40af; }
.tipo-pago_completo { background:var(--green-l);color:#065f46; }
.tipo-mitad_pago    { background:var(--blue-l);color:#1e40af; }
.val-pendiente      { background:var(--amber-l);color:#92400e; }
.val-verificado     { background:var(--green-l);color:#065f46; }
.val-rechazado      { background:var(--red-l);color:#991b1b; }

/* ── VOUCHER DESPLEGABLE ── */
.voucher-toggle {
    background:none;border:1.5px solid var(--line);border-radius:7px;
    padding:4px 10px;font-size:.72rem;font-weight:600;color:var(--ink-3);
    cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;
    font-family:'DM Sans',sans-serif;transition:all .15s;
}
.voucher-toggle:hover { border-color:var(--blue);color:var(--blue);background:var(--blue-l); }
.voucher-panel {
    display:none;margin-top:.5rem;background:var(--line-2);
    border:1px solid var(--line);border-radius:8px;padding:.75rem;text-align:center;
}
.voucher-panel.open { display:block;animation:fadeIn .2s ease; }
.voucher-panel img {
    max-width:100%;max-height:240px;border-radius:6px;
    border:1px solid var(--line);object-fit:contain;
}
.voucher-panel a.ver-full {
    display:inline-flex;align-items:center;gap:.3rem;
    margin-top:.4rem;font-size:.75rem;color:var(--blue);text-decoration:none;font-weight:600;
}
.voucher-panel a.ver-full:hover { text-decoration:underline; }
@keyframes fadeIn { from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)} }

/* ── BOTONES ── */
.btn-back {
    display:inline-flex;align-items:center;gap:.4rem;
    color:var(--ink-3);font-size:.84rem;font-weight:500;
    text-decoration:none;margin-bottom:1rem;transition:color .15s;
}
.btn-back:hover { color:var(--blue); }
.btn-editar {
    display:inline-flex;align-items:center;gap:.4rem;
    padding:7px 16px;border-radius:9px;font-size:.82rem;font-weight:600;
    background:white;color:var(--ink-2);border:1.5px solid var(--line);
    text-decoration:none;transition:all .15s;font-family:'DM Sans',sans-serif;
}
.btn-editar:hover { border-color:var(--blue);color:var(--blue);background:var(--blue-l); }
.btn-completar {
    background:var(--green);color:#fff;border:none;border-radius:8px;
    padding:8px 18px;font-size:.82rem;font-weight:700;cursor:pointer;
    display:inline-flex;align-items:center;gap:.4rem;
    font-family:'DM Sans',sans-serif;transition:background .15s;
}
.btn-completar:hover { background:#047857; }

/* ── CHIP TRANSPORTE / HOTEL ── */
.chip-info {
    display:inline-flex;align-items:center;gap:.3rem;
    background:var(--line-2);border:1px solid var(--line);
    border-radius:6px;padding:.25rem .6rem;font-size:.76rem;font-weight:600;color:var(--ink-2);
}

/* ── SALUD ── */
.salud-item {
    display:flex;align-items:flex-start;gap:.5rem;
    padding:.55rem .7rem;background:var(--amber-l);
    border:1px solid #fde68a;border-radius:8px;margin-bottom:.45rem;font-size:.79rem;
}
.salud-item:last-child { margin-bottom:0; }
.salud-item .si-lbl { font-weight:700;color:#92400e;min-width:110px;flex-shrink:0; }
.salud-item .si-val { color:var(--ink-2);line-height:1.45; }

/* ── POLITICA ── */
.politica-box {
    background:var(--line-2);border:1.5px solid var(--line);border-radius:10px;
    padding:1rem 1.1rem;font-size:.78rem;color:var(--ink-2);
    line-height:1.65;white-space:pre-wrap;max-height:320px;overflow-y:auto;
}

/* ── HISTORIAL TIMELINE ── */
.historial-timeline { padding:1rem 1.25rem; }
.timeline-item {
    display:flex;gap:.875rem;position:relative;padding-bottom:1.1rem;
}
.timeline-item:last-child { padding-bottom:0; }
.tl-left { display:flex;flex-direction:column;align-items:center;flex-shrink:0; }
.tl-dot {
    width:30px;height:30px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.75rem;flex-shrink:0;border:2px solid;
}
.tl-line { flex:1;width:2px;background:var(--line);margin:3px 0;min-height:16px; }
.tl-right { flex:1;padding-top:.15rem; }
.tl-estado { font-size:.82rem;font-weight:700; }
.tl-meta {
    font-size:.72rem;color:var(--ink-4);margin-top:.15rem;
    display:flex;gap:.6rem;flex-wrap:wrap;align-items:center;
}
.tl-motivo {
    margin-top:.35rem;background:var(--line-2);border-radius:6px;
    padding:.35rem .6rem;font-size:.73rem;color:var(--ink-3);font-style:italic;
    border-left:2px solid var(--line);
}

/* ── MODAL ── */
.modal-overlay {
    display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
    z-index:9999;align-items:center;justify-content:center;
    animation:fadeIn .15s ease;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:white;border-radius:16px;padding:2rem;width:100%;max-width:460px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);margin:1rem;
    animation:slideUp .2s ease;
}
@keyframes slideUp { from{transform:translateY(12px);opacity:0}to{transform:translateY(0);opacity:1} }
.modal-title { font-size:1.1rem;font-weight:700;color:var(--ink);margin-bottom:.35rem; }
.modal-body  { font-size:.87rem;color:var(--ink-3);line-height:1.6;margin-bottom:1.25rem; }
.modal-footer { display:flex;gap:.6rem;justify-content:flex-end; }
.modal-btn-cancel {
    padding:8px 18px;border-radius:9px;font-size:.84rem;font-weight:600;
    background:var(--line-2);color:var(--ink-3);border:1.5px solid var(--line);
    cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s;
}
.modal-btn-cancel:hover { background:var(--line);color:var(--ink-2); }
.modal-btn-confirm {
    padding:8px 20px;border-radius:9px;font-size:.84rem;font-weight:700;
    background:var(--green);color:white;border:none;
    cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .15s;
    display:flex;align-items:center;gap:.4rem;
}
.modal-btn-confirm:hover { background:#047857; }
.modal-resumen {
    background:var(--green-l);border:1.5px solid var(--green-m);border-radius:10px;
    padding:.875rem 1rem;margin:.75rem 0;font-size:.82rem;
}
.modal-resumen .mr-row {
    display:flex;justify-content:space-between;align-items:center;
    padding:.2rem 0;color:var(--ink-2);
}
.modal-resumen .mr-row .mr-lbl { color:var(--ink-4);font-size:.76rem; }
.modal-resumen .mr-row .mr-val {
    font-family:'DM Mono',monospace;font-weight:700;color:var(--green);
}

/* ── EMERGENCIA CARD ── */
.emerg-card {
    background:#fff7ed;border:1.5px solid #fed7aa;border-radius:10px;
    padding:.7rem .875rem;display:flex;flex-wrap:wrap;gap:.5rem 1.2rem;
    font-size:.79rem;
}
.emerg-card .ec-item { display:flex;flex-direction:column;gap:2px; }
.emerg-card .ec-lbl  { font-size:.63rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#9a3412; }
.emerg-card .ec-val  { font-weight:600;color:var(--ink); }

/* Responsive */
@media(max-width:576px){
    .datos-grid{grid-template-columns:1fr;}
    .pago-resumen{grid-template-columns:1fr;}
    .pago-res-item{border-right:none;border-bottom:1px solid var(--line);}
    .pago-res-item:last-child{border-bottom:none;}
}
</style>
@endpush

@section('contenido')

<a href="{{ route('reservas.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Reservas
</a>

{{-- ── HEADER ── --}}
<div class="reserva-header">
    <div style="display:flex;align-items:center;gap:.875rem;">
        <div>
            <div class="reserva-codigo">{{ $reserva->codigo_reserva }}</div>
            <div class="reserva-meta">
                Registrada el {{ $reserva->created_at->format('d/m/Y \a \l\a\s H:i') }}
                &nbsp;·&nbsp; Por
                @php
                    $admin = $reserva->usuarioAdmin;
                    $adminNombre = $admin ? trim(($admin->nombre ?? '') . ' ' . ($admin->apellido ?? '')) : 'Sistema';
                @endphp
                <strong>{{ $adminNombre }}</strong>
                &nbsp;·&nbsp;
                @php $canalIcon=['whatsapp'=>'bi-whatsapp','presencial'=>'bi-shop','llamada'=>'bi-telephone','redes_sociales'=>'bi-instagram','web'=>'bi-globe2','referido'=>'bi-people']; @endphp
                <strong>
                    <i class="bi {{ $canalIcon[$reserva->canal_contacto] ?? 'bi-chat' }}"></i>
                    {{ ucfirst(str_replace('_',' ',$reserva->canal_contacto)) }}
                </strong>
            </div>
        </div>
    </div>
    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
        @php $estadoSlug = str_replace(' ','_',strtolower($reserva->estado->nombre ?? 'consulta')); @endphp
        <span class="estado-badge est-{{ $estadoSlug }}">
            <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>
            {{ ucfirst(str_replace('_',' ',$reserva->estado->nombre ?? '')) }}
        </span>
        <a href="{{ route('reservas.edit', $reserva) }}" class="btn-editar">
            <i class="bi bi-pencil"></i> Editar
        </a>
    </div>
</div>

<div class="row g-3">

{{-- ═══════════════════════════════════════════════════
     COLUMNA IZQUIERDA
════════════════════════════════════════════════════ --}}
<div class="col-lg-8">

    {{-- ── CLIENTE + TOUR ── --}}
    <div class="row g-3 mb-3">

        {{-- CLIENTE --}}
        <div class="col-md-6">
            <div class="info-card" style="margin-bottom:0;height:100%;">
                <div class="info-card-header">
                    <div class="hdr-left"><i class="bi bi-person-circle"></i> Cliente / Titular</div>
                </div>
                <div class="info-card-body">
                    {{-- Avatar + nombre --}}
                    <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.875rem;padding-bottom:.75rem;border-bottom:1px solid var(--line);">
                        <div class="cliente-avatar">
                            {{ strtoupper(substr($reserva->cliente->nombre_completo ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.92rem;color:var(--ink);line-height:1.3;">
                                {{ $reserva->cliente->nombre_completo }}
                            </div>
                            @if($reserva->cliente->tipo_documento && $reserva->cliente->numero_documento)
                            <div style="font-size:.72rem;color:var(--ink-4);margin-top:2px;">
                                {{ $reserva->cliente->tipo_documento }}:
                                <span style="font-family:'DM Mono',monospace;font-weight:600;color:var(--ink-3);">
                                    {{ $reserva->cliente->numero_documento }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="datos-grid">
                        <div class="dato full">
                            <div class="lbl"><i class="bi bi-whatsapp" style="color:#25d366;"></i> WhatsApp</div>
                            <div class="val">
                                @if($reserva->cliente->telefono)
                                    <a href="https://wa.me/51{{ preg_replace('/\D/','',$reserva->cliente->telefono) }}"
                                       target="_blank"
                                       style="color:var(--green);text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:.3rem;">
                                        <i class="bi bi-whatsapp"></i> +51 {{ $reserva->cliente->telefono }}
                                    </a>
                                @else
                                    <span class="nd">—</span>
                                @endif
                            </div>
                        </div>

                        @if($reserva->cliente->telefono2)
                        <div class="dato full">
                            <div class="lbl">Teléfono secundario</div>
                            <div class="val">{{ $reserva->cliente->telefono2 }}</div>
                        </div>
                        @endif

                        <div class="dato full">
                            <div class="lbl">Correo electrónico</div>
                            <div class="val">
                                @if($reserva->cliente->email)
                                    <a href="mailto:{{ $reserva->cliente->email }}"
                                       style="color:var(--blue);text-decoration:none;display:inline-flex;align-items:center;gap:.3rem;">
                                        <i class="bi bi-envelope" style="font-size:.8rem;"></i>
                                        {{ $reserva->cliente->email }}
                                    </a>
                                @else
                                    <span class="nd">—</span>
                                @endif
                            </div>
                        </div>

                        @if($reserva->cliente->fecha_nacimiento)
                        <div class="dato">
                            <div class="lbl">Nacimiento</div>
                            <div class="val mono">{{ \Carbon\Carbon::parse($reserva->cliente->fecha_nacimiento)->format('d/m/Y') }}</div>
                        </div>
                        @endif

                        @if($reserva->cliente->nacionalidad)
                        <div class="dato">
                            <div class="lbl">Nacionalidad</div>
                            <div class="val">{{ $reserva->cliente->nacionalidad }}</div>
                        </div>
                        @endif
                    </div>

                    {{-- Contacto de emergencia --}}
                    @if($reserva->cliente->emergencia_nombre)
                    <div class="sec-div" style="margin-top:.9rem;"><i class="bi bi-exclamation-triangle-fill" style="color:#d97706;"></i> Emergencia</div>
                    <div class="emerg-card">
                        <div class="ec-item">
                            <div class="ec-lbl">Nombre</div>
                            <div class="ec-val">{{ $reserva->cliente->emergencia_nombre }}</div>
                        </div>
                        @if($reserva->cliente->emergencia_parentesco)
                        <div class="ec-item">
                            <div class="ec-lbl">Parentesco</div>
                            <div class="ec-val">{{ $reserva->cliente->emergencia_parentesco }}</div>
                        </div>
                        @endif
                        @if($reserva->cliente->emergencia_telefono)
                        <div class="ec-item">
                            <div class="ec-lbl">Teléfono</div>
                            <div class="ec-val">
                                <a href="tel:{{ $reserva->cliente->emergencia_telefono }}"
                                   style="color:var(--ink);text-decoration:none;">
                                    {{ $reserva->cliente->emergencia_telefono }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TOUR ── --}}
        <div class="col-md-6">
            <div class="info-card" style="margin-bottom:0;height:100%;">
                <div class="info-card-header">
                    <div class="hdr-left"><i class="bi bi-map"></i> Tour / Servicio</div>
                </div>
                <div class="info-card-body">
                    <div class="datos-grid">
                        <div class="dato full">
                            <div class="lbl">Nombre del tour</div>
                            <div class="val">{{ $reserva->nombre_tour ?? ($reserva->fechaTour?->tour?->nombre ?? '—') }}</div>
                        </div>
                        <div class="dato full">
                            <div class="lbl">Fecha y hora de salida</div>
                            <div class="val mono">
                                @php
                                    $fTour = $reserva->fecha_tour ?? $reserva->fechaTour?->fecha;
                                    $hSal  = $reserva->hora_salida ?? $reserva->fechaTour?->hora_salida;
                                @endphp
                                {{ $fTour ? \Carbon\Carbon::parse($fTour)->format('d/m/Y') : '—' }}
                                @if($hSal) &nbsp;—&nbsp; {{ substr($hSal,0,5) }} @endif
                            </div>
                        </div>
                        <div class="dato">
                            <div class="lbl">Adultos</div>
                            <div class="val">{{ $reserva->cantidad_adultos }}</div>
                        </div>
                        <div class="dato">
                            <div class="lbl">Niños</div>
                            <div class="val">{{ $reserva->cantidad_ninos > 0 ? $reserva->cantidad_ninos : '—' }}</div>
                        </div>
                        <div class="dato">
                            <div class="lbl">Total pax</div>
                            <div class="val">{{ $reserva->cantidad_adultos + $reserva->cantidad_ninos }}</div>
                        </div>
                        <div class="dato">
                            <div class="lbl">Procedencia</div>
                            <div class="val">{{ $reserva->ciudad_procedencia ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── INFORMACIÓN DE VIAJE COMPLETA ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-airplane-engines"></i> Detalles del viaje</div>
        </div>
        <div class="info-card-body">

            {{-- Destino --}}
            <div class="sec-div"><i class="bi bi-geo-alt-fill"></i> Destino</div>
            <div class="datos-grid">
                <div class="dato">
                    <div class="lbl">Ciudad destino</div>
                    <div class="val">{{ $reserva->ciudad_destino ?? '—' }}</div>
                </div>
                <div class="dato">
                    <div class="lbl">Departamento</div>
                    <div class="val">{{ $reserva->departamento_destino ?? '—' }}</div>
                </div>
            </div>

            {{-- Fechas viaje --}}
            <div class="sec-div" style="margin-top:.9rem;"><i class="bi bi-calendar3-range"></i> Fechas del viaje</div>
            <div class="datos-grid">
                <div class="dato">
                    <div class="lbl">Fecha de arribo</div>
                    <div class="val mono">
                        @if($reserva->fecha_arribo)
                            {{ \Carbon\Carbon::parse($reserva->fecha_arribo)->format('d/m/Y') }}
                        @else
                            <span class="nd">—</span>
                        @endif
                    </div>
                </div>
                <div class="dato">
                    <div class="lbl">Hora de arribo</div>
                    <div class="val mono">
                        {{ $reserva->hora_arribo ? substr($reserva->hora_arribo,0,5) : '—' }}
                    </div>
                </div>
                <div class="dato">
                    <div class="lbl">Fecha de retorno</div>
                    <div class="val mono">
                        @if($reserva->fecha_retorno)
                            {{ \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') }}
                        @else
                            <span class="nd">—</span>
                        @endif
                    </div>
                </div>
                <div class="dato">
                    <div class="lbl">Hora de retorno</div>
                    <div class="val mono">
                        {{ $reserva->hora_retorno ? substr($reserva->hora_retorno,0,5) : '—' }}
                    </div>
                </div>
                @if($reserva->dias_viaje)
                <div class="dato full">
                    <div class="lbl"><i class="bi bi-clock-history"></i> Días de viaje</div>
                    <div class="val">
                        <span class="chip-info"><i class="bi bi-sun"></i> {{ $reserva->dias_viaje }}</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- Transporte --}}
            @if($reserva->tipo_transporte)
            <div class="sec-div" style="margin-top:.9rem;">
                <i class="bi {{ $reserva->tipo_transporte === 'aereo' ? 'bi-airplane' : 'bi-bus-front' }}"></i>
                Transporte — {{ ucfirst($reserva->tipo_transporte) }}
            </div>
            <div class="datos-grid">
                @if($reserva->tipo_transporte === 'terrestre')
                    <div class="dato full">
                        <div class="lbl">Empresa de transporte</div>
                        <div class="val">{{ $reserva->empresa_transporte ?? '—' }}</div>
                    </div>
                @else
                    <div class="dato">
                        <div class="lbl">Aerolínea</div>
                        <div class="val">{{ $reserva->aerolinea ?? '—' }}</div>
                    </div>
                    <div class="dato">
                        <div class="lbl">N° vuelo</div>
                        <div class="val mono">{{ $reserva->numero_vuelo ?? '—' }}</div>
                    </div>
                    <div class="dato">
                        <div class="lbl">Salida vuelo</div>
                        <div class="val mono">{{ $reserva->hora_salida_vuelo ? substr($reserva->hora_salida_vuelo,0,5) : '—' }}</div>
                    </div>
                    <div class="dato">
                        <div class="lbl">Llegada vuelo</div>
                        <div class="val mono">{{ $reserva->hora_llegada_vuelo ? substr($reserva->hora_llegada_vuelo,0,5) : '—' }}</div>
                    </div>
                @endif
            </div>
            @endif

            {{-- Hospedaje --}}
            @if($reserva->nombre_hotel || $reserva->tipo_establecimiento || $reserva->tipo_habitacion)
            <div class="sec-div" style="margin-top:.9rem;"><i class="bi bi-building"></i> Hospedaje</div>
            <div class="datos-grid">
                @if($reserva->nombre_hotel)
                <div class="dato full">
                    <div class="lbl">Hotel / Alojamiento</div>
                    <div class="val">{{ $reserva->nombre_hotel }}</div>
                </div>
                @endif
                @if($reserva->tipo_establecimiento)
                <div class="dato">
                    <div class="lbl">Tipo</div>
                    <div class="val">
                        @php $estLabels=['hotel_2'=>'Hotel 2★','hotel_3'=>'Hotel 3★','hotel_4'=>'Hotel 4★','hotel_5'=>'Hotel 5★','hostal'=>'Hostal','apart'=>'Apart-hotel','resort'=>'Resort','ecolodge'=>'Ecolodge','albergue'=>'Albergue / Hostel']; @endphp
                        {{ $estLabels[$reserva->tipo_establecimiento] ?? $reserva->tipo_establecimiento }}
                    </div>
                </div>
                @endif
                @if($reserva->tipo_cama)
                <div class="dato">
                    <div class="lbl">Tipo de cama</div>
                    <div class="val">
                        @php $camaLabels=['KB'=>'King Bed','QB'=>'Queen Bed','TB'=>'Twin Beds']; @endphp
                        {{ $camaLabels[$reserva->tipo_cama] ?? $reserva->tipo_cama }}
                    </div>
                </div>
                @endif
                @if($reserva->tipo_habitacion)
                <div class="dato full">
                    <div class="lbl">Habitaciones</div>
                    <div class="val">{{ $reserva->tipo_habitacion }}</div>
                </div>
                @endif
                @if($reserva->plan_alimentacion)
                <div class="dato">
                    <div class="lbl">Plan alimentación</div>
                    <div class="val">
                        @php $planLabels=['RO'=>'RO — Solo habitación','BB'=>'BB — Con desayuno','HB'=>'HB — Desayuno y cena','FB'=>'FB — Pensión completa','AI'=>'AI — Todo incluido']; @endphp
                        {{ $planLabels[$reserva->plan_alimentacion] ?? $reserva->plan_alimentacion }}
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>

    {{-- ── PASAJEROS ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-people"></i> Pasajeros</div>
            <span style="font-size:.75rem;color:var(--ink-4);text-transform:none;letter-spacing:0;font-weight:400;">
                {{ $reserva->pasajeros->count() }} registrado(s)
            </span>
        </div>
        @if($reserva->pasajeros->count())
        <table class="inner-table">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>Tipo</th>
                    <th>Documento</th>
                    <th class="th-center">Edad</th>
                    <th class="th-center">Titular</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reserva->pasajeros as $pasajero)
                <tr>
                    <td style="font-weight:600;">
                        {{ $pasajero->nombre_completo }}
                        @if($pasajero->es_titular)
                            <span style="background:var(--blue-l);color:var(--blue);font-size:.65rem;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:.3rem;">TITULAR</span>
                        @endif
                    </td>
                    <td>
                        <span style="background:var(--line-2);border-radius:6px;padding:2px 8px;font-size:.74rem;font-weight:600;color:var(--ink-3);">
                            {{ ucfirst($pasajero->tipo) }}
                        </span>
                    </td>
                    <td style="font-family:'DM Mono',monospace;font-size:.78rem;">
                        {{ $pasajero->tipo_documento ? $pasajero->tipo_documento.': '.$pasajero->numero_documento : '—' }}
                    </td>
                    <td class="td-center" style="font-family:'DM Mono',monospace;">{{ $pasajero->edad ?? '—' }}</td>
                    <td class="td-center">
                        @if($pasajero->es_titular)
                            <i class="bi bi-check-circle-fill" style="color:var(--green);"></i>
                        @else
                            <span style="color:var(--ink-4);">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="padding:2rem;text-align:center;color:var(--ink-4);font-size:.84rem;">
            <i class="bi bi-people" style="font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
            Sin pasajeros registrados
        </div>
        @endif
    </div>

    {{-- ── SALUD DEL TITULAR ── --}}
    @if($reserva->alergias_titular || $reserva->restricciones_alimentarias_titular || $reserva->titular_obs_medicas)
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-heart-pulse"></i> Salud del titular</div>
        </div>
        <div class="info-card-body">
            @if($reserva->alergias_titular)
            <div class="salud-item">
                <div class="si-lbl"><i class="bi bi-exclamation-circle me-1"></i>Alergias</div>
                <div class="si-val">{{ $reserva->alergias_titular }}</div>
            </div>
            @endif
            @if($reserva->restricciones_alimentarias_titular)
            <div class="salud-item">
                <div class="si-lbl"><i class="bi bi-cup-straw me-1"></i>Restricciones</div>
                <div class="si-val">{{ $reserva->restricciones_alimentarias_titular }}</div>
            </div>
            @endif
            @if($reserva->titular_obs_medicas)
            <div class="salud-item">
                <div class="si-lbl"><i class="bi bi-file-medical me-1"></i>Obs. médicas</div>
                <div class="si-val">{{ $reserva->titular_obs_medicas }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ── COMPROBANTE ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-receipt"></i> Comprobante fiscal</div>
        </div>
        <div class="info-card-body">
            <div class="datos-grid">
                <div class="dato">
                    <div class="lbl">Tipo</div>
                    <div class="val">
                        <span style="background:{{ $reserva->tipo_comprobante==='factura' ? 'var(--purple-l)' : 'var(--green-l)' }};
                                     color:{{ $reserva->tipo_comprobante==='factura' ? 'var(--purple)' : 'var(--green)' }};
                                     border-radius:6px;padding:2px 10px;font-size:.78rem;font-weight:700;">
                            {{ ucfirst($reserva->tipo_comprobante) }}
                        </span>
                    </div>
                </div>
                @if($reserva->ruc_factura)
                <div class="dato">
                    <div class="lbl">RUC</div>
                    <div class="val mono">{{ $reserva->ruc_factura }}</div>
                </div>
                @endif
                @if($reserva->razon_social)
                <div class="dato full">
                    <div class="lbl">Razón social</div>
                    <div class="val">{{ $reserva->razon_social }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── PAGOS ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-credit-card"></i> Pagos registrados</div>
        </div>

        {{-- Barra de progreso --}}
        @php
            $total  = (float)($reserva->precio_total ?? 0);
            $pagado = $reserva->pagos->sum('monto'); // ← recalculado desde la relación real
            $saldo  = max(0, $total - $pagado);
            $pct    = $total > 0 ? min(100, round($pagado / $total * 100)) : 0;
            $barColor     = $pct >= 100 ? 'var(--green)' : ($pct >= 50 ? 'var(--blue)' : 'var(--amber)');
            $barColorHex  = $pct >= 100 ? '#059669' : ($pct >= 50 ? '#1d4ed8' : '#d97706');
            // Mostrar el banner de "completar pago" si hay saldo pendiente Y el estado no es 'pagado'/'cancelada'/'finalizada'
            $estadosNoPendiente = ['pagado','cancelada','finalizada'];
            $mostrarCompletarPago = $saldo > 0 && !in_array($estadoSlug, $estadosNoPendiente);
            $estadoPagadoId = isset($estados)
                ? ($estados->firstWhere('nombre','pagado')?->id ?? null)
                : (\App\Models\EstadoReserva::where('nombre','pagado')->value('id'));
        @endphp

        <div class="progress-wrap">
            <div class="progress-header">
                <span style="font-size:.73rem;font-weight:700;color:var(--ink-3);">Progreso de pago</span>
                <span id="pago-pct-label"
                      style="font-size:.82rem;font-weight:800;color:{{ $barColor }};font-family:'DM Mono',monospace;letter-spacing:.02em;">
                    {{ $pct }}%
                </span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="pago-progress-bar"
                     style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:.3rem;flex-wrap:wrap;gap:.25rem;">
                <span style="font-size:.7rem;color:var(--ink-4);">
                    Pagado:
                    <strong style="color:var(--green);font-family:'DM Mono',monospace;" id="pagado-label">
                        S/ {{ number_format($pagado,2) }}
                    </strong>
                </span>
                <span style="font-size:.7rem;color:var(--ink-4);">
                    Saldo:
                    <strong style="color:{{ $saldo > 0 ? 'var(--red)' : 'var(--green)' }};font-family:'DM Mono',monospace;" id="saldo-label">
                        S/ {{ number_format($saldo,2) }}
                    </strong>
                </span>
                <span style="font-size:.7rem;color:var(--ink-4);">
                    Total:
                    <strong style="font-family:'DM Mono',monospace;">S/ {{ number_format($total,2) }}</strong>
                </span>
            </div>
        </div>

        {{-- Tabla de pagos --}}
        <table class="inner-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Método</th>
                    <th class="th-center">Tipo</th>
                    <th class="th-center">Monto</th>
                    <th>N° Operación</th>
                    <th class="th-center">Voucher</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reserva->pagos as $pago)
                @php $tipoPago = str_replace(' ','_',strtolower($pago->tipo_pago ?? '')); @endphp
                <tr>
                    <td style="font-family:'DM Mono',monospace;font-size:.78rem;white-space:nowrap;">
                        {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                    </td>
                    <td style="font-size:.82rem;font-weight:500;">
                        {{ $pago->metodoPago->nombre ?? '—' }}
                    </td>
                    <td class="td-center">
                        <span class="tipo-badge tipo-{{ $tipoPago }}">
                            {{ ucfirst(str_replace('_',' ',$pago->tipo_pago)) }}
                        </span>
                    </td>
                    <td class="td-center">
                        <span style="font-family:'DM Mono',monospace;font-weight:700;color:var(--green);font-size:.9rem;">
                            S/ {{ number_format($pago->monto,2) }}
                        </span>
                    </td>
                    <td style="font-family:'DM Mono',monospace;font-size:.75rem;color:var(--ink-4);">
                        {{ $pago->numero_operacion ?? '—' }}
                    </td>
                    <td class="td-center">
                        @if($pago->archivo_baucher)
                        <div>
                            <button type="button" class="voucher-toggle"
                                    onclick="toggleVoucher('voucher-{{ $pago->id }}',this)">
                                <i class="bi bi-image"></i> Ver
                                <i class="bi bi-chevron-down" style="font-size:.65rem;"></i>
                            </button>
                            <div id="voucher-{{ $pago->id }}" class="voucher-panel">
                                @php
                                    $ext = pathinfo($pago->archivo_baucher, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($ext),['jpg','jpeg','png','webp']))
                                    <img src="{{ Storage::url($pago->archivo_baucher) }}" alt="Voucher"
                                         onerror="this.closest('.voucher-panel').innerHTML='<span style=\'font-size:.75rem;color:var(--ink-4)\'>No se pudo cargar</span>'">
                                @else
                                    <div style="padding:.5rem;font-size:.8rem;color:var(--ink-3);">
                                        <i class="bi bi-file-earmark-pdf" style="font-size:2rem;color:var(--red);display:block;margin-bottom:.3rem;"></i>
                                        Archivo PDF
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ Storage::url($pago->archivo_baucher) }}" target="_blank" class="ver-full">
                                        <i class="bi bi-box-arrow-up-right"></i> Abrir en nueva pestaña
                                    </a>
                                </div>
                            </div>
                        </div>
                        @else
                            <span style="color:var(--ink-4);font-size:.75rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:2rem;color:var(--ink-4);font-size:.84rem;">
                        <i class="bi bi-cash-stack" style="font-size:1.4rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                        Sin pagos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Resumen ── --}}
        <div class="pago-resumen">
            <div class="pago-res-item">
                <div class="pr-label">Total reserva</div>
                <div class="pr-val" style="color:var(--ink);">S/ {{ number_format($total,2) }}</div>
            </div>
            <div class="pago-res-item">
                <div class="pr-label">Total pagado</div>
                <div class="pr-val" style="color:var(--green);">S/ {{ number_format($pagado,2) }}</div>
            </div>
            <div class="pago-res-item">
                <div class="pr-label">Saldo pendiente</div>
                <div class="pr-val" style="color:{{ $saldo > 0 ? 'var(--red)' : 'var(--green)' }}">
                    S/ {{ number_format($saldo,2) }}
                </div>
            </div>
        </div>

        {{-- Banner "completar pago" → abre modal ── --}}
        @if($mostrarCompletarPago && $estadoPagadoId)
        <div style="padding:.875rem 1.25rem;border-top:1px solid var(--line);background:var(--blue-l);
                    display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
            <div style="font-size:.83rem;font-weight:600;color:#1e40af;display:flex;align-items:center;gap:.4rem;">
                <i class="bi bi-info-circle-fill"></i>
                Saldo pendiente: <strong style="font-family:'DM Mono',monospace;">S/ {{ number_format($saldo,2) }}</strong>
                ({{ 100 - $pct }}% restante)
            </div>
            <button type="button" class="btn-completar" onclick="abrirModalCompletarPago()">
                <i class="bi bi-check-circle-fill"></i> Marcar como pagado al 100%
            </button>
        </div>
        @endif

    </div>{{-- fin info-card pagos --}}

    {{-- ── POLÍTICAS ── --}}
    @if($reserva->politica_descripcion)
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-shield-check"></i> Políticas</div>
            @if($reserva->politica_tipo)
            <span style="background:var(--blue-l);color:var(--blue);border-radius:6px;padding:2px 10px;font-size:.72rem;font-weight:700;text-transform:none;letter-spacing:0;">
                {{ ucfirst($reserva->politica_tipo) }}
            </span>
            @endif
        </div>
        <div class="info-card-body">
            <div class="politica-box">{{ $reserva->politica_descripcion }}</div>
        </div>
    </div>
    @endif

    {{-- ── OBSERVACIONES ── --}}
    @if($reserva->observaciones)
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-chat-text"></i> Observaciones</div>
        </div>
        <div class="info-card-body">
            <p style="font-size:.85rem;color:var(--ink-2);line-height:1.65;margin:0;">
                {{ $reserva->observaciones }}
            </p>
        </div>
    </div>
    @endif

</div>{{-- fin col-lg-8 --}}

{{-- ═══════════════════════════════════════════════════
     COLUMNA DERECHA
════════════════════════════════════════════════════ --}}
<div class="col-lg-4">

    {{-- ── HISTORIAL DE ESTADOS ── --}}
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-clock-history"></i> Historial de estados</div>
            <span style="font-size:.74rem;color:var(--ink-4);text-transform:none;letter-spacing:0;font-weight:400;">
                {{ $reserva->historialEstados->count() }} cambio(s)
            </span>
        </div>
        <div class="historial-timeline">
            @php
                $historial = $reserva->historialEstados->sortByDesc('fecha_cambio');
                $dotColors = [
                    'consulta'    => ['bg'=>'#f1f5f9','border'=>'#9ca3af','icon'=>'bi-question-circle','text'=>'#475569'],
                    'pre_reserva' => ['bg'=>'#fffbeb','border'=>'#f59e0b','icon'=>'bi-clock','text'=>'#92400e'],
                    'confirmada'  => ['bg'=>'#ecfdf5','border'=>'#059669','icon'=>'bi-check-circle','text'=>'#065f46'],
                    'mitad_pago'  => ['bg'=>'#eff6ff','border'=>'#1d4ed8','icon'=>'bi-currency-dollar','text'=>'#1e40af'],
                    'pagado'      => ['bg'=>'#f0fdf4','border'=>'#15803d','icon'=>'bi-check-all','text'=>'#15803d'],
                    'cancelada'   => ['bg'=>'#fef2f2','border'=>'#dc2626','icon'=>'bi-x-circle','text'=>'#991b1b'],
                    'finalizada'  => ['bg'=>'#f5f3ff','border'=>'#7c3aed','icon'=>'bi-star-fill','text'=>'#5b21b6'],
                ];
            @endphp
            @forelse($historial as $h)
            @php
                $slug = strtolower(str_replace(' ','_',$h->estadoNuevo->nombre ?? 'consulta'));
                $dc   = $dotColors[$slug] ?? $dotColors['consulta'];
                $fecha = \Carbon\Carbon::parse($h->fecha_cambio);
                $slugAnterior = strtolower(str_replace(' ','_',$h->estadoAnterior->nombre ?? ''));
                // Nombre del usuario que cambió el estado
                // La relación en el modelo puede ser 'cambiadorPor' o 'usuarioAdmin'
                $quienCambio = $h->cambiadorPor ?? $h->usuarioAdmin ?? null;
                $quienNombre = $quienCambio
                    ? trim(($quienCambio->nombre ?? '') . ' ' . ($quienCambio->apellido ?? ''))
                    : 'Sistema';
            @endphp
            <div class="timeline-item">
                <div class="tl-left">
                    <div class="tl-dot"
                         style="background:{{ $dc['bg'] }};border-color:{{ $dc['border'] }};color:{{ $dc['text'] }};">
                        <i class="bi {{ $dc['icon'] }}" style="font-size:.72rem;"></i>
                    </div>
                    @if(!$loop->last)
                    <div class="tl-line"></div>
                    @endif
                </div>
                <div class="tl-right">
                    <div class="tl-estado" style="color:{{ $dc['text'] }};">
                        {{ ucfirst(str_replace('_',' ',$h->estadoNuevo->nombre ?? '')) }}
                        @if($slug === 'pagado' && $slugAnterior)
                            <span style="font-size:.65rem;font-weight:600;background:#f0fdf4;color:#15803d;padding:1px 7px;border-radius:20px;margin-left:.3rem;vertical-align:middle;">
                                ✓ Pago completo
                            </span>
                        @endif
                    </div>
                    <div class="tl-meta">
                        <span><i class="bi bi-calendar3" style="font-size:.63rem;"></i> {{ $fecha->format('d/m/Y') }}</span>
                        <span><i class="bi bi-clock" style="font-size:.63rem;"></i> {{ $fecha->format('H:i') }}</span>
                        <span><i class="bi bi-person" style="font-size:.63rem;"></i> {{ $quienNombre }}</span>
                    </div>
                    @if($slugAnterior && $slugAnterior !== $slug)
                    <div style="font-size:.68rem;color:var(--ink-4);margin-top:.2rem;display:flex;align-items:center;gap:.3rem;">
                        <span style="opacity:.7;">{{ ucfirst(str_replace('_',' ',$slugAnterior)) }}</span>
                        <i class="bi bi-arrow-right" style="font-size:.6rem;"></i>
                        <span style="color:{{ $dc['text'] }};font-weight:600;">{{ ucfirst(str_replace('_',' ',$h->estadoNuevo->nombre ?? '')) }}</span>
                    </div>
                    @endif
                    @if($h->motivo)
                    <div class="tl-motivo">"{{ $h->motivo }}"</div>
                    @endif
                </div>
            </div>
            @empty
            <div style="padding:.75rem 0 .25rem;text-align:center;color:var(--ink-4);font-size:.83rem;">
                <i class="bi bi-clock-history" style="display:block;font-size:1.4rem;margin-bottom:.4rem;opacity:.3;"></i>
                Sin historial
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── LOGÍSTICA ── --}}
    @if($reserva->logistica || $reserva->punto_encuentro || $reserva->hora_recojo)
    <div class="info-card">
        <div class="info-card-header">
            <div class="hdr-left"><i class="bi bi-geo-alt"></i> Logística</div>
        </div>
        <div class="info-card-body">
            <div class="datos-grid">
                @if($reserva->hora_recojo)
                <div class="dato">
                    <div class="lbl">Hora de recojo</div>
                    <div class="val mono">{{ substr($reserva->hora_recojo,0,5) }}</div>
                </div>
                @endif
                @if($reserva->logistica?->nombre_guia)
                <div class="dato">
                    <div class="lbl">Guía asignado</div>
                    <div class="val">{{ $reserva->logistica->nombre_guia }}</div>
                </div>
                @endif
                @if($reserva->punto_encuentro)
                <div class="dato full">
                    <div class="lbl">Punto de encuentro</div>
                    <div class="val">{{ $reserva->punto_encuentro }}</div>
                </div>
                @endif
                @if($reserva->logistica?->instrucciones_especiales)
                <div class="dato full">
                    <div class="lbl">Instrucciones</div>
                    <div class="val">{{ $reserva->logistica->instrucciones_especiales }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>{{-- fin col-lg-4 --}}
</div>{{-- fin row --}}

{{-- ═══ MODAL — COMPLETAR PAGO ════════════════════════════════════════ --}}
@if($mostrarCompletarPago && $estadoPagadoId)
<div class="modal-overlay" id="modal-completar-pago" onclick="cerrarModalSiOverlay(event)">
    <div class="modal-box" style="max-width:500px;">
 
        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div class="modal-title">
                <i class="bi bi-check-circle-fill" style="color:var(--green);margin-right:.4rem;"></i>
                Completar pago
            </div>
            <button type="button" onclick="cerrarModal()"
                    style="background:none;border:none;cursor:pointer;color:var(--ink-4);font-size:1.1rem;padding:.2rem .4rem;border-radius:6px;"
                    onmouseenter="this.style.background='var(--line-2)'"
                    onmouseleave="this.style.background='none'">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
 
        {{-- Resumen financiero --}}
        <div class="modal-resumen" style="margin-bottom:1rem;">
            <div class="mr-row">
                <span class="mr-lbl">Total reserva</span>
                <span class="mr-val">S/ {{ number_format($total,2) }}</span>
            </div>
            <div class="mr-row">
                <span class="mr-lbl">Ya pagado</span>
                <span class="mr-val">S/ {{ number_format($pagado,2) }}</span>
            </div>
            <div class="mr-row" style="border-top:1px solid var(--green-m);padding-top:.4rem;margin-top:.25rem;">
                <span class="mr-lbl" style="font-weight:700;color:var(--ink-2);">Saldo pendiente</span>
                <span class="mr-val" style="font-size:1.1rem;">S/ {{ number_format($saldo,2) }}</span>
            </div>
        </div>
 
        {{-- Tabs: dos opciones --}}
        <div style="display:flex;gap:.5rem;margin-bottom:1rem;background:var(--line-2);
                    border-radius:10px;padding:.3rem;">
            <button type="button" id="tab-btn-solo" onclick="switchTab('solo')"
                    style="flex:1;padding:.5rem .75rem;border-radius:8px;border:none;cursor:pointer;
                           font-size:.8rem;font-weight:700;font-family:'DM Sans',sans-serif;
                           background:white;color:var(--ink-2);
                           box-shadow:0 1px 3px rgba(0,0,0,.1);transition:all .15s;">
                <i class="bi bi-check-circle me-1"></i> Solo marcar pagado
            </button>
            <button type="button" id="tab-btn-pago" onclick="switchTab('pago')"
                    style="flex:1;padding:.5rem .75rem;border-radius:8px;border:none;cursor:pointer;
                           font-size:.8rem;font-weight:700;font-family:'DM Sans',sans-serif;
                           background:none;color:var(--ink-4);transition:all .15s;">
                <i class="bi bi-plus-circle me-1"></i> Registrar nuevo pago
            </button>
        </div>
 
        {{-- Panel: Solo marcar pagado --}}
        <div id="panel-solo">
            <div style="background:var(--green-l);border:1.5px solid var(--green-m);border-radius:10px;
                        padding:.875rem 1rem;font-size:.83rem;color:#065f46;line-height:1.55;">
                <i class="bi bi-info-circle-fill me-1"></i>
                Se marcará la reserva <strong>{{ $reserva->codigo_reserva }}</strong> como
                <strong>Pagada al 100%</strong> sin registrar un nuevo pago.
                El monto total quedará en
                <strong style="font-family:'DM Mono',monospace;">S/ {{ number_format($total,2) }}</strong>.
            </div>
        </div>
 
        {{-- Panel: Registrar nuevo pago --}}
        <div id="panel-pago" style="display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                <div class="field">
                    <label class="lbl" style="font-size:.72rem;font-weight:700;color:var(--ink-4);
                                              text-transform:uppercase;letter-spacing:.06em;">
                        Método de pago <span style="color:var(--red);">*</span>
                    </label>
                    <select id="modal-metodo" style="width:100%;padding:.55rem .75rem;border:1.5px solid var(--line);
                                                     border-radius:8px;font-size:.83rem;background:white;
                                                     color:var(--ink-2);font-family:'DM Sans',sans-serif;">
                        <option value="">— Seleccionar —</option>
                        <optgroup label="Efectivo">
                            <option value="efectivo">Efectivo</option>
                        </optgroup>
                        <optgroup label="Pagos digitales">
                            <option value="yape">Yape</option>
                            <option value="plin">Plin</option>
                            <option value="tunki">Tunki</option>
                        </optgroup>
                        <optgroup label="Transferencia">
                            <option value="transf_bcp">Transf. BCP</option>
                            <option value="transf_bbva">Transf. BBVA</option>
                            <option value="transf_inter">Transf. Interbank</option>
                            <option value="transf_sc">Transf. Scotiabank</option>
                            <option value="transf_bn">Transf. Banco Nación</option>
                            <option value="transf_otros">Otro banco</option>
                        </optgroup>
                        <optgroup label="Depósito">
                            <option value="dep_bcp">Depósito BCP</option>
                            <option value="dep_bbva">Depósito BBVA</option>
                            <option value="dep_inter">Depósito Interbank</option>
                            <option value="dep_otros">Depósito otro banco</option>
                        </optgroup>
                        <optgroup label="Tarjeta">
                            <option value="tarjeta_credito">Tarjeta crédito</option>
                            <option value="tarjeta_debito">Tarjeta débito</option>
                        </optgroup>
                    </select>
                </div>
 
                <div class="field">
                    <label class="lbl" style="font-size:.72rem;font-weight:700;color:var(--ink-4);
                                              text-transform:uppercase;letter-spacing:.06em;">
                        Monto (S/) <span style="color:var(--red);">*</span>
                    </label>
                    <div style="display:flex;align-items:center;border:1.5px solid var(--line);
                                border-radius:8px;overflow:hidden;background:white;">
                        <span style="padding:.55rem .65rem;background:var(--line-2);color:var(--ink-4);
                                     font-weight:700;font-size:.8rem;border-right:1px solid var(--line);">S/</span>
                        <input type="number" id="modal-monto" step="0.01" min="0.01"
                               value="{{ number_format($saldo, 2, '.', '') }}"
                               style="border:none;outline:none;padding:.55rem .75rem;font-size:.88rem;
                                      font-family:'DM Mono',monospace;font-weight:700;
                                      color:var(--ink);width:100%;background:white;">
                    </div>
                    <div style="font-size:.68rem;color:var(--ink-4);margin-top:.25rem;">
                        Saldo: <strong style="color:var(--red);font-family:'DM Mono',monospace;">S/ {{ number_format($saldo,2) }}</strong>
                    </div>
                </div>
 
                <div class="field">
                    <label class="lbl" style="font-size:.72rem;font-weight:700;color:var(--ink-4);
                                              text-transform:uppercase;letter-spacing:.06em;">
                        N° Operación <span style="font-weight:400;opacity:.6;">(opcional)</span>
                    </label>
                    <input type="text" id="modal-operacion" maxlength="100"
                           placeholder="Código de transacción..."
                           style="width:100%;padding:.55rem .75rem;border:1.5px solid var(--line);
                                  border-radius:8px;font-size:.82rem;font-family:'DM Sans',sans-serif;
                                  color:var(--ink-2);background:white;box-sizing:border-box;">
                </div>
 
                <div class="field">
                    <label class="lbl" style="font-size:.72rem;font-weight:700;color:var(--ink-4);
                                              text-transform:uppercase;letter-spacing:.06em;">
                        Voucher <span style="font-weight:400;opacity:.6;">(opcional)</span>
                    </label>
                    <label id="modal-upload-label"
                           style="display:flex;align-items:center;gap:.5rem;
                                  padding:.5rem .75rem;border:1.5px dashed var(--line);
                                  border-radius:8px;cursor:pointer;font-size:.78rem;
                                  color:var(--ink-4);background:var(--line-2);
                                  transition:all .15s;"
                           onmouseenter="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                           onmouseleave="this.style.borderColor='var(--line)';this.style.color='var(--ink-4)'">
                        <i class="bi bi-cloud-upload" style="font-size:1rem;"></i>
                        <span id="modal-upload-txt">Seleccionar archivo...</span>
                        <input type="file" id="modal-baucher" accept=".jpg,.jpeg,.png,.pdf,.webp"
                               style="display:none;" onchange="onModalFile(this)">
                    </label>
                </div>
            </div>
        </div>
 
        {{-- Error message --}}
        <div id="modal-error"
             style="display:none;margin-top:.75rem;padding:.6rem .875rem;
                    background:var(--red-l);border:1px solid #fca5a5;border-radius:8px;
                    font-size:.8rem;color:var(--red);font-weight:600;">
        </div>
 
        {{-- Footer --}}
        <div class="modal-footer" style="margin-top:1.25rem;">
            <button type="button" class="modal-btn-cancel" onclick="cerrarModal()" id="modal-btn-cancelar">
                Cancelar
            </button>
            <button type="button" class="modal-btn-confirm" id="modal-btn-confirmar"
                    onclick="ejecutarPagoCompleto()">
                <span id="modal-btn-txt">
                    <i class="bi bi-check-circle-fill"></i> Confirmar
                </span>
                <span id="modal-btn-loading" style="display:none;">
                    <i class="bi bi-hourglass-split"></i> Guardando...
                </span>
            </button>
        </div>
 
    </div>
</div>
@endif
 
 
{{-- =====================================================================
     PASO 2 — Reemplaza todo el @push('scripts') con esto:
     ===================================================================== --}}
 
@push('scripts')
<script>
/* ── Variables globales de la reserva ── */
const RESERVA_ID       = {{ $reserva->id }};
const PAGO_URL         = "{{ route('reservas.registrarPago', $reserva) }}";
const CSRF_TOKEN       = "{{ csrf_token() }}";
const PRECIO_TOTAL     = {{ (float)$reserva->precio_total }};
let   pagadoActual     = {{ $pagado }};
let   tabActual        = 'solo'; // 'solo' | 'pago'
 
/* ══════════════════════════════════════
   VOUCHER DESPLEGABLE (tabla de pagos)
══════════════════════════════════════ */
function toggleVoucher(id, btn) {
    const panel   = document.getElementById(id);
    const chevron = btn.querySelector('i:last-child');
    const isOpen  = panel.classList.contains('open');
 
    document.querySelectorAll('.voucher-panel.open').forEach(p => {
        if (p.id !== id) {
            p.classList.remove('open');
            const otherBtn = document.querySelector(`[onclick*="${p.id}"]`);
            if (otherBtn) {
                const ch = otherBtn.querySelector('i:last-child');
                if (ch) ch.className = 'bi bi-chevron-down';
            }
        }
    });
 
    panel.classList.toggle('open');
    if (chevron) {
        chevron.className = panel.classList.contains('open')
            ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    }
}
 
/* ══════════════════════════════════════
   MODAL — ABRIR / CERRAR
══════════════════════════════════════ */
function abrirModalCompletarPago() {
    const m = document.getElementById('modal-completar-pago');
    if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
    // Resetear al tab "solo" cada vez que se abre
    switchTab('solo');
    ocultarError();
}
function cerrarModal() {
    const m = document.getElementById('modal-completar-pago');
    if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
}
function cerrarModalSiOverlay(e) {
    if (e.target === e.currentTarget) cerrarModal();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });
 
/* ══════════════════════════════════════
   MODAL — TABS
══════════════════════════════════════ */
function switchTab(tab) {
    tabActual = tab;
    const btnSolo = document.getElementById('tab-btn-solo');
    const btnPago = document.getElementById('tab-btn-pago');
    const panSolo = document.getElementById('panel-solo');
    const panPago = document.getElementById('panel-pago');
 
    const activeStyle = 'background:white;color:var(--ink-2);box-shadow:0 1px 3px rgba(0,0,0,.1);';
    const inactiveStyle = 'background:none;color:var(--ink-4);box-shadow:none;';
 
    if (tab === 'solo') {
        btnSolo.style.cssText += activeStyle;
        btnPago.style.cssText += inactiveStyle;
        panSolo.style.display = 'block';
        panPago.style.display = 'none';
    } else {
        btnPago.style.cssText += activeStyle;
        btnSolo.style.cssText += inactiveStyle;
        panSolo.style.display = 'none';
        panPago.style.display = 'block';
    }
    ocultarError();
}
 
/* ══════════════════════════════════════
   MODAL — ARCHIVO VOUCHER
══════════════════════════════════════ */
function onModalFile(input) {
    const txt = document.getElementById('modal-upload-txt');
    if (input.files.length > 0) {
        const f = input.files[0];
        // Validar tamaño 5MB
        if (f.size > 5 * 1024 * 1024) {
            mostrarError('El archivo supera los 5 MB permitidos.');
            input.value = '';
            if (txt) txt.textContent = 'Seleccionar archivo...';
            return;
        }
        if (txt) txt.textContent = f.name;
    } else {
        if (txt) txt.textContent = 'Seleccionar archivo...';
    }
}
 
/* ══════════════════════════════════════
   MODAL — ERROR / LOADING
══════════════════════════════════════ */
function mostrarError(msg) {
    const el = document.getElementById('modal-error');
    if (el) { el.textContent = msg; el.style.display = 'block'; }
}
function ocultarError() {
    const el = document.getElementById('modal-error');
    if (el) el.style.display = 'none';
}
function setLoading(loading) {
    const btnTxt  = document.getElementById('modal-btn-txt');
    const btnLoad = document.getElementById('modal-btn-loading');
    const btnConf = document.getElementById('modal-btn-confirmar');
    const btnCanc = document.getElementById('modal-btn-cancelar');
    if (btnTxt)  btnTxt.style.display  = loading ? 'none'  : 'inline-flex';
    if (btnLoad) btnLoad.style.display = loading ? 'inline-flex' : 'none';
    if (btnConf) btnConf.disabled = loading;
    if (btnCanc) btnCanc.disabled = loading;
}
 
/* ══════════════════════════════════════
   MODAL — EJECUTAR PAGO (AJAX)
══════════════════════════════════════ */
async function ejecutarPagoCompleto() {
    ocultarError();
 
    // Validar campos si es tab "pago"
    if (tabActual === 'pago') {
        const metodo = document.getElementById('modal-metodo')?.value;
        const monto  = parseFloat(document.getElementById('modal-monto')?.value || 0);
        if (!metodo) { mostrarError('Selecciona un método de pago.'); return; }
        if (!monto || monto <= 0) { mostrarError('Ingresa un monto válido mayor a 0.'); return; }
    }
 
    setLoading(true);
 
    try {
        const formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        formData.append('solo_estado', tabActual === 'solo' ? '1' : '0');
 
        if (tabActual === 'pago') {
            const metodo    = document.getElementById('modal-metodo').value;
            const monto     = document.getElementById('modal-monto').value;
            const operacion = document.getElementById('modal-operacion')?.value || '';
            const baucher   = document.getElementById('modal-baucher')?.files[0];
 
            formData.append('metodo_pago',      metodo);
            formData.append('monto',            monto);
            formData.append('numero_operacion', operacion);
            if (baucher) formData.append('archivo_baucher', baucher);
        }
 
        const resp = await fetch(PAGO_URL, { method: 'POST', body: formData });
        const data = await resp.json();
 
        if (!data.ok) {
            mostrarError(data.message || 'Ocurrió un error. Intenta de nuevo.');
            setLoading(false);
            return;
        }
 
        // ── ÉXITO: animar barra y actualizar UI ──
        animarBarraAlCien();
        cerrarModal();
        mostrarBannerExito(data.message || 'Pago completado correctamente.');
        ocultarBannerSaldo();
 
        // Actualizar labels de la barra
        pagadoActual = data.monto_pagado || PRECIO_TOTAL;
        const labelPagado = document.getElementById('pagado-label');
        const labelSaldo  = document.getElementById('saldo-label');
        if (labelPagado) labelPagado.textContent = 'S/ ' + pagadoActual.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g,',');
        if (labelSaldo)  { labelSaldo.textContent = 'S/ 0.00'; labelSaldo.style.color = 'var(--green)'; }
 
        // Recargar después de 1.8s para mostrar el historial actualizado
        setTimeout(() => window.location.reload(), 1800);
 
    } catch (err) {
        mostrarError('Error de conexión. Verifica tu internet e intenta de nuevo.');
        setLoading(false);
    }
}
 
/* ══════════════════════════════════════
   ANIMACIONES POST-PAGO
══════════════════════════════════════ */
function animarBarraAlCien() {
    const bar   = document.getElementById('pago-progress-bar');
    const label = document.getElementById('pago-pct-label');
 
    if (bar) {
        bar.style.transition = 'width .8s cubic-bezier(.4,0,.2,1)';
        bar.style.width      = '100%';
        bar.style.background = 'var(--green)';
    }
    if (label) {
        // Animar el número del 0 al 100 progresivamente
        let current = {{ $pct }};
        const target  = 100;
        const step    = (target - current) / 40; // 40 frames
        const timer   = setInterval(() => {
            current = Math.min(current + step, target);
            label.textContent = Math.round(current) + '%';
            label.style.color = 'var(--green)';
            if (current >= target) clearInterval(timer);
        }, 20);
    }
}
 
function mostrarBannerExito(msg) {
    // Crear banner de éxito temporal en la parte superior
    const banner = document.createElement('div');
    banner.style.cssText = `
        position:fixed;top:1rem;left:50%;transform:translateX(-50%);
        background:var(--green);color:white;
        padding:.75rem 1.5rem;border-radius:12px;
        font-size:.88rem;font-weight:700;
        box-shadow:0 4px 20px rgba(5,150,105,.4);
        z-index:99999;display:flex;align-items:center;gap:.5rem;
        animation:slideDown .3s ease;
    `;
    banner.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + msg;
    document.body.appendChild(banner);
 
    // Agregar animación CSS si no existe
    if (!document.getElementById('banner-anim-style')) {
        const s = document.createElement('style');
        s.id = 'banner-anim-style';
        s.textContent = '@keyframes slideDown{from{opacity:0;transform:translateX(-50%) translateY(-10px)}to{opacity:1;transform:translateX(-50%) translateY(0)}}';
        document.head.appendChild(s);
    }
 
    setTimeout(() => { banner.style.opacity = '0'; banner.style.transition = 'opacity .4s'; }, 1400);
    setTimeout(() => banner.remove(), 1800);
}
 
function ocultarBannerSaldo() {
    // Ocultar el banner inferior "Saldo pendiente / Marcar como pagado"
    const banner = document.querySelector('[style*="var(--blue-l)"]');
    // Buscar más específico: el div con el botón "Marcar como pagado al 100%"
    document.querySelectorAll('.btn-completar').forEach(btn => {
        const parent = btn.closest('div');
        if (parent) {
            parent.style.transition = 'opacity .4s';
            parent.style.opacity = '0';
            setTimeout(() => parent.style.display = 'none', 400);
        }
    });
}
</script>
@endpush
@endsection