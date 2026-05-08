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
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 9pt;
    color: #1a1a2e;
    background: #ffffff;
  }

  /* ── CABECERA ─────────────────────────────────────────────────── */
  .header {
    background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
    color: #ffffff;
    padding: 22px 30px 18px 30px;
    position: relative;
  }
  .header-inner {
    display: table;
    width: 100%;
  }
  .header-logo {
    display: table-cell;
    vertical-align: middle;
    width: 55%;
  }
  .brand-name {
    font-size: 26pt;
    font-weight: 700;
    letter-spacing: 4px;
    color: #e2b96f;
    text-transform: uppercase;
  }
  .brand-tagline {
    font-size: 8pt;
    color: #a8c8e8;
    letter-spacing: 2px;
    margin-top: 2px;
  }
  .header-codigo {
    display: table-cell;
    vertical-align: middle;
    text-align: right;
    width: 45%;
  }
  .codigo-label {
    font-size: 7pt;
    color: #a8c8e8;
    letter-spacing: 2px;
    text-transform: uppercase;
  }
  .codigo-valor {
    font-size: 18pt;
    font-weight: 700;
    color: #e2b96f;
    letter-spacing: 3px;
  }
  .codigo-fecha {
    font-size: 7.5pt;
    color: #a8c8e8;
    margin-top: 3px;
  }

  /* ── BANNER CONFIRMADO ─────────────────────────────────────────── */
  .banner-confirmado {
    background: #e2b96f;
    color: #0f3460;
    text-align: center;
    padding: 7px;
    font-size: 9pt;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
  }

  /* ── CONTENIDO ────────────────────────────────────────────────── */
  .contenido {
    padding: 22px 30px;
  }

  /* ── SECCIÓN ──────────────────────────────────────────────────── */
  .seccion {
    margin-bottom: 16px;
  }
  .seccion-titulo {
    font-size: 7.5pt;
    font-weight: 700;
    color: #e2b96f;
    text-transform: uppercase;
    letter-spacing: 2px;
    border-bottom: 1.5px solid #e2b96f;
    padding-bottom: 4px;
    margin-bottom: 10px;
  }
  .seccion-titulo-icon {
    color: #0f3460;
    background: #e2b96f;
    padding: 1px 5px;
    border-radius: 2px;
    margin-right: 4px;
    font-size: 7pt;
  }

  /* ── TABLA DOS COLUMNAS ────────────────────────────────────────── */
  .tabla-datos {
    width: 100%;
    border-collapse: collapse;
  }
  .tabla-datos td {
    padding: 4px 6px;
    vertical-align: top;
    font-size: 8.5pt;
  }
  .tabla-datos .lbl {
    color: #6b7280;
    font-size: 7.5pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 130px;
    white-space: nowrap;
  }
  .tabla-datos .val {
    color: #1a1a2e;
    font-weight: 400;
  }
  .tabla-datos .val-destacado {
    color: #0f3460;
    font-weight: 700;
    font-size: 9.5pt;
  }

  /* ── CAJA TOUR ────────────────────────────────────────────────── */
  .caja-tour {
    background: #f0f7ff;
    border-left: 4px solid #0f3460;
    border-radius: 4px;
    padding: 12px 16px;
    margin-bottom: 4px;
  }
  .tour-nombre {
    font-size: 13pt;
    font-weight: 700;
    color: #0f3460;
    margin-bottom: 6px;
  }
  .tour-meta {
    display: table;
    width: 100%;
  }
  .tour-meta-item {
    display: table-cell;
    vertical-align: middle;
    padding-right: 18px;
    font-size: 8pt;
    color: #374151;
  }
  .tour-meta-label {
    color: #6b7280;
    font-size: 7pt;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: block;
  }
  .tour-meta-valor {
    font-weight: 700;
    color: #0f3460;
    font-size: 9pt;
    display: block;
  }
  .badge-pasajeros {
    display: inline-block;
    background: #0f3460;
    color: #ffffff;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 7.5pt;
    font-weight: 700;
  }

  /* ── TABLA PASAJEROS ──────────────────────────────────────────── */
  .tabla-pasajeros {
    width: 100%;
    border-collapse: collapse;
    font-size: 8pt;
  }
  .tabla-pasajeros thead tr {
    background: #0f3460;
    color: #ffffff;
  }
  .tabla-pasajeros thead td {
    padding: 5px 8px;
    font-size: 7.5pt;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .tabla-pasajeros tbody tr:nth-child(even) {
    background: #f8faff;
  }
  .tabla-pasajeros tbody tr:nth-child(odd) {
    background: #ffffff;
  }
  .tabla-pasajeros tbody td {
    padding: 5px 8px;
    color: #374151;
    border-bottom: 0.5px solid #e5e7eb;
  }
  .badge-titular {
    background: #e2b96f;
    color: #0f3460;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: 6.5pt;
    font-weight: 700;
    text-transform: uppercase;
  }
  .badge-adulto {
    background: #dbeafe;
    color: #1e40af;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: 6.5pt;
    font-weight: 700;
  }
  .badge-nino {
    background: #fef3c7;
    color: #92400e;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: 6.5pt;
    font-weight: 700;
  }

  /* ── RESUMEN PAGO ─────────────────────────────────────────────── */
  .caja-pago {
    background: #f8faff;
    border: 1px solid #dbeafe;
    border-radius: 6px;
    padding: 14px 18px;
  }
  .pago-fila {
    display: table;
    width: 100%;
    margin-bottom: 6px;
  }
  .pago-col {
    display: table-cell;
    vertical-align: middle;
  }
  .pago-label {
    color: #6b7280;
    font-size: 8pt;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .pago-valor {
    text-align: right;
    font-size: 9.5pt;
    font-weight: 700;
    color: #1a1a2e;
  }
  .pago-divisor {
    border-top: 1px solid #dbeafe;
    margin: 8px 0;
  }
  .pago-total-label {
    color: #0f3460;
    font-size: 9.5pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .pago-total-valor {
    text-align: right;
    font-size: 13pt;
    font-weight: 700;
    color: #0f3460;
  }
  .pago-pagado-valor {
    color: #059669;
  }
  .pago-saldo-valor {
    color: #dc2626;
  }
  .pago-cero-valor {
    color: #059669;
  }

  /* ── LAYOUT DOS COLUMNAS ──────────────────────────────────────── */
  .dos-col {
    display: table;
    width: 100%;
    border-collapse: separate;
    border-spacing: 10px 0;
  }
  .col-izq {
    display: table-cell;
    width: 55%;
    vertical-align: top;
  }
  .col-der {
    display: table-cell;
    width: 45%;
    vertical-align: top;
  }

  /* ── POLÍTICAS ────────────────────────────────────────────────── */
  .caja-politicas {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-left: 4px solid #f59e0b;
    border-radius: 4px;
    padding: 10px 14px;
    font-size: 7.5pt;
    color: #78350f;
    line-height: 1.5;
  }

  /* ── PIE DE PÁGINA ────────────────────────────────────────────── */
  .footer {
    margin-top: 14px;
    border-top: 1.5px solid #e2b96f;
    padding-top: 10px;
    padding-left: 30px;
    padding-right: 30px;
    padding-bottom: 16px;
    text-align: center;
    color: #6b7280;
    font-size: 7.5pt;
  }
  .footer-empresa {
    font-size: 9pt;
    font-weight: 700;
    color: #0f3460;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 3px;
  }
  .footer-aviso {
    font-size: 7pt;
    color: #9ca3af;
    margin-top: 4px;
    font-style: italic;
  }

  /* ── CONTACTO ─────────────────────────────────────────────────── */
  .caja-contacto {
    background: #0f3460;
    color: #a8c8e8;
    padding: 8px 14px;
    border-radius: 4px;
    font-size: 7.5pt;
    text-align: center;
    margin-top: 6px;
  }
  .caja-contacto strong {
    color: #e2b96f;
  }
</style>
</head>
<body>

{{-- ═══════════════════════════════ CABECERA ═══════════════════════════════ --}}
<div class="header">
  <div class="header-inner">
    <div class="header-logo">
      <div class="brand-name">Adventur</div>
      <div class="brand-tagline">Turismo &amp; Experiencias</div>
    </div>
    <div class="header-codigo">
      <div class="codigo-label">Código de Reserva</div>
      <div class="codigo-valor">{{ $reserva->codigo_reserva }}</div>
      <div class="codigo-fecha">
        Emitido: {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}
      </div>
    </div>
  </div>
</div>

<div class="banner-confirmado">✓ &nbsp; Reserva Registrada Exitosamente &nbsp; ✓</div>

{{-- ═══════════════════════════════ CONTENIDO ══════════════════════════════ --}}
<div class="contenido">

  {{-- ── BLOQUE: TOUR ──────────────────────────────────────────────────── --}}
  <div class="seccion">
    <div class="seccion-titulo">Detalle del Servicio</div>
    <div class="caja-tour">
      <div class="tour-nombre">{{ $reserva->nombre_tour }}</div>
      <div class="tour-meta">
        <div class="tour-meta-item">
          <span class="tour-meta-label">Fecha del tour</span>
          <span class="tour-meta-valor">
            {{ $reserva->fecha_tour
                ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y')
                : '—' }}
          </span>
        </div>
        @if($reserva->hora_salida)
        <div class="tour-meta-item">
          <span class="tour-meta-label">Hora de salida</span>
          <span class="tour-meta-valor">{{ substr($reserva->hora_salida, 0, 5) }}</span>
        </div>
        @endif
        @if($reserva->ciudad_destino)
        <div class="tour-meta-item">
          <span class="tour-meta-label">Destino</span>
          <span class="tour-meta-valor">{{ $reserva->ciudad_destino }}</span>
        </div>
        @endif
        @if($reserva->dias_viaje)
        <div class="tour-meta-item">
          <span class="tour-meta-label">Duración</span>
          <span class="tour-meta-valor">{{ $reserva->dias_viaje }} día(s)</span>
        </div>
        @endif
        <div class="tour-meta-item">
          <span class="tour-meta-label">Pasajeros</span>
          <span class="tour-meta-valor">
            <span class="badge-pasajeros">
              {{ $reserva->cantidad_adultos }} adulto(s)
              @if($reserva->cantidad_ninos > 0)
                &nbsp;+&nbsp; {{ $reserva->cantidad_ninos }} niño(s)
              @endif
            </span>
          </span>
        </div>
      </div>

      @if($reserva->punto_encuentro || $reserva->hora_recojo)
      <div style="margin-top:8px; font-size:8pt; color:#374151;">
        @if($reserva->punto_encuentro)
          <strong>Punto de encuentro:</strong> {{ $reserva->punto_encuentro }}
          &nbsp;&nbsp;
        @endif
        @if($reserva->hora_recojo)
          <strong>Hora de recojo:</strong> {{ substr($reserva->hora_recojo, 0, 5) }}
        @endif
      </div>
      @endif
    </div>
  </div>

  {{-- ── DOS COLUMNAS: TITULAR + PAGO ─────────────────────────────────── --}}
  <div class="dos-col">

    {{-- Columna izquierda: Titular --}}
    <div class="col-izq">
      <div class="seccion">
        <div class="seccion-titulo">Titular de la Reserva</div>
        <table class="tabla-datos">
          <tr>
            <td class="lbl">Nombre</td>
            <td class="val val-destacado">{{ $reserva->cliente->nombre_completo }}</td>
          </tr>
          @if($reserva->cliente->numero_documento)
          <tr>
            <td class="lbl">{{ strtoupper($reserva->cliente->tipo_documento ?? 'DNI') }}</td>
            <td class="val">{{ $reserva->cliente->numero_documento }}</td>
          </tr>
          @endif
          <tr>
            <td class="lbl">Teléfono</td>
            <td class="val">{{ $reserva->cliente->telefono }}</td>
          </tr>
          @if($reserva->cliente->email)
          <tr>
            <td class="lbl">Email</td>
            <td class="val">{{ $reserva->cliente->email }}</td>
          </tr>
          @endif
          @if($reserva->cliente->nacionalidad)
          <tr>
            <td class="lbl">Nacionalidad</td>
            <td class="val">{{ $reserva->cliente->nacionalidad }}</td>
          </tr>
          @endif
          @if($reserva->ciudad_procedencia)
          <tr>
            <td class="lbl">Ciudad origen</td>
            <td class="val">{{ $reserva->ciudad_procedencia }}</td>
          </tr>
          @endif
        </table>
      </div>
    </div>

    {{-- Columna derecha: Resumen de pago --}}
    <div class="col-der">
      <div class="seccion">
        <div class="seccion-titulo">Resumen de Pago</div>
        <div class="caja-pago">
          @php
            $total  = (float) $reserva->precio_total;
            $pagado = (float) $reserva->monto_pagado;
            $saldo  = max(0, $total - $pagado);
          @endphp

          <div class="pago-fila">
            <div class="pago-col pago-label">Total del servicio</div>
            <div class="pago-col pago-valor">S/ {{ number_format($total, 2) }}</div>
          </div>

          <div class="pago-divisor"></div>

          <div class="pago-fila">
            <div class="pago-col pago-label">Monto pagado</div>
            <div class="pago-col pago-valor pago-pagado-valor">
              S/ {{ number_format($pagado, 2) }}
            </div>
          </div>

          <div class="pago-divisor"></div>

          <div class="pago-fila">
            <div class="pago-col pago-total-label">Saldo pendiente</div>
            <div class="pago-col pago-total-valor {{ $saldo > 0 ? 'pago-saldo-valor' : 'pago-cero-valor' }}">
              S/ {{ number_format($saldo, 2) }}
            </div>
          </div>

          @if($saldo <= 0)
          <div style="margin-top:8px; text-align:center;">
            <span style="background:#059669;color:#fff;padding:3px 12px;border-radius:10px;font-size:7.5pt;font-weight:700;">
              ✓ PAGADO COMPLETAMENTE
            </span>
          </div>
          @endif
        </div>

        {{-- Tipo de comprobante --}}
        @if($reserva->tipo_comprobante)
        <div style="margin-top:8px; font-size:8pt; color:#6b7280;">
          <strong>Comprobante:</strong>
          {{ ucfirst($reserva->tipo_comprobante) }}
          @if($reserva->tipo_comprobante === 'factura' && $reserva->razon_social)
            — {{ $reserva->razon_social }}
            @if($reserva->ruc_factura) (RUC: {{ $reserva->ruc_factura }}) @endif
          @endif
        </div>
        @endif
      </div>
    </div>

  </div>{{-- fin dos-col --}}

  {{-- ── LISTA DE PASAJEROS ────────────────────────────────────────────── --}}
  @if($reserva->pasajeros && $reserva->pasajeros->count() > 0)
  <div class="seccion">
    <div class="seccion-titulo">Lista de Pasajeros</div>
    <table class="tabla-pasajeros">
      <thead>
        <tr>
          <td style="width:32px">#</td>
          <td>Nombre Completo</td>
          <td>Documento</td>
          <td>Tipo</td>
          <td>Condición</td>
        </tr>
      </thead>
      <tbody>
        @foreach($reserva->pasajeros as $i => $pasajero)
        <tr>
          <td style="color:#9ca3af; text-align:center;">{{ $i + 1 }}</td>
          <td style="font-weight:{{ $pasajero->es_titular ? '700' : '400' }};">
            {{ $pasajero->nombre_completo }}
            @if($pasajero->es_titular)
              &nbsp;<span class="badge-titular">Titular</span>
            @endif
          </td>
          <td style="color:#6b7280;">
            @if($pasajero->numero_documento)
              {{ strtoupper($pasajero->tipo_documento ?? 'DNI') }}: {{ $pasajero->numero_documento }}
            @else
              —
            @endif
          </td>
          <td>
            @if($pasajero->tipo === 'nino' || $pasajero->tipo === 'niño')
              <span class="badge-nino">Niño</span>
            @else
              <span class="badge-adulto">Adulto</span>
            @endif
          </td>
          <td style="color:#6b7280; font-size:7.5pt;">
            @if($pasajero->salud)
              @if($pasajero->salud->alergias)
                Alergia: {{ $pasajero->salud->alergias }}
              @endif
              @if($pasajero->salud->restricciones_alimentarias)
                Restricción alimentaria
              @endif
              @if($pasajero->salud->condiciones_medicas)
                Obs. médica
              @endif
            @else
              Sin obs.
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif

  {{-- ── POLÍTICAS ──────────────────────────────────────────────────────── --}}
  @if($reserva->politica_descripcion)
  <div class="seccion">
    <div class="seccion-titulo">Políticas y Condiciones</div>
    <div class="caja-politicas">
      {!! nl2br(e($reserva->politica_descripcion)) !!}
    </div>
  </div>
  @endif

  {{-- ── CONTACTO ────────────────────────────────────────────────────────── --}}
  <div class="caja-contacto">
    Para consultas sobre tu reserva escríbenos y menciona tu código
    <strong>{{ $reserva->codigo_reserva }}</strong>.
    &nbsp;¡Gracias por elegir <strong>Adventur</strong>! 🌿
  </div>

</div>{{-- fin contenido --}}

{{-- ═══════════════════════════════ FOOTER ════════════════════════════════ --}}
<div class="footer">
  <div class="footer-empresa">Adventur — Turismo &amp; Experiencias</div>
  <div>Este documento es tu comprobante de reserva. Preséntalo el día del tour.</div>
  <div class="footer-aviso">
    Documento generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}
  </div>
</div>

</body>
</html>