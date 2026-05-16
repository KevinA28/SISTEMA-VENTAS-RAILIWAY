@extends('layouts.app')
@section('titulo', 'Cliente')
@section('subtitulo', $cliente->nombre_mostrar)

@push('styles')
<style>
:root {
    --ink:#0d1117;--ink-2:#374151;--ink-3:#6b7280;--ink-4:#9ca3af;
    --line:#e5e7eb;--line-2:#f9fafb;
    --blue:#1d4ed8;--blue-l:#eff6ff;--blue-m:#dbeafe;
    --green:#059669;--green-l:#ecfdf5;
    --amber:#d97706;--amber-l:#fffbeb;
    --red:#dc2626;--red-l:#fef2f2;
    --purple:#7c3aed;--purple-l:#f5f3ff;
    --orange:#ea580c;--orange-l:#fff7ed;
}

/* ── LAYOUT ── */
.show-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media(max-width:900px) { .show-grid { grid-template-columns: 1fr; } }

/* ── CARD BASE ── */
.card {
    background: white;
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 1.25rem;
}
.card-head {
    padding: .875rem 1.25rem;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; justify-content: space-between;
    gap: .75rem;
}
.card-title {
    font-size: .88rem; font-weight: 700; color: var(--ink);
    display: flex; align-items: center; gap: .45rem;
}
.card-title i { color: var(--blue); }
.card-body { padding: 1.1rem 1.25rem; }

/* ── PERFIL ── */
.perfil-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; font-weight: 800;
    margin: 0 auto 1rem;
}
.perfil-nombre {
    text-align: center;
    font-size: 1rem; font-weight: 700; color: var(--ink);
    line-height: 1.2; margin-bottom: .25rem;
}
.perfil-insignia { text-align: center; margin-bottom: 1rem; }

.insignia {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: 3px 10px; border-radius: 999px;
    font-size: .7rem; font-weight: 700;
}
.ins-nuevo     { background:#f0fdf4;color:#15803d;border:1px solid #86efac; }
.ins-regular   { background:var(--blue-l);color:var(--blue);border:1px solid var(--blue-m); }
.ins-frecuente { background:var(--amber-l);color:var(--amber);border:1px solid #fcd34d; }
.ins-vip       { background:var(--purple-l);color:var(--purple);border:1px solid #c4b5fd; }

.perfil-stats {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: .5rem; margin-bottom: 1rem;
}
.pstat {
    background: var(--line-2); border-radius: 10px;
    padding: .65rem .75rem; text-align: center;
}
.pstat-val { font-size: 1.25rem; font-weight: 700; color: var(--ink); }
.pstat-lbl { font-size: .65rem; color: var(--ink-4); font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-top: 1px; }

/* ── DATOS ── */
.dato-row {
    display: flex; align-items: flex-start; gap: .6rem;
    padding: .55rem 0; border-bottom: 1px solid var(--line-2);
    font-size: .83rem;
}
.dato-row:last-child { border-bottom: none; }
.dato-ico { color: var(--ink-4); font-size: .85rem; width: 16px; text-align: center; flex-shrink: 0; margin-top: 1px; }
.dato-lbl { color: var(--ink-4); font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; min-width: 90px; flex-shrink: 0; padding-top: 1px; }
.dato-val { color: var(--ink-2); font-weight: 500; word-break: break-word; }
.dato-val.mono { font-family: 'JetBrains Mono', monospace; font-size: .78rem; }
.dato-nd { color: var(--ink-4); font-style: italic; font-size: .78rem; }

/* ── RESERVAS ── */
.reserva-item {
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: .875rem 1rem;
    margin-bottom: .65rem;
    transition: border-color .15s, box-shadow .15s;
}
.reserva-item:hover { border-color: #93c5fd; box-shadow: 0 2px 8px rgba(29,78,216,.07); }
.reserva-item:last-child { margin-bottom: 0; }

.res-top {
    display: flex; align-items: center; justify-content: space-between;
    gap: .5rem; margin-bottom: .45rem; flex-wrap: wrap;
}
.res-codigo { font-size: .72rem; font-weight: 700; color: var(--ink-4); font-family: monospace; }
.res-tour { font-size: .88rem; font-weight: 700; color: var(--ink); }
.res-fecha { font-size: .75rem; color: var(--ink-4); display: flex; align-items: center; gap: .25rem; margin-bottom: .5rem; }

.res-meta {
    display: flex; flex-wrap: wrap; gap: .5rem; align-items: center;
    margin-bottom: .5rem;
}
.res-chip {
    display: inline-flex; align-items: center; gap: .2rem;
    background: var(--line-2); border-radius: 6px;
    padding: 2px 8px; font-size: .72rem; color: var(--ink-3); font-weight: 500;
}

.res-footer {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .5rem;
}
.res-monto { font-family: monospace; font-size: .8rem; font-weight: 700; }
.res-monto .total { color: var(--green); }
.res-monto .pendiente { color: var(--amber); font-size: .72rem; margin-left: .3rem; }

/* Estado badge */
.estado-badge {
    display: inline-flex; align-items: center; gap: .25rem;
    padding: 3px 9px; border-radius: 999px;
    font-size: .68rem; font-weight: 700; border: 1px solid;
}

.btn-mini {
    padding: 4px 10px; border-radius: 7px; font-size: .73rem; font-weight: 600;
    border: 1.5px solid var(--line); color: var(--ink-2); background: white;
    text-decoration: none; transition: all .15s; display: inline-flex; align-items: center; gap: .25rem;
}
.btn-mini:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-l); }

/* ── SALUD ── */
.salud-item {
    display: flex; gap: .65rem; padding: .6rem 0;
    border-bottom: 1px solid var(--line-2);
}
.salud-item:last-child { border-bottom: none; }
.salud-ico {
    width: 30px; height: 30px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; flex-shrink: 0;
}
.salud-lbl { font-size: .72rem; font-weight: 700; color: var(--ink-4); text-transform: uppercase; letter-spacing: .04em; }
.salud-val { font-size: .83rem; color: var(--ink-2); margin-top: 1px; }

/* ── TIMELINE ── */
.timeline { position: relative; padding-left: 1.5rem; }
.timeline::before {
    content: ''; position: absolute; left: .4rem; top: 0; bottom: 0;
    width: 2px; background: var(--line); border-radius: 2px;
}
.tl-item { position: relative; margin-bottom: 1rem; }
.tl-item:last-child { margin-bottom: 0; }
.tl-dot {
    position: absolute; left: -1.15rem; top: .25rem;
    width: 10px; height: 10px; border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px var(--line);
}
.tl-fecha { font-size: .68rem; color: var(--ink-4); font-weight: 600; margin-bottom: .15rem; }
.tl-texto { font-size: .82rem; color: var(--ink-2); }
.tl-tour  { font-size: .75rem; color: var(--ink-4); margin-top: 1px; }

/* ── BTN BACK ── */
.btn-back {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: 7px 13px; border-radius: 8px; font-size: .8rem; font-weight: 600;
    border: 1.5px solid var(--line); color: var(--ink-2); background: white;
    text-decoration: none; transition: all .15s; margin-bottom: 1.1rem;
}
.btn-back:hover { border-color: var(--ink-4); color: var(--ink); }

.empty-sm { text-align: center; padding: 2rem 1rem; color: var(--ink-4); font-size: .83rem; }
.empty-sm i { font-size: 1.6rem; display: block; margin-bottom: .5rem; opacity: .35; }
</style>
@endpush

@section('contenido')

<a href="{{ route('clientes.index') }}" class="btn-back">
    <i class="bi bi-arrow-left"></i> Volver a Clientes
</a>

@php
    $reservas   = $cliente->reservas->sortByDesc('created_at');
    $totalRes   = $reservas->count();
    $totalGasto = $reservas->sum('precio_total');
    $totalPagado= $reservas->sum('monto_pagado');
    $pendiente  = $totalGasto - $totalPagado;

    // Insignia
    if ($totalRes >= 5)     { $ins = '<span class="insignia ins-vip"><i class="bi bi-gem"></i> VIP</span>'; }
    elseif ($totalRes >= 3) { $ins = '<span class="insignia ins-frecuente"><i class="bi bi-star-fill"></i> Frecuente</span>'; }
    elseif ($totalRes >= 2) { $ins = '<span class="insignia ins-regular"><i class="bi bi-arrow-repeat"></i> Regular</span>'; }
    else                     { $ins = '<span class="insignia ins-nuevo"><i class="bi bi-person-plus"></i> Nuevo</span>'; }

    // Avatar color
    if ($totalRes >= 5)     { $avBg = '#f5f3ff'; $avColor = '#7c3aed'; }
    elseif ($totalRes >= 3) { $avBg = '#fffbeb'; $avColor = '#d97706'; }
    elseif ($totalRes >= 2) { $avBg = '#eff6ff'; $avColor = '#1d4ed8'; }
    else                     { $avBg = '#f0fdf4'; $avColor = '#059669'; }

    $ini = strtoupper(substr($cliente->nombre_completo ?? $cliente->razon_social ?? '?', 0, 2));

    // Recopilar datos de salud de todos los pasajeros de todas las reservas
    $saludItems = collect();
    foreach ($reservas as $r) {
        if ($r->relationLoaded('pasajeros')) {
            foreach ($r->pasajeros as $p) {
                if ($p->salud) $saludItems->push(['reserva' => $r, 'pasajero' => $p, 'salud' => $p->salud]);
            }
        }
    }
@endphp

<div class="show-grid">

    {{-- ═══════════ COLUMNA IZQUIERDA ═══════════ --}}
    <div>

        {{-- PERFIL --}}
        <div class="card">
            <div class="card-body">
                <div class="perfil-avatar" style="background:{{ $avBg }};color:{{ $avColor }}">
                    {{ $ini }}
                </div>
                <div class="perfil-nombre">{{ $cliente->nombre_mostrar }}</div>
                @if($cliente->razon_social && $cliente->nombre_completo)
                    <div style="text-align:center;font-size:.75rem;color:var(--ink-4);margin-bottom:.5rem;">
                        {{ $cliente->nombre_completo }}
                    </div>
                @endif
                <div class="perfil-insignia">{!! $ins !!}</div>

                <div class="perfil-stats">
                    <div class="pstat">
                        <div class="pstat-val">{{ $totalRes }}</div>
                        <div class="pstat-lbl">Reservas</div>
                    </div>
                    <div class="pstat">
                        <div class="pstat-val" style="font-size:.95rem;color:var(--green)">
                            S/ {{ number_format($totalGasto, 0) }}
                        </div>
                        <div class="pstat-lbl">Total gastado</div>
                    </div>
                </div>

                @if($pendiente > 0)
                <div style="background:var(--amber-l);border:1px solid #fcd34d;border-radius:8px;padding:.5rem .75rem;text-align:center;font-size:.78rem;font-weight:600;color:var(--amber);">
                    <i class="bi bi-hourglass-split"></i>
                    Saldo pendiente: S/ {{ number_format($pendiente, 2) }}
                </div>
                @endif
            </div>
        </div>

        {{-- DATOS PERSONALES --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="bi bi-person-vcard"></i> Datos personales</div>
            </div>
            <div class="card-body" style="padding-top:.75rem;padding-bottom:.75rem;">

                <div class="dato-row">
                    <i class="bi bi-card-text dato-ico"></i>
                    <span class="dato-lbl">Documento</span>
                    <span class="dato-val mono">
                        {{ $cliente->tipo_documento ?? '—' }}
                        {{ $cliente->numero_documento ?? '' }}
                    </span>
                </div>

                <div class="dato-row">
                    <i class="bi bi-telephone dato-ico"></i>
                    <span class="dato-lbl">Teléfono</span>
                    <span class="dato-val">
                        @if($cliente->telefono)
                            <a href="https://wa.me/51{{ $cliente->telefono }}" target="_blank"
                               style="color:var(--green);text-decoration:none;font-weight:600;">
                                <i class="bi bi-whatsapp"></i> {{ $cliente->telefono }}
                            </a>
                        @else
                            <span class="dato-nd">—</span>
                        @endif
                    </span>
                </div>

                @if($cliente->telefono2)
                <div class="dato-row">
                    <i class="bi bi-telephone-plus dato-ico"></i>
                    <span class="dato-lbl">Teléfono 2</span>
                    <span class="dato-val">{{ $cliente->telefono2 }}</span>
                </div>
                @endif

                <div class="dato-row">
                    <i class="bi bi-envelope dato-ico"></i>
                    <span class="dato-lbl">Email</span>
                    <span class="dato-val">
                        @if($cliente->email)
                            <a href="mailto:{{ $cliente->email }}" style="color:var(--blue);text-decoration:none;">
                                {{ $cliente->email }}
                            </a>
                        @else
                            <span class="dato-nd">—</span>
                        @endif
                    </span>
                </div>

                <div class="dato-row">
                    <i class="bi bi-cake2 dato-ico"></i>
                    <span class="dato-lbl">Nacimiento</span>
                    <span class="dato-val">
                        @if($cliente->fecha_nacimiento)
                            {{ $cliente->fecha_nacimiento->format('d/m/Y') }}
                            <span style="color:var(--ink-4);font-size:.75rem;">
                                ({{ $cliente->fecha_nacimiento->age }} años)
                            </span>
                        @else
                            <span class="dato-nd">—</span>
                        @endif
                    </span>
                </div>

                <div class="dato-row">
                    <i class="bi bi-gender-ambiguous dato-ico"></i>
                    <span class="dato-lbl">Género</span>
                    <span class="dato-val">{{ $cliente->genero ?? '—' }}</span>
                </div>

                <div class="dato-row">
                    <i class="bi bi-flag dato-ico"></i>
                    <span class="dato-lbl">Nacionalidad</span>
                    <span class="dato-val">{{ $cliente->nacionalidad ?? '—' }}</span>
                </div>

            </div>
        </div>

        {{-- CONTACTO DE EMERGENCIA --}}
        @if($cliente->emergencia_nombre || $cliente->emergencia_telefono)
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="bi bi-shield-plus"></i> Contacto de emergencia</div>
            </div>
            <div class="card-body" style="padding-top:.75rem;padding-bottom:.75rem;">
                <div class="dato-row">
                    <i class="bi bi-person dato-ico"></i>
                    <span class="dato-lbl">Nombre</span>
                    <span class="dato-val">{{ $cliente->emergencia_nombre ?? '—' }}</span>
                </div>
                <div class="dato-row">
                    <i class="bi bi-people dato-ico"></i>
                    <span class="dato-lbl">Parentesco</span>
                    <span class="dato-val">{{ $cliente->emergencia_parentesco ?? '—' }}</span>
                </div>
                <div class="dato-row">
                    <i class="bi bi-telephone dato-ico"></i>
                    <span class="dato-lbl">Teléfono</span>
                    <span class="dato-val">
                        @if($cliente->emergencia_telefono)
                            <a href="https://wa.me/51{{ $cliente->emergencia_telefono }}" target="_blank"
                               style="color:var(--green);text-decoration:none;font-weight:600;">
                                <i class="bi bi-whatsapp"></i> {{ $cliente->emergencia_telefono }}
                            </a>
                        @else —
                        @endif
                    </span>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ═══════════ COLUMNA DERECHA ═══════════ --}}
    <div>

        {{-- HISTORIAL DE RESERVAS --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title">
                    <i class="bi bi-calendar-check"></i> Historial de reservas
                    <span style="background:var(--blue-l);color:var(--blue);border-radius:999px;font-size:.65rem;font-weight:700;padding:1px 7px;">{{ $totalRes }}</span>
                </div>
                <a href="{{ route('reservas.create') }}?cliente_id={{ $cliente->id }}" class="btn-mini">
                    <i class="bi bi-plus-lg"></i> Nueva reserva
                </a>
            </div>
            <div class="card-body">
                @if($reservas->isEmpty())
                    <div class="empty-sm">
                        <i class="bi bi-calendar-x"></i>
                        Este cliente no tiene reservas aún.
                    </div>
                @else
                    @foreach($reservas as $r)
                    @php
                        $est   = $r->estado;
                        $color = $est->color_hex ?? '#6b7280';
                        $pend  = max(0, (float)$r->precio_total - (float)$r->monto_pagado);
                    @endphp
                    <div class="reserva-item">
                        <div class="res-top">
                            <div>
                                <div class="res-codigo">{{ $r->codigo_reserva }}</div>
                                <div class="res-tour">{{ $r->nombre_tour }}</div>
                            </div>
                            <span class="estado-badge"
                                  style="background:{{ $color }}18;color:{{ $color }};border-color:{{ $color }}40;">
                                {{ $est->etiqueta ?? $est->nombre ?? '—' }}
                            </span>
                        </div>

                        <div class="res-fecha">
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($r->fecha_tour)->format('d/m/Y') }}
                            @if($r->hora_salida)
                                · <i class="bi bi-clock"></i> {{ $r->hora_salida }}
                            @endif
                            @if($r->ciudad_destino)
                                · <i class="bi bi-geo-alt"></i> {{ $r->ciudad_destino }}
                            @endif
                        </div>

                        <div class="res-meta">
                            @if($r->cantidad_adultos)
                            <span class="res-chip">
                                <i class="bi bi-person"></i>
                                {{ $r->cantidad_adultos }} adulto{{ $r->cantidad_adultos != 1 ? 's' : '' }}
                            </span>
                            @endif
                            @if($r->cantidad_ninos)
                            <span class="res-chip">
                                <i class="bi bi-person-hearts"></i>
                                {{ $r->cantidad_ninos }} niño{{ $r->cantidad_ninos != 1 ? 's' : '' }}
                            </span>
                            @endif
                            @if($r->canal_contacto)
                            <span class="res-chip">
                                <i class="bi bi-chat-dots"></i> {{ $r->canal_contacto }}
                            </span>
                            @endif
                        </div>

                        <div class="res-footer">
                            <div class="res-monto">
                                <span class="total">S/ {{ number_format($r->precio_total, 2) }}</span>
                                @if($pend > 0)
                                <span class="pendiente">· Pendiente S/ {{ number_format($pend, 2) }}</span>
                                @else
                                <span style="color:var(--green);font-size:.72rem;margin-left:.3rem;">· Pagado</span>
                                @endif
                            </div>
                            <a href="{{ route('reservas.show', $r) }}" class="btn-mini">
                                <i class="bi bi-eye"></i> Ver detalle
                            </a>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- INFORMACIÓN MÉDICA / SALUD --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="bi bi-heart-pulse"></i> Historial médico / restricciones</div>
            </div>
            <div class="card-body">
                @php
                    // Recopilar info médica del titular en cada reserva
                    $medItems = $reservas->filter(fn($r) =>
                        $r->alergias_titular ||
                        $r->restricciones_alimentarias_titular ||
                        $r->titular_obs_medicas
                    );
                @endphp
                @if($medItems->isEmpty())
                    <div class="empty-sm">
                        <i class="bi bi-clipboard2-heart"></i>
                        Sin información médica registrada.
                    </div>
                @else
                    @foreach($medItems as $r)
                    <div style="margin-bottom:1rem;padding:.75rem;background:var(--line-2);border-radius:10px;border:1px solid var(--line);">
                        <div style="font-size:.72rem;font-weight:700;color:var(--ink-4);margin-bottom:.5rem;">
                            <i class="bi bi-calendar3"></i>
                            {{ \Carbon\Carbon::parse($r->fecha_tour)->format('d/m/Y') }} —
                            {{ $r->nombre_tour }}
                        </div>

                        @if($r->alergias_titular)
                        <div class="salud-item">
                            <div class="salud-ico" style="background:#fef2f2;color:var(--red)">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div class="salud-lbl">Alergias</div>
                                <div class="salud-val">{{ $r->alergias_titular }}</div>
                            </div>
                        </div>
                        @endif

                        @if($r->restricciones_alimentarias_titular)
                        <div class="salud-item">
                            <div class="salud-ico" style="background:var(--orange-l);color:var(--orange)">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                            <div>
                                <div class="salud-lbl">Restricciones alimentarias</div>
                                <div class="salud-val">{{ $r->restricciones_alimentarias_titular }}</div>
                            </div>
                        </div>
                        @endif

                        @if($r->titular_obs_medicas)
                        <div class="salud-item">
                            <div class="salud-ico" style="background:var(--blue-l);color:var(--blue)">
                                <i class="bi bi-clipboard2-pulse"></i>
                            </div>
                            <div>
                                <div class="salud-lbl">Observaciones médicas</div>
                                <div class="salud-val">{{ $r->titular_obs_medicas }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- TIMELINE DE ACTIVIDAD --}}
        <div class="card">
            <div class="card-head">
                <div class="card-title"><i class="bi bi-clock-history"></i> Línea de tiempo</div>
            </div>
            <div class="card-body">
                @if($reservas->isEmpty())
                    <div class="empty-sm">
                        <i class="bi bi-clock"></i>
                        Sin actividad registrada.
                    </div>
                @else
                <div class="timeline">
                    @foreach($reservas as $r)
                    @php $color = $r->estado->color_hex ?? '#6b7280'; @endphp
                    <div class="tl-item">
                        <div class="tl-dot" style="background:{{ $color }};box-shadow:0 0 0 2px {{ $color }}33;"></div>
                        <div class="tl-fecha">
                            {{ $r->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="tl-texto">
                            Reserva <strong>{{ $r->codigo_reserva }}</strong> creada
                            <span style="display:inline-flex;align-items:center;gap:.2rem;padding:1px 6px;border-radius:4px;font-size:.65rem;font-weight:700;background:{{ $color }}18;color:{{ $color }};margin-left:.25rem;">
                                {{ $r->estado->etiqueta ?? $r->estado->nombre ?? '' }}
                            </span>
                        </div>
                        <div class="tl-tour">
                            <i class="bi bi-briefcase" style="font-size:.65rem"></i>
                            {{ $r->nombre_tour }} —
                            {{ \Carbon\Carbon::parse($r->fecha_tour)->format('d/m/Y') }}
                        </div>
                    </div>
                    @endforeach
                    <div class="tl-item">
                        <div class="tl-dot" style="background:#e2e8f0;"></div>
                        <div class="tl-fecha">{{ $cliente->created_at->format('d/m/Y') }}</div>
                        <div class="tl-texto" style="color:var(--ink-4);">Cliente registrado en el sistema</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
