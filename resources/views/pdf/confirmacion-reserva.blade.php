{{-- =====================================================================
     ARCHIVO: resources/views/pdf/confirmacion-reserva.blade.php
     GENERADO POR: PdfService::generarConfirmacion()
     LIBRERÍA: barryvdh/laravel-dompdf
     ===================================================================== --}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }

  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 8.5pt;
    color: #1a1a2e;
    background: #ffffff;
  }

  /* ══════════════════════════════════════════════
     CABECERA
  ══════════════════════════════════════════════ */
  .header {
    background: #0f3460;
    padding: 0;
  }
  .header-top {
    padding: 18px 30px 14px 30px;
    display: table;
    width: 100%;
  }
  .header-left {
    display: table-cell;
    vertical-align: middle;
    width: 60%;
  }
  .brand-name {
    font-size: 28pt;
    font-weight: 700;
    color: #e2b96f;
    letter-spacing: 5px;
    text-transform: uppercase;
    line-height: 1;
  }
  .brand-tagline {
    font-size: 7.5pt;
    color: #a8c8e8;
    letter-spacing: 2px;
    margin-top: 3px;
  }
  .brand-datos {
    margin-top: 8px;
    font-size: 7pt;
    color: #a8c8e8;
    line-height: 1.6;
  }
  .brand-datos span {
    color: #e2b96f;
    font-weight: 700;
  }
  .header-right {
    display: table-cell;
    vertical-align: middle;
    text-align: right;
    width: 40%;
  }
  .doc-title {
    font-size: 7.5pt;
    color: #a8c8e8;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .doc-codigo {
    font-size: 17pt;
    font-weight: 700;
    color: #e2b96f;
    letter-spacing: 2px;
    line-height: 1;
  }
  .doc-meta {
    font-size: 7pt;
    color: #a8c8e8;
    margin-top: 5px;
    line-height: 1.6;
  }
  .banner-confirmado {
    background: #e2b96f;
    color: #0f3460;
    text-align: center;
    padding: 7px;
    font-size: 8.5pt;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
  }

  /* ══════════════════════════════════════════════
     CONTENIDO
  ══════════════════════════════════════════════ */
  .contenido {
    padding: 20px 30px;
  }

  /* ── Sección título ── */
  .seccion {
    margin-bottom: 14px;
  }
  .sec-titulo {
    font-size: 7.5pt;
    font-weight: 700;
    color: #ffffff;
    background: #0f3460;
    text-transform: uppercase;
    letter-spacing: 2px;
    padding: 5px 10px;
    border-left: 4px solid #e2b96f;
    margin-bottom: 8px;
  }
  .sec-subtitulo {
    font-size: 7pt;
    font-weight: 700;
    color: #0f3460;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-bottom: 1px solid #dbeafe;
    padding-bottom: 3px;
    margin: 8px 0 6px 0;
  }

  /* ── Tabla datos genérica ── */
  .t-datos {
    width: 100%;
    border-collapse: collapse;
  }
  .t-datos td {
    padding: 3px 5px;
    vertical-align: top;
    font-size: 8pt;
  }
  .t-datos .lbl {
    color: #6b7280;
    font-size: 7pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 120px;
    white-space: nowrap;
  }
  .t-datos .val {
    color: #1a1a2e;
  }
  .t-datos .val-strong {
    color: #0f3460;
    font-weight: 700;
    font-size: 9pt;
  }

  /* ── Caja info (fondo celeste claro) ── */
  .caja-info {
    background: #f0f7ff;
    border-left: 3px solid #0f3460;
    border-radius: 3px;
    padding: 10px 14px;
  }
  .caja-warn {
    background: #fffbeb;
    border-left: 3px solid #e2b96f;
    border-radius: 3px;
    padding: 10px 14px;
  }

  /* ── Dos columnas ── */
  .dos-col {
    display: table;
    width: 100%;
  }
  .col-l {
    display: table-cell;
    width: 50%;
    vertical-align: top;
    padding-right: 8px;
  }
  .col-r {
    display: table-cell;
    width: 50%;
    vertical-align: top;
    padding-left: 8px;
  }

  /* ── Tabla pasajeros ── */
  .t-pax {
    width: 100%;
    border-collapse: collapse;
    font-size: 7.5pt;
  }
  .t-pax thead tr {
    background: #0f3460;
    color: #ffffff;
  }
  .t-pax thead td {
    padding: 5px 7px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 7pt;
  }
  .t-pax tbody tr:nth-child(even) { background: #f8faff; }
  .t-pax tbody tr:nth-child(odd)  { background: #ffffff; }
  .t-pax tbody td {
    padding: 5px 7px;
    border-bottom: 0.5px solid #e5e7eb;
    color: #374151;
    vertical-align: top;
  }

  /* ── Badges ── */
  .badge {
    display: inline-block;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: 6.5pt;
    font-weight: 700;
    text-transform: uppercase;
  }
  .badge-titular  { background:#e2b96f; color:#0f3460; }
  .badge-adulto   { background:#dbeafe; color:#1e40af; }
  .badge-nino     { background:#fef3c7; color:#92400e; }

  /* ── Resumen de pago ── */
  .caja-pago {
    background: #f8faff;
    border: 1px solid #dbeafe;
    border-radius: 5px;
    padding: 12px 14px;
  }
  .pago-row {
    display: table;
    width: 100%;
    margin-bottom: 5px;
  }
  .pago-lbl {
    display: table-cell;
    color: #6b7280;
    font-size: 7.5pt;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .pago-val {
    display: table-cell;
    text-align: right;
    font-weight: 700;
    font-size: 9pt;
    color: #1a1a2e;
  }
  .pago-div { border-top: 1px solid #dbeafe; margin: 6px 0; }
  .pago-lbl-total { display:table-cell; color:#0f3460; font-size:8.5pt; font-weight:700; text-transform:uppercase; }
  .pago-val-total { display:table-cell; text-align:right; font-size:12pt; font-weight:700; color:#0f3460; }
  .c-green { color:#059669; }
  .c-red   { color:#dc2626; }

  /* ── Voucher ── */
  .caja-voucher {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    padding: 10px;
    text-align: center;
  }
  .voucher-img {
    max-width: 100%;
    max-height: 160px;
    border-radius: 3px;
    border: 1px solid #e5e7eb;
  }

  /* ── Políticas ── */
  .caja-politicas {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 4px;
    padding: 12px 16px;
    font-size: 7.5pt;
    color: #78350f;
    line-height: 1.6;
  }

  /* ── Salto de página ── */
  .page-break {
    page-break-before: always;
  }

  /* ── Footer ── */
  .footer {
    border-top: 2px solid #e2b96f;
    padding: 10px 30px 14px 30px;
    text-align: center;
    color: #6b7280;
    font-size: 7pt;
    margin-top: 14px;
  }
  .footer-brand {
    font-size: 9pt;
    font-weight: 700;
    color: #0f3460;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 2px;
  }
  .footer-aviso {
    font-size: 6.5pt;
    color: #9ca3af;
    margin-top: 3px;
    font-style: italic;
  }
</style>
</head>
<body>

{{-- ════════════════════ 1. ENCABEZADO EMPRESARIAL ════════════════════ --}}
<div class="header">
  <div class="header-top">
    <div class="header-left">
      <div class="brand-name">Adventur</div>
      <div class="brand-tagline">Turismo &amp; Experiencias</div>
      <div class="brand-datos">
        <span>RUC:</span> 20123456789 &nbsp;|&nbsp;
        <span>Razón Social:</span> HORIZONTE ANDINO COMPANY E.I.R.L.<br>
        <span>Dirección:</span> Jr. Amazonas 5 Esquinas Oficina – Cajamarca, Perú
      </div>
    </div>
    <div class="header-right">
      <div class="doc-title">Confirmación de Reserva Electrónica</div>
      <div class="doc-codigo">{{ $reserva->codigo_reserva }}</div>
      <div class="doc-meta">
        <strong style="color:#e2b96f">Emisión:</strong>
        {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}<br>
        @if($reserva->fecha_retorno)
          <strong style="color:#e2b96f">Vencimiento:</strong>
          {{ \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') }}
        @endif
      </div>
    </div>
  </div>
</div>
<div class="banner-confirmado">✓ &nbsp; Reserva Registrada Exitosamente &nbsp; ✓</div>

{{-- ════════════════════ CONTENIDO ════════════════════ --}}
<div class="contenido">

  {{-- ════════════════════ 2. DETALLE DEL VIAJE ════════════════════ --}}
  <div class="seccion">
    <div class="sec-titulo">2. Detalle del Viaje</div>
    <div class="caja-info">
      <div class="sec-subtitulo">Información del Viaje</div>
      <table class="t-datos">
        <tr>
          <td class="lbl">Tipo de paquete</td>
          <td class="val val-strong">{{ $reserva->nombre_tour }}</td>
          <td class="lbl">Destino</td>
          <td class="val">{{ $reserva->ciudad_destino ?? '—' }}{{ $reserva->departamento_destino ? ', '.$reserva->departamento_destino : '' }}</td>
        </tr>
        <tr>
          <td class="lbl">Fecha de arribo</td>
          <td class="val">
            {{ $reserva->fecha_arribo ? \Carbon\Carbon::parse($reserva->fecha_arribo)->format('d/m/Y') : '—' }}
            {{ $reserva->hora_arribo ? ' · '.substr($reserva->hora_arribo,0,5) : '' }}
          </td>
          <td class="lbl">Fecha de retorno</td>
          <td class="val">
            {{ $reserva->fecha_retorno ? \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') : '—' }}
            {{ $reserva->hora_retorno ? ' · '.substr($reserva->hora_retorno,0,5) : '' }}
          </td>
        </tr>
        <tr>
          <td class="lbl">Cantidad de días</td>
          <td class="val">{{ $reserva->dias_viaje ?? '—' }}</td>
          <td class="lbl">Hora de salida</td>
          <td class="val">{{ $reserva->hora_salida ? substr($reserva->hora_salida,0,5) : '—' }}</td>
        </tr>
        @if($reserva->punto_encuentro)
        <tr>
          <td class="lbl">Punto de encuentro</td>
          <td class="val" colspan="3">{{ $reserva->punto_encuentro }}
            {{ $reserva->hora_recojo ? ' — Recojo: '.substr($reserva->hora_recojo,0,5) : '' }}
          </td>
        </tr>
        @endif
      </table>

      {{-- Hospedaje --}}
      @if($reserva->nombre_hotel || $reserva->tipo_habitacion || $reserva->tipo_cama || $reserva->plan_alimentacion)
      <div class="sec-subtitulo" style="margin-top:10px">Hospedaje</div>
      <table class="t-datos">
        @if($reserva->nombre_hotel)
        <tr>
          <td class="lbl">Hotel</td>
          <td class="val val-strong">{{ $reserva->nombre_hotel }}</td>
          <td class="lbl">Tipo establecimiento</td>
          <td class="val">{{ $reserva->tipo_establecimiento ?? '—' }}</td>
        </tr>
        @endif
        <tr>
          <td class="lbl">Tipo de habitación</td>
          <td class="val">{{ $reserva->tipo_habitacion ?? '—' }}</td>
          <td class="lbl">Tipo de cama</td>
          <td class="val">{{ $reserva->tipo_cama ?? '—' }}</td>
        </tr>
        @if($reserva->plan_alimentacion)
        <tr>
          <td class="lbl">Pensión</td>
          <td class="val" colspan="3">
            @php
              $planes = ['RO'=>'Solo habitación','BB'=>'Con desayuno','HB'=>'Desayuno y cena','FB'=>'Pensión completa','AI'=>'Todo incluido'];
            @endphp
            {{ $planes[$reserva->plan_alimentacion] ?? $reserva->plan_alimentacion }}
          </td>
        </tr>
        @endif
      </table>
      @endif

      {{-- Transporte --}}
      @if($reserva->tipo_transporte)
      <div class="sec-subtitulo" style="margin-top:10px">Transporte</div>
      <table class="t-datos">
        <tr>
          <td class="lbl">Tipo de transporte</td>
          <td class="val">{{ ucfirst($reserva->tipo_transporte) }}</td>
          @if($reserva->tipo_transporte === 'terrestre' && $reserva->empresa_transporte)
          <td class="lbl">Empresa</td>
          <td class="val">{{ $reserva->empresa_transporte }}</td>
          @elseif($reserva->tipo_transporte === 'aereo')
          <td class="lbl">Aerolínea</td>
          <td class="val">{{ $reserva->aerolinea ?? '—' }}</td>
          @endif
        </tr>
        @if($reserva->tipo_transporte === 'aereo')
        <tr>
          <td class="lbl">N° de vuelo</td>
          <td class="val">{{ $reserva->numero_vuelo ?? '—' }}</td>
          <td class="lbl">Salida / Llegada</td>
          <td class="val">
            {{ $reserva->hora_salida_vuelo ? substr($reserva->hora_salida_vuelo,0,5) : '—' }}
            / {{ $reserva->hora_llegada_vuelo ? substr($reserva->hora_llegada_vuelo,0,5) : '—' }}
          </td>
        </tr>
        @endif
      </table>
      @endif
    </div>
  </div>

  {{-- ════════════════════ 3. TITULAR Y PASAJEROS ════════════════════ --}}
  <div class="seccion">
    <div class="sec-titulo">3. Titular y Pasajeros</div>

    {{-- Titular --}}
    <div class="sec-subtitulo">Titular de la Reserva</div>
    <div class="caja-info">
      <table class="t-datos">
        <tr>
          <td class="lbl">Nombre completo</td>
          <td class="val val-strong">{{ $reserva->cliente->nombre_completo }}</td>
          <td class="lbl">{{ strtoupper($reserva->cliente->tipo_documento ?? 'DNI') }}</td>
          <td class="val">{{ $reserva->cliente->numero_documento ?? '—' }}</td>
        </tr>
        <tr>
          <td class="lbl">Lugar de origen</td>
          <td class="val">{{ $reserva->ciudad_procedencia ?? '—' }}</td>
          <td class="lbl">Celular</td>
          <td class="val">{{ $reserva->cliente->telefono ?? '—' }}</td>
        </tr>
        <tr>
          <td class="lbl">Correo electrónico</td>
          <td class="val" colspan="3">{{ $reserva->cliente->email ?? '—' }}</td>
        </tr>
      </table>
    </div>

    {{-- Pasajeros adicionales --}}
    @php
      $adicionales = $reserva->pasajeros->where('es_titular', false);
    @endphp
    @if($adicionales->count() > 0)
    <div class="sec-subtitulo" style="margin-top:10px">Pasajeros Adicionales</div>
    <table class="t-pax">
      <thead>
        <tr>
          <td style="width:25px">#</td>
          <td>Nombre Completo</td>
          <td>Documento</td>
          <td>Fecha Nac.</td>
          <td>Edad</td>
          <td>Tipo</td>
          <td>Salud</td>
        </tr>
      </thead>
      <tbody>
        @foreach($adicionales as $i => $pax)
        <tr>
          <td style="text-align:center;color:#9ca3af">{{ $i + 1 }}</td>
          <td style="font-weight:600">{{ $pax->nombre_completo }}</td>
          <td style="color:#6b7280;font-size:7pt">
            {{ $pax->numero_documento ? strtoupper($pax->tipo_documento ?? 'DNI').': '.$pax->numero_documento : '—' }}
          </td>
          <td style="color:#6b7280;font-size:7pt">
            {{ $pax->fecha_nacimiento ? \Carbon\Carbon::parse($pax->fecha_nacimiento)->format('d/m/Y') : '—' }}
          </td>
          <td style="text-align:center;color:#6b7280;font-size:7pt">
            {{ $pax->edad ?? '—' }}
          </td>
          <td>
            @if(in_array($pax->tipo, ['nino','niño','bebe']))
              <span class="badge badge-nino">Niño</span>
            @else
              <span class="badge badge-adulto">Adulto</span>
            @endif
          </td>
          <td style="font-size:7pt;color:#6b7280">
            @if($pax->salud)
              @if($pax->salud->restricciones_alimentarias) {{ $pax->salud->restricciones_alimentarias }}<br> @endif
              @if($pax->salud->alergias) Alergia: {{ $pax->salud->alergias }} @endif
            @else
              Sin obs.
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>

  {{-- ════════════════════ 4. CONTACTO DE EMERGENCIA ════════════════════ --}}
  @if($reserva->cliente->emergencia_nombre || $reserva->cliente->emergencia_telefono)
  <div class="seccion">
    <div class="sec-titulo">4. Contacto de Emergencia</div>
    <div class="caja-warn">
      <table class="t-datos">
        <tr>
          <td class="lbl">Nombre completo</td>
          <td class="val val-strong">{{ $reserva->cliente->emergencia_nombre ?? '—' }}</td>
          <td class="lbl">Parentesco</td>
          <td class="val">{{ $reserva->cliente->emergencia_parentesco ?? '—' }}</td>
        </tr>
        <tr>
          <td class="lbl">Teléfono</td>
          <td class="val">{{ $reserva->cliente->emergencia_telefono ?? '—' }}</td>
          <td class="lbl">Correo</td>
          <td class="val">{{ $reserva->cliente->email ?? '—' }}</td>
        </tr>
      </table>
    </div>
  </div>
  @endif

  {{-- ════════════════════ 5. INFORMACIÓN DE PAGO ════════════════════ --}}
  <div class="seccion">
    <div class="sec-titulo">5. Información de Pago</div>
    <div class="dos-col">
      <div class="col-l">
        <div class="sec-subtitulo">Resumen de Pago</div>
        <div class="caja-pago">
          @php
            $total  = (float) $reserva->precio_total;
            $pagado = (float) $reserva->monto_pagado;
            $saldo  = max(0, $total - $pagado);
          @endphp
          <div class="pago-row">
            <div class="pago-lbl">Costo total</div>
            <div class="pago-val">S/ {{ number_format($total, 2) }}</div>
          </div>
          <div class="pago-div"></div>
          <div class="pago-row">
            <div class="pago-lbl">Adelanto pagado</div>
            <div class="pago-val c-green">S/ {{ number_format($pagado, 2) }}</div>
          </div>
          <div class="pago-div"></div>
          <div class="pago-row">
            <div class="pago-lbl-total">Saldo pendiente</div>
            <div class="pago-val-total {{ $saldo > 0 ? 'c-red' : 'c-green' }}">
              S/ {{ number_format($saldo, 2) }}
            </div>
          </div>
          @if($saldo <= 0)
          <div style="margin-top:8px;text-align:center">
            <span style="background:#059669;color:#fff;padding:3px 12px;border-radius:10px;font-size:7pt;font-weight:700;">
              ✓ PAGADO COMPLETAMENTE
            </span>
          </div>
          @endif
        </div>
      </div>

      <div class="col-r">
        <div class="sec-subtitulo">Medio de Pago</div>
        @php $pago = $reserva->pagos->first(); @endphp
        @if($pago)
        <div class="caja-info">
          <table class="t-datos">
            <tr>
              <td class="lbl">Método</td>
              <td class="val val-strong">{{ $pago->metodoPago->nombre ?? '—' }}</td>
            </tr>
            @if($reserva->tipo_comprobante)
            <tr>
              <td class="lbl">Comprobante</td>
              <td class="val">{{ ucfirst($reserva->tipo_comprobante) }}</td>
            </tr>
            @endif
            @if($reserva->tipo_comprobante === 'factura' && $reserva->razon_social)
            <tr>
              <td class="lbl">Razón social</td>
              <td class="val">{{ $reserva->razon_social }}</td>
            </tr>
            <tr>
              <td class="lbl">RUC</td>
              <td class="val">{{ $reserva->ruc_factura ?? '—' }}</td>
            </tr>
            @endif
          </table>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>{{-- fin contenido --}}

{{-- ════════════════════ 7. POLÍTICAS (página aparte) ════════════════════ --}}
@if($reserva->politica_descripcion)
<div class="page-break">
  <div class="header" style="padding:14px 30px">
    <div style="display:table;width:100%">
      <div style="display:table-cell;vertical-align:middle">
        <span style="font-size:18pt;font-weight:700;color:#e2b96f;letter-spacing:4px">Adventur</span>
        <span style="font-size:7.5pt;color:#a8c8e8;margin-left:10px;letter-spacing:1px">Turismo &amp; Experiencias</span>
      </div>
      <div style="display:table-cell;vertical-align:middle;text-align:right">
        <span style="font-size:7pt;color:#a8c8e8">Reserva: </span>
        <span style="font-size:10pt;font-weight:700;color:#e2b96f">{{ $reserva->codigo_reserva }}</span>
      </div>
    </div>
  </div>
  <div class="banner-confirmado" style="letter-spacing:2px;font-size:8pt">
    7. Políticas y Condiciones
  </div>
  <div class="contenido">
    <div class="caja-politicas">
      {!! nl2br(e($reserva->politica_descripcion)) !!}
    </div>
  </div>
</div>
@endif

{{-- ════════════════════ FOOTER ════════════════════ --}}
<div class="footer">
  <div class="footer-brand">Adventur — Horizonte Andino Company E.I.R.L.</div>
  <div>Jr. Amazonas 5 Esquinas Oficina – Cajamarca, Perú &nbsp;|&nbsp; RUC: 20123456789</div>
  <div>Este documento es tu comprobante oficial de reserva. Preséntalo el día del servicio.</div>
  <div class="footer-aviso">
    Generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}
  </div>
</div>

</body>
</html>