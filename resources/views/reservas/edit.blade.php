{{-- =====================================================================
     ARCHIVO: edit.blade.php
     UBICACIÓN: resources/views/reservas/edit.blade.php
     ===================================================================== --}}
@extends('layouts.app')
@section('titulo', 'Editar Reserva — ' . $reserva->codigo_reserva)

@php
    if (! session()->hasOldInput()) {
        $cliente = $reserva->cliente;

        $pasajeroTitular = $reserva->pasajeros->firstWhere('es_titular', true)
                        ?? $reserva->pasajeros->first();

        $pasajerosAdicionales = $reserva->pasajeros
            ->filter(fn($p) => !$p->es_titular && $p->id !== ($pasajeroTitular?->id))
            ->values()
            ->map(fn($p) => [
                'nombre_completo'  => $p->nombre_completo,
                'tipo'             => $p->tipo,
                'tipo_documento'   => $p->tipo_documento,
                'numero_documento' => $p->numero_documento,
                'edad'             => $p->edad,
            ])->toArray();

        $aerolineasConocidas = [
            'LATAM Airlines Peru','Sky Airline Peru','JetSMART Peru','Star Peru',
            'ATSA Airlines','Avianca','American Airlines','Copa Airlines',
            'Delta Air Lines','Iberia','Air Europa','KLM','Aeromexico',
            'Spirit Airlines','United Airlines',
        ];
        $aerolineaVal  = $reserva->aerolinea;
        $aerolineaOtra = null;
        if ($aerolineaVal && !in_array($aerolineaVal, $aerolineasConocidas)) {
            $aerolineaOtra = $aerolineaVal;
            $aerolineaVal  = 'otra';
        }

        $estadoNombre  = $reserva->estado?->nombre ?? 'mitad_pago';
        $estadoMap     = ['mitad_pago'=>'mitad_pago','pagado'=>'pagado','cancelada'=>'cancelada'];
        $estadoInicial = $estadoMap[$estadoNombre] ?? 'mitad_pago';

        $emailCompleto = $cliente?->email ?? '';
        $emailUser = $emailDomain = '';
        if ($emailCompleto && str_contains($emailCompleto, '@')) {
            [$emailUser, $emailDomain] = explode('@', $emailCompleto, 2);
        }

        session()->flashInput([
            'nombre_tour'          => $reserva->nombre_tour,
            'precio_tour'          => $reserva->precio_total,
            'fecha_tour'           => $reserva->fecha_tour ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('Y-m-d') : null,
            'hora_salida'          => $reserva->hora_salida ? substr($reserva->hora_salida,0,5) : null,
            'ciudad_destino'       => $reserva->ciudad_destino,
            'departamento_destino' => $reserva->departamento_destino,
            'ciudad_procedencia'   => $reserva->ciudad_procedencia,
            'fecha_arribo'         => $reserva->fecha_arribo ? \Carbon\Carbon::parse($reserva->fecha_arribo)->format('Y-m-d') : null,
            'fecha_retorno'        => $reserva->fecha_retorno ? \Carbon\Carbon::parse($reserva->fecha_retorno)->format('Y-m-d') : null,
            'dias_viaje'           => $reserva->dias_viaje,
            'hora_arribo'          => $reserva->hora_arribo ? substr($reserva->hora_arribo,0,5) : null,
            'hora_retorno'         => $reserva->hora_retorno ? substr($reserva->hora_retorno,0,5) : null,
            'tipo_transporte'      => $reserva->tipo_transporte,
            'empresa_transporte'   => $reserva->empresa_transporte,
            'aerolinea'            => $aerolineaVal,
            'aerolinea_otra'       => $aerolineaOtra,
            'numero_vuelo'         => $reserva->numero_vuelo,
            'hora_salida_vuelo'    => $reserva->hora_salida_vuelo ? substr($reserva->hora_salida_vuelo,0,5) : null,
            'hora_llegada_vuelo'   => $reserva->hora_llegada_vuelo ? substr($reserva->hora_llegada_vuelo,0,5) : null,
            'nombre_hotel'         => $reserva->nombre_hotel,
            'tipo_establecimiento' => $reserva->tipo_establecimiento,
            'tipo_habitacion'      => $reserva->tipo_habitacion,
            'tipo_cama'            => $reserva->tipo_cama,
            'plan_alimentacion'    => $reserva->plan_alimentacion,
            'cliente_id'                   => $reserva->cliente_id,
            'titular_nombre'               => $cliente?->nombre_completo,
            'titular_tipo_documento'       => $cliente?->tipo_documento,
            'titular_numero_documento'     => $cliente?->numero_documento,
            'titular_fecha_nacimiento'     => $cliente?->fecha_nacimiento ? \Carbon\Carbon::parse($cliente->fecha_nacimiento)->format('Y-m-d') : null,
            'titular_genero'               => $cliente?->genero,
            'titular_nacionalidad'         => $cliente?->nacionalidad,
            'titular_telefono'             => $cliente?->telefono,
            'titular_telefono_codigo'      => '+51',
            'titular_telefono2'            => $cliente?->telefono2,
            'titular_email'                => $emailCompleto,
            'canal_contacto'               => $reserva->canal_contacto,
            'emergencia_nombre'            => $cliente?->emergencia_nombre,
            'emergencia_parentesco'        => $cliente?->emergencia_parentesco,
            'emergencia_telefono'          => $cliente?->emergencia_telefono,
            'pasajeros'                    => $pasajerosAdicionales,
            'titular_tiene_alergias'       => $reserva->alergias_titular ? 'si' : 'no',
            'titular_alergias_detalle'     => $reserva->alergias_titular,
            'titular_restricciones'        => $reserva->restricciones_alimentarias_titular,
            'titular_obs_medicas'          => $reserva->titular_obs_medicas,
            'tipo_comprobante'             => $reserva->tipo_comprobante,
            'ruc_factura'                  => $reserva->ruc_factura,
            'razon_social'                 => $reserva->razon_social,
            'estado_inicial'               => $estadoInicial,
            'metodo_pago'                  => '',
            'monto_pagado_inicial'         => '',
            'fecha_pago'                   => date('Y-m-d'),
            'numero_operacion'             => '',
            'politica_descripcion'         => $reserva->politica_descripcion,
            'politica_tipo'                => $reserva->politica_tipo,
        ]);
    }

    $jsReserva = [
        'fecha_tour'         => $reserva->fecha_tour ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('Y-m-d') : '',
        'hora_salida'        => $reserva->hora_salida ? substr($reserva->hora_salida,0,5) : '',
        'fecha_arribo'       => $reserva->fecha_arribo ? \Carbon\Carbon::parse($reserva->fecha_arribo)->format('Y-m-d') : '',
        'fecha_retorno'      => $reserva->fecha_retorno ? \Carbon\Carbon::parse($reserva->fecha_retorno)->format('Y-m-d') : '',
        'hora_arribo'        => $reserva->hora_arribo ? substr($reserva->hora_arribo,0,5) : '',
        'hora_retorno'       => $reserva->hora_retorno ? substr($reserva->hora_retorno,0,5) : '',
        'hora_salida_vuelo'  => $reserva->hora_salida_vuelo ? substr($reserva->hora_salida_vuelo,0,5) : '',
        'hora_llegada_vuelo' => $reserva->hora_llegada_vuelo ? substr($reserva->hora_llegada_vuelo,0,5) : '',
        'email_user'         => $emailUser ?? '',
        'email_domain'       => $emailDomain ?? '',
        'pasajeros'          => $pasajerosAdicionales ?? [],
        'tipo_habitacion'    => $reserva->tipo_habitacion ?? '',
        'tipo_transporte'    => $reserva->tipo_transporte ?? '',
        'dias_viaje'         => $reserva->dias_viaje ?? '',
        'titular_nacimiento' => $reserva->cliente?->fecha_nacimiento ? \Carbon\Carbon::parse($reserva->cliente->fecha_nacimiento)->format('Y-m-d') : '',
    ];

    $totalActual  = (float)$reserva->precio_total;
    $pagadoActual = $reserva->pagos->sum('monto');
    $saldoActual  = max(0, $totalActual - $pagadoActual);
    $pctActual    = $totalActual > 0 ? min(100, round($pagadoActual / $totalActual * 100)) : 0;
@endphp

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('css/reservas-form.css') }}">
<link rel="stylesheet" href="{{ asset('css/reservas-form-fixes.css') }}">
<style>
.edit-banner {
    background:linear-gradient(135deg,#1e40af 0%,#1d4ed8 100%);color:white;
    border-radius:12px;padding:.875rem 1.25rem;margin-bottom:1.25rem;
    display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;
}
.edit-banner-left { display:flex;align-items:center;gap:.75rem; }
.edit-banner-icon { width:38px;height:38px;background:rgba(255,255,255,.2);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0; }
.edit-banner-codigo { font-family:'JetBrains Mono',monospace;font-size:1rem;font-weight:700;letter-spacing:.04em; }
.edit-banner-sub  { font-size:.74rem;opacity:.75;margin-top:.1rem; }
.edit-banner-badge { background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:.3rem .8rem;font-size:.76rem;font-weight:700;white-space:nowrap;display:flex;align-items:center;gap:.35rem; }
.edit-pago-nota { background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;padding:.7rem 1rem;font-size:.8rem;color:#92400e;display:flex;align-items:flex-start;gap:.5rem;margin-bottom:.75rem; }
</style>
@endpush

@section('contenido')
<div class="page-wrap">
    @include('reservas.partials._header')
    <div class="form-main">

        {{-- Banner --}}
        <div class="edit-banner">
            <div class="edit-banner-left">
                <div class="edit-banner-icon"><i class="bi bi-pencil-square"></i></div>
                <div>
                    <div class="edit-banner-codigo">{{ $reserva->codigo_reserva }}</div>
                    <div class="edit-banner-sub">Editando reserva · Creada el {{ $reserva->created_at->format('d/m/Y \a \l\a\s H:i') }}</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
                @php $estadoActual = $reserva->estado?->nombre ?? ''; @endphp
                <div class="edit-banner-badge">
                    <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>
                    Estado actual: {{ ucfirst(str_replace('_',' ',$estadoActual)) }}
                </div>
                <a href="{{ route('reservas.show', $reserva) }}"
                   style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);border-radius:8px;padding:.3rem .8rem;font-size:.76rem;font-weight:600;color:white;text-decoration:none;display:flex;align-items:center;gap:.3rem;">
                    <i class="bi bi-eye"></i> Ver reserva
                </a>
            </div>
        </div>

        @if($errors->any())
        <div class="lerr">
            <strong><i class="bi bi-exclamation-triangle me-1"></i> Corrige los errores:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        @if(session('error'))  <div class="lerr"><strong><i class="bi bi-exclamation-triangle me-1"></i></strong> {{ session('error') }}</div> @endif
        @if(session('success')) <div class="msg-ok"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div> @endif

        <form method="POST" action="{{ route('reservas.update', $reserva) }}"
              enctype="multipart/form-data" id="form-reserva" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" name="cliente_id" value="{{ $reserva->cliente_id }}">

            @include('reservas.partials._bloque1_viaje')
            @include('reservas.partials._bloque2_titular_pasajeros')

            {{-- BLOQUE 4 EDICIÓN --}}
            <div class="fb" id="bloque-4">
                <div class="fb-num-badge" id="fb-status-4">4</div>
                <div class="fb-head">
                    <div class="fb-ico amber"><i class="bi bi-credit-card"></i></div>
                    <div class="fb-titles">
                        <h3>Pago y comprobante</h3>
                        <p>Comprobante fiscal y nuevo pago (opcional en edición)</p>
                    </div>
                </div>
                <div class="fb-body">

                    {{-- Resumen actual --}}
                    <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:.875rem 1.1rem;margin-bottom:1rem;">
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:.6rem;">
                            <i class="bi bi-graph-up me-1"></i> Situación actual de pagos
                        </div>
                        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;align-items:center;">
                            <div>
                                <div style="font-size:.67rem;font-weight:700;color:#94a3b8;text-transform:uppercase;">Total</div>
                                <div style="font-family:'JetBrains Mono',monospace;font-size:1.05rem;font-weight:800;color:#0f172a;">S/ {{ number_format($totalActual,2) }}</div>
                            </div>
                            <div>
                                <div style="font-size:.67rem;font-weight:700;color:#94a3b8;text-transform:uppercase;">Pagado</div>
                                <div style="font-family:'JetBrains Mono',monospace;font-size:1.05rem;font-weight:800;color:#059669;">S/ {{ number_format($pagadoActual,2) }}</div>
                            </div>
                            <div>
                                <div style="font-size:.67rem;font-weight:700;color:#94a3b8;text-transform:uppercase;">Saldo</div>
                                <div style="font-family:'JetBrains Mono',monospace;font-size:1.05rem;font-weight:800;color:{{ $saldoActual > 0 ? '#dc2626':'#059669' }};">S/ {{ number_format($saldoActual,2) }}</div>
                            </div>
                            <div style="flex:1;min-width:140px;">
                                <div style="display:flex;justify-content:space-between;font-size:.7rem;font-weight:700;color:#94a3b8;margin-bottom:.3rem;">
                                    <span>Progreso</span><span style="color:{{ $pctActual>=100 ? '#059669':'#1d4ed8' }};">{{ $pctActual }}%</span>
                                </div>
                                <div style="background:#e2e8f0;border-radius:99px;height:8px;overflow:hidden;">
                                    <div style="height:100%;border-radius:99px;width:{{ $pctActual }}%;background:{{ $pctActual>=100 ? '#059669':($pctActual>=50 ? '#1d4ed8':'#d97706') }};transition:width .5s;"></div>
                                </div>
                            </div>
                        </div>
                        @if($reserva->pagos->count())
                        <div style="margin-top:.75rem;border-top:1px solid #e2e8f0;padding-top:.6rem;">
                            <div style="font-size:.67rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:.4rem;">Pagos registrados</div>
                            @foreach($reserva->pagos as $pago)
                            <div style="display:flex;align-items:center;gap:.75rem;font-size:.78rem;padding:.25rem 0;border-bottom:1px solid #f1f5f9;">
                                <span style="font-family:'JetBrains Mono',monospace;color:#64748b;min-width:70px;">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</span>
                                <span style="color:#475569;">{{ $pago->metodoPago?->nombre ?? '—' }}</span>
                                <span style="font-family:'JetBrains Mono',monospace;font-weight:700;color:#059669;margin-left:auto;">S/ {{ number_format($pago->monto,2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="st">Comprobante fiscal</div>
                    <div class="g12">
                        <div class="field">
                            <label class="lbl" for="tipo_comprobante">Tipo <span class="req">*</span></label>
                            <select name="tipo_comprobante" id="tipo_comprobante" class="fi" required onchange="togFactura()" data-validate="required" data-bloque="4">
                                <option value="boleta"  {{ old('tipo_comprobante','boleta')=='boleta'  ?'selected':'' }}>Boleta</option>
                                <option value="factura" {{ old('tipo_comprobante')         =='factura' ?'selected':'' }}>Factura</option>
                            </select>
                        </div>
                        <div id="campos-factura" style="display:{{ old('tipo_comprobante')=='factura' ? 'block':'none' }}">
                            <div class="g2">
                                <div class="field">
                                    <label class="lbl" for="ruc_factura">RUC</label>
                                    <div class="ig"><span class="ia">RUC</span>
                                        <input type="text" name="ruc_factura" id="ruc_factura" value="{{ old('ruc_factura') }}" class="fi" placeholder="20XXXXXXXXX" maxlength="11">
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="lbl" for="razon_social">Razón social</label>
                                    <input type="text" name="razon_social" id="razon_social" value="{{ old('razon_social') }}" class="fi" placeholder="EMPRESA S.A.C." maxlength="200" oninput="this.value=this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="st">Estado de la reserva</div>
                    <div class="field">
                        <label class="lbl">Estado actual <span class="req">*</span></label>
                        <div class="estado-inline">
                            <label class="eo-compact e-mitad {{ old('estado_inicial')=='mitad_pago' ?'sel':'' }}" onclick="selEst(this)">
                                <input type="radio" name="estado_inicial" value="mitad_pago" {{ old('estado_inicial')=='mitad_pago' ?'checked':'' }}>
                                <span class="eo-dot"></span><i class="bi bi-hourglass-split"></i> 50% Pagado
                            </label>
                            <label class="eo-compact e-pagado {{ old('estado_inicial')=='pagado' ?'sel':'' }}" onclick="selEst(this)">
                                <input type="radio" name="estado_inicial" value="pagado" {{ old('estado_inicial')=='pagado' ?'checked':'' }}>
                                <span class="eo-dot"></span><i class="bi bi-patch-check"></i> Pagado completo
                            </label>
                            <label class="eo-compact e-custom {{ old('estado_inicial')=='cancelada' ?'sel':'' }}" onclick="selEst(this)">
                                <input type="radio" name="estado_inicial" value="cancelada" {{ old('estado_inicial')=='cancelada' ?'checked':'' }}>
                                <span class="eo-dot"></span><i class="bi bi-x-circle"></i> Cancelada
                            </label>
                        </div>
                    </div>

                    <div class="st">Registrar nuevo pago <span class="opt">(opcional)</span></div>
                    <div class="edit-pago-nota">
                        <i class="bi bi-info-circle-fill" style="margin-top:1px;flex-shrink:0;"></i>
                        <span>Solo completa esta sección si el cliente realizó un nuevo pago. Si dejas el método vacío no se registrará ningún pago adicional.</span>
                    </div>
                    <div class="g3">
                        <div class="field">
                            <label class="lbl" for="metodo_pago">Método de pago</label>
                            <select name="metodo_pago" id="metodo_pago" class="fi" onchange="updOpHint?.();updateProgressSteps?.()">
                                <option value="">— Sin nuevo pago —</option>
                                <optgroup label="Efectivo"><option value="efectivo" {{ old('metodo_pago')=='efectivo'?'selected':'' }}>Efectivo</option></optgroup>
                                <optgroup label="Pagos digitales">
                                    <option value="yape"  {{ old('metodo_pago')=='yape' ?'selected':'' }}>Yape</option>
                                    <option value="plin"  {{ old('metodo_pago')=='plin' ?'selected':'' }}>Plin</option>
                                    <option value="tunki" {{ old('metodo_pago')=='tunki'?'selected':'' }}>Tunki</option>
                                </optgroup>
                                <optgroup label="Transferencia">
                                    <option value="transf_bcp"   {{ old('metodo_pago')=='transf_bcp'  ?'selected':'' }}>Transf. BCP</option>
                                    <option value="transf_bbva"  {{ old('metodo_pago')=='transf_bbva' ?'selected':'' }}>Transf. BBVA</option>
                                    <option value="transf_inter" {{ old('metodo_pago')=='transf_inter'?'selected':'' }}>Transf. Interbank</option>
                                    <option value="transf_sc"    {{ old('metodo_pago')=='transf_sc'   ?'selected':'' }}>Transf. Scotiabank</option>
                                    <option value="transf_bn"    {{ old('metodo_pago')=='transf_bn'   ?'selected':'' }}>Transf. Banco Nación</option>
                                    <option value="transf_otros" {{ old('metodo_pago')=='transf_otros'?'selected':'' }}>Otro banco</option>
                                </optgroup>
                                <optgroup label="Depósito">
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
                            <label class="lbl" for="monto_pagado_inicial">Monto nuevo pago (S/)</label>
                            <div class="ig"><span class="ia">S/</span>
                                <input type="number" name="monto_pagado_inicial" id="monto_pagado_inicial" value="{{ old('monto_pagado_inicial') }}" class="fi" step="0.01" min="0" placeholder="0.00" inputmode="decimal" oninput="calcTotal?.()">
                            </div>
                            @if($saldoActual > 0)
                            <div class="fhint">Saldo pendiente: <strong style="color:#dc2626;">S/ {{ number_format($saldoActual,2) }}</strong></div>
                            @endif
                        </div>
                        <div class="field">
                            <label class="lbl">Fecha del pago</label>
                            <div class="ig"><span class="ia"><i class="bi bi-calendar3"></i></span>
                                <input type="date" name="fecha_pago" value="{{ old('fecha_pago', date('Y-m-d')) }}" class="fi">
                            </div>
                        </div>
                    </div>

                    <div class="field" style="max-width:360px">
                        <label class="lbl">N° operación <span class="opt">(opcional)</span></label>
                        <input type="text" name="numero_operacion" value="{{ old('numero_operacion') }}" class="fi" placeholder="Código de transacción..." maxlength="100">
                    </div>

                    {{-- Voucher — solo si hay saldo pendiente --}}
                    @if($saldoActual > 0)
                    <div class="st">Voucher del nuevo pago <span class="opt">(opcional)</span></div>
                    <div class="upload-zone" id="uz"
                         ondragover="event.preventDefault();this.classList.add('over')"
                         ondragleave="this.classList.remove('over')"
                         ondrop="onDrop(event)">
                        <input type="file" name="archivo_baucher" id="archivo_baucher"
                               accept=".jpg,.jpeg,.png,.pdf,.webp" onchange="onFile(event)">
                        <div class="uz-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                        <div class="uz-text">Arrastra aquí o <strong style="color:var(--adv-blue)">haz clic para seleccionar</strong></div>
                        <div class="uz-sub">JPG · PNG · PDF · WEBP — máx. 5 MB</div>
                    </div>
                    <div class="file-preview" id="fprev">
                        <img id="prev-img" src="" alt="">
                        <div class="file-preview-bar">
                            <span class="fn" id="prev-name">—</span>
                            <button type="button" class="fr" onclick="removeFile()">
                                <i class="bi bi-x-circle me-1"></i> Quitar
                            </button>
                        </div>
                    </div>
                    @else
                    <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:.75rem 1rem;font-size:.82rem;color:#15803d;display:flex;align-items:center;gap:.5rem;margin-top:.5rem;">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Esta reserva ya está <strong>100% pagada</strong>. No se requiere voucher adicional.</span>
                    </div>
                    @endif

                </div>
            </div>

            @include('reservas.partials._bloque5_politicas')
        </form>

        {{-- Form oculto para cancelar reserva --}}
        @if($reserva->estado?->nombre !== 'cancelada')
        <form id="form-cancelar"
              method="POST"
              action="{{ route('reservas.update', $reserva) }}"
              style="display:none;">
            @csrf
            @method('PUT')
            <input type="hidden" name="cliente_id"           value="{{ $reserva->cliente_id }}">
            <input type="hidden" name="nombre_tour"          value="{{ $reserva->nombre_tour }}">
            <input type="hidden" name="precio_tour"          value="{{ $reserva->precio_total }}">
            <input type="hidden" name="ciudad_procedencia"   value="{{ $reserva->ciudad_procedencia }}">
            <input type="hidden" name="canal_contacto"       value="{{ $reserva->canal_contacto }}">
            <input type="hidden" name="estado_inicial"       value="cancelada">
            <input type="hidden" name="tipo_comprobante"     value="{{ $reserva->tipo_comprobante }}">
            <input type="hidden" name="metodo_pago"          value="">
            <input type="hidden" name="monto_pagado_inicial" value="0">
            <input type="hidden" name="tipo_pago"            value="adelanto">
            <input type="hidden" name="titular_nombre"       value="{{ $reserva->cliente?->nombre_completo }}">
            <input type="hidden" name="titular_telefono"     value="{{ $reserva->cliente?->telefono }}">
            <input type="hidden" name="politica_descripcion" value="{{ $reserva->politica_descripcion }}">
        </form>
        @endif

        {{-- Submit bar --}}
        <div class="sbar">
            <div class="sbar-left">
                <div class="sbar-label">Editando: {{ $reserva->codigo_reserva }}</div>
                <div class="sbar-value" id="sb-total">S/ {{ number_format($reserva->precio_total,2) }} <span id="sb-pasajeros"></span></div>
                <div style="display:flex;gap:1rem;margin-top:.25rem;font-size:.72rem;color:var(--ink-4);font-weight:600;align-items:center;flex-wrap:wrap">
                    <span>Pagado: <strong id="sp-adel" style="color:var(--adv-green)">S/ {{ number_format($pagadoActual,2) }}</strong></span>
                    <span>Faltante: <strong id="sp-saldo" style="color:var(--adv-amber-d)">S/ {{ number_format($saldoActual,2) }}</strong></span>
                    <span id="sp-estado-badge" class="sp-status-badge"></span>
                </div>
            </div>
            <div class="sbar-actions">
                @if($reserva->estado?->nombre !== 'cancelada')
                <button type="button" class="btn-secondary"
                        style="border-color:#fecaca;color:#dc2626;"
                        onclick="if(confirm('¿Cancelar esta reserva? Esta acción cambiará el estado a Cancelada.')) document.getElementById('form-cancelar').submit()">
                    <i class="bi bi-x-circle"></i> Cancelar reserva
                </button>
                @endif
                <a href="{{ route('reservas.show', $reserva) }}" class="btn-secondary">
                    <i class="bi bi-x"></i> Descartar
                </a>
                <button type="submit" form="form-reserva" class="btn-primary" id="btn-submit">
                    <i class="bi bi-check-circle"></i> Guardar cambios
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="{{ asset('js/politicas.js') }}"></script>
<script src="{{ asset('js/reservas-create.js') }}"></script>

<script>
window._EDIT = @json($jsReserva);
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const D = window._EDIT;

    function iniF(dispId, hidId, val) {
        if (!val) return;
        const d = document.getElementById(dispId);
        const h = document.getElementById(hidId);
        if (!d || !h) return;
        h.value = val;
        if (d._flatpickr) d._flatpickr.destroy();
        flatpickr('#' + dispId, {
            locale:'es', dateFormat:'d/m/Y', allowInput:false, defaultDate:val,
            onChange(sel,s,i){ h.value = i.formatDate(sel[0],'Y-m-d'); updateProgressSteps?.(); }
        });
    }

    function iniH(dispId, hidId, val) {
        if (!val || val==='N/D') return;
        const d = document.getElementById(dispId);
        const h = document.getElementById(hidId);
        if (!d || !h) return;
        h.value = val;
        if (d._flatpickr) d._flatpickr.destroy();
        flatpickr('#' + dispId, {
            enableTime:true, noCalendar:true, dateFormat:'h:i K',
            time_24hr:false, allowInput:false, defaultDate:'2000-01-01 '+val,
            onChange(sel,s,i){ h.value = i.formatDate(sel[0],'H:i'); }
        });
    }

    iniF('fecha_tour_display',        'fecha_tour',         D.fecha_tour);
    iniH('hora_salida_display',       'hora_salida',        D.hora_salida);
    iniF('fecha_arribo_display',      'fecha_arribo',       D.fecha_arribo);
    iniF('fecha_retorno_display',     'fecha_retorno',      D.fecha_retorno);
    iniH('hora_arribo_display',       'hora_arribo',        D.hora_arribo);
    iniH('hora_retorno_display',      'hora_retorno',       D.hora_retorno);
    iniH('hora_salida_vuelo_display', 'hora_salida_vuelo',  D.hora_salida_vuelo);
    iniH('hora_llegada_vuelo_display','hora_llegada_vuelo', D.hora_llegada_vuelo);

    if (D.titular_nacimiento) {
        const fpEl = document.getElementById('titular_fecha_nacimiento');
        if (fpEl) {
            if (fpEl._flatpickr) fpEl._flatpickr.destroy();
            flatpickr('#titular_fecha_nacimiento', {
                locale:'es', dateFormat:'d/m/Y', maxDate:'today', allowInput:false,
                defaultDate: D.titular_nacimiento,
                onChange(sel,s,i){
                    const iso = document.getElementById('titular_fecha_nacimiento_iso');
                    if (iso) iso.value = i.formatDate(sel[0],'Y-m-d');
                    if (typeof calcEdad === 'function') calcEdad(i.formatDate(sel[0],'Y-m-d'));
                },
                onReady(sel,s,i){
                    const iso = document.getElementById('titular_fecha_nacimiento_iso');
                    if (iso) iso.value = D.titular_nacimiento;
                    if (typeof calcEdad === 'function') calcEdad(D.titular_nacimiento);
                }
            });
        }
    }

    if (D.email_user || D.email_domain) {
        const eu = document.getElementById('email-user');
        const ed = document.getElementById('email-domain');
        const eh = document.getElementById('titular_email');
        if (eu) eu.value = D.email_user;
        if (ed) ed.value = D.email_domain;
        if (eh) eh.value = D.email_user + (D.email_domain ? '@'+D.email_domain : '');
    }

    if (D.tipo_transporte && typeof toggleTransporte === 'function') {
        toggleTransporte();
    }

    const habHidden = document.getElementById('tipo_habitacion_hidden');
    if (habHidden && !habHidden.value && D.tipo_habitacion) {
        habHidden.value = D.tipo_habitacion;
    }
    if (habHidden?.value && typeof _renderHabs === 'function') {
        if (typeof _habitaciones !== 'undefined' && Object.keys(_habitaciones).length === 0 && habHidden.value) {
            const labelInv = {
                'sgl — simple (1 persona)':'sgl','dbl — doble matrimonial':'dbl',
                'twn — twin':'twn','tpl — triple':'tpl',
                'qdl — cuadruple':'qdl','fam — familiar':'fam','sui — suite':'sui'
            };
            habHidden.value.split(',').forEach(p => {
                const m = p.trim().match(/^(.+?)\s*x(\d+)$/i);
                if (!m) return;
                const lbl = m[1].trim().toLowerCase();
                let tipo = null;
                for (const [k,v] of Object.entries(labelInv)) {
                    if (lbl.startsWith(k.substring(0,3))) { tipo=v; break; }
                }
                if (!tipo) tipo = lbl.replace(/[^a-z]/g,'').substring(0,3);
                const qty = parseInt(m[2],10);
                if (tipo && qty>0) _habitaciones[tipo] = qty;
            });
            _renderHabs();
        }
    }

    if (D.pasajeros && D.pasajeros.length > 0) {
        const paxLista = document.getElementById('pax-lista');
        if (paxLista && paxLista.querySelectorAll('.pax-card').length === 0) {
            D.pasajeros.forEach(function() {
                if (typeof addPax === 'function') addPax();
            });
            setTimeout(function() {
                const cards = document.querySelectorAll('#pax-lista .pax-card');
                D.pasajeros.forEach(function(p, i) {
                    const card = cards[i];
                    if (!card) return;
                    const nombre = card.querySelector('input[name*="nombre_completo"]');
                    if (nombre) nombre.value = (p.nombre_completo || '').toUpperCase();
                    const tipodoc = card.querySelector('select[name*="tipo_documento"]');
                    if (tipodoc && p.tipo_documento) tipodoc.value = p.tipo_documento;
                    const numdoc = card.querySelector('input[name*="numero_documento"]');
                    if (numdoc && p.numero_documento) numdoc.value = p.numero_documento;
                    const tipoSel = card.querySelector('select[name*="[tipo]"]');
                    const tipoHid = card.querySelector('input[type="hidden"][name*="[tipo]"]');
                    if (tipoSel && p.tipo) tipoSel.value = p.tipo;
                    if (tipoHid && p.tipo) tipoHid.value = p.tipo;
                    if (p.fecha_nacimiento) {
                        const idx    = card.id.replace('pax-', '');
                        const dispEl = document.getElementById('pax-fnac-display-' + idx);
                        const hidEl  = document.getElementById('pax-fnac-' + idx);
                        const edadEl = document.getElementById('pax-edad-' + idx);
                        if (hidEl)  hidEl.value = p.fecha_nacimiento;
                        if (dispEl) {
                            if (dispEl._flatpickr) dispEl._flatpickr.destroy();
                            flatpickr('#pax-fnac-display-' + idx, {
                                locale:'es', dateFormat:'d/m/Y', maxDate:'today', allowInput:false,
                                defaultDate: p.fecha_nacimiento,
                                onChange(sel, str, inst) {
                                    const iso = inst.formatDate(sel[0], 'Y-m-d');
                                    if (hidEl) hidEl.value = iso;
                                    const hoy = new Date(); const nac = new Date(iso);
                                    let edad = hoy.getFullYear() - nac.getFullYear();
                                    const mes = hoy.getMonth() - nac.getMonth();
                                    if (mes < 0 || (mes===0 && hoy.getDate()<nac.getDate())) edad--;
                                    if (edadEl) edadEl.value = edad >= 0 ? edad : '';
                                    if (typeof calcPaxEdad === 'function') calcPaxEdad(idx, iso);
                                }
                            });
                        }
                    }
                    if (p.edad) {
                        const idx    = card.id.replace('pax-', '');
                        const edadEl = document.getElementById('pax-edad-' + idx);
                        if (edadEl) edadEl.value = p.edad;
                    }
                });
                if (typeof paxCnt === 'function') paxCnt();
            }, 80);
        }
    }

    const diasEl = document.getElementById('dias_calculados');
    if (diasEl && !diasEl.value && D.dias_viaje) {
        diasEl.value = D.dias_viaje;
    }

    if (typeof onTitularChange  === 'function') onTitularChange();
    if (typeof calcTotal        === 'function') calcTotal();
    if (typeof togFactura       === 'function') togFactura();

    setTimeout(function(){
        if (typeof updateProgressSteps === 'function') updateProgressSteps();
    }, 200);
});
</script>
@endpush