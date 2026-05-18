{{-- =====================================================================
     ARCHIVO: resources/views/emails/confirmacion-reserva.blade.php
     USADO POR: MailService::enviarConfirmacion()
     VERSIÓN: 2.0 — Rediseño profesional: amarillo #FACC15 + azul #1E40AF
     ===================================================================== --}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>Confirmación de Reserva — {{ $reserva->codigo_reserva }}</title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
</head>
<body style="margin:0;padding:0;background:#EFF6FF;font-family:'Helvetica Neue',Arial,sans-serif;font-size:14px;color:#1E293B;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" role="presentation" style="background:#EFF6FF;padding:40px 16px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0" role="presentation" style="max-width:600px;width:100%;">

        {{-- ══════════════════════════════════
             CABECERA
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#1E40AF;border-radius:14px 14px 0 0;padding:0;">

            {{-- Franja superior decorativa --}}
            <div style="background:#FACC15;height:5px;border-radius:14px 14px 0 0;"></div>

            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td style="padding:28px 36px 24px 36px;">
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>

                      {{-- Logo / Nombre --}}
                      <td style="vertical-align:middle;">
                        <table cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td style="vertical-align:middle;">

                              {{-- Ícono cuadrado marca --}}
                              <table cellpadding="0" cellspacing="0" role="presentation" style="display:inline-table;vertical-align:middle;">
                                <tr>
                                  <td style="background:#FACC15;border-radius:10px;width:42px;height:42px;text-align:center;vertical-align:middle;">
                                    <span style="font-size:18px;font-weight:900;color:#1E3A8A;letter-spacing:0;line-height:42px;">A</span>
                                  </td>
                                </tr>
                              </table>

                              <table cellpadding="0" cellspacing="0" role="presentation" style="display:inline-table;vertical-align:middle;margin-left:12px;">
                                <tr>
                                  <td>
                                    <div style="font-size:22px;font-weight:900;color:#FACC15;letter-spacing:5px;text-transform:uppercase;line-height:1.1;">
                                      Adventur
                                    </div>
                                    <div style="font-size:9px;color:#93C5FD;letter-spacing:2.5px;margin-top:3px;text-transform:uppercase;font-weight:500;">
                                      Turismo &amp; Experiencias
                                    </div>
                                  </td>
                                </tr>
                              </table>

                            </td>
                          </tr>
                          <tr>
                            <td style="padding-top:6px;">
                              <div style="font-size:9px;color:#60A5FA;letter-spacing:0.5px;">
                                HORIZONTE ANDINO COMPANY E.I.R.L. &nbsp;&bull;&nbsp; Cajamarca, Per&uacute;
                              </div>
                            </td>
                          </tr>
                        </table>
                      </td>

                      {{-- Código reserva --}}
                      <td style="text-align:right;vertical-align:top;">
                        <div style="font-size:9px;color:#93C5FD;letter-spacing:2px;text-transform:uppercase;margin-bottom:5px;font-weight:600;">
                          C&oacute;digo de Reserva
                        </div>
                        <div style="background:#FACC15;border-radius:8px;padding:6px 14px;display:inline-block;">
                          <span style="font-size:18px;font-weight:900;color:#1E3A8A;letter-spacing:2px;font-family:'Courier New',monospace;">
                            {{ $reserva->codigo_reserva }}
                          </span>
                        </div>
                        <div style="font-size:9px;color:#60A5FA;margin-top:7px;">
                          {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}
                        </div>
                      </td>

                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══════════════════════════════════
             BANNER CONFIRMACIÓN
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#FACC15;padding:11px 36px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td style="text-align:center;">
                  <span style="font-size:10px;font-weight:800;color:#1E3A8A;letter-spacing:4px;text-transform:uppercase;">
                    &#10003;&nbsp;&nbsp; Reserva Registrada Exitosamente &nbsp;&nbsp;&#10003;
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══════════════════════════════════
             SALUDO
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#ffffff;padding:30px 36px 18px 36px;">
            <p style="font-size:18px;color:#1E40AF;margin:0 0 10px 0;font-weight:800;line-height:1.3;">
              Hola, {{ $reserva->cliente->nombre_completo }}
            </p>
            <p style="color:#475569;line-height:1.75;margin:0;font-size:13px;">
              Tu reserva ha sido registrada exitosamente en nuestro sistema.
              A continuaci&oacute;n encontrar&aacute;s el resumen completo.
              El <strong style="color:#1E40AF;">PDF con todos los detalles oficiales</strong>
              se encuentra adjunto a este correo.
            </p>
          </td>
        </tr>

        {{-- ══════════════════════════════════
             SECCIÓN: DETALLES DEL VIAJE
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#ffffff;padding:8px 36px 8px 36px;">

            {{-- Encabezado sección --}}
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td style="padding-bottom:10px;">
                  <table cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td style="background:#1E40AF;border-radius:6px;width:4px;height:18px;"></td>
                      <td style="padding-left:10px;font-size:11px;font-weight:800;color:#1E40AF;text-transform:uppercase;letter-spacing:2px;vertical-align:middle;">
                        Detalles del Viaje
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            {{-- Card viaje --}}
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="background:#EFF6FF;border:1.5px solid #BFDBFE;border-radius:10px;">
              <tr>
                <td style="padding:18px 22px;">

                  {{-- Nombre del tour --}}
                  <div style="font-size:15px;font-weight:800;color:#1E3A8A;margin-bottom:14px;padding-bottom:12px;border-bottom:1.5px solid #BFDBFE;line-height:1.3;">
                    {{ $reserva->nombre_tour }}
                  </div>

                  {{-- Grid de datos --}}
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      @if($reserva->fecha_arribo)
                      <td style="vertical-align:top;padding-right:16px;padding-bottom:10px;width:25%;">
                        <div style="font-size:8px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:3px;font-weight:700;">Arribo</div>
                        <div style="font-size:13px;font-weight:700;color:#1E3A8A;">{{ \Carbon\Carbon::parse($reserva->fecha_arribo)->format('d/m/Y') }}</div>
                      </td>
                      @endif
                      @if($reserva->fecha_retorno)
                      <td style="vertical-align:top;padding-right:16px;padding-bottom:10px;width:25%;">
                        <div style="font-size:8px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:3px;font-weight:700;">Retorno</div>
                        <div style="font-size:13px;font-weight:700;color:#1E3A8A;">{{ \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') }}</div>
                      </td>
                      @endif
                      @if($reserva->ciudad_destino)
                      <td style="vertical-align:top;padding-right:16px;padding-bottom:10px;width:25%;">
                        <div style="font-size:8px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:3px;font-weight:700;">Destino</div>
                        <div style="font-size:13px;font-weight:700;color:#1E3A8A;">{{ $reserva->ciudad_destino }}</div>
                      </td>
                      @endif
                      @if($reserva->dias_viaje)
                      <td style="vertical-align:top;padding-bottom:10px;width:25%;">
                        <div style="font-size:8px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:3px;font-weight:700;">Duraci&oacute;n</div>
                        <div style="font-size:13px;font-weight:700;color:#1E3A8A;">{{ $reserva->dias_viaje }} d&iacute;as</div>
                      </td>
                      @endif
                    </tr>
                  </table>

                  {{-- Pasajeros --}}
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                         style="background:#DBEAFE;border-radius:7px;margin-top:4px;">
                    <tr>
                      <td style="padding:9px 14px;">
                        <table cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td style="vertical-align:middle;">
                              <table cellpadding="0" cellspacing="0" role="presentation"
                                     style="background:#1E40AF;border-radius:5px;width:24px;height:24px;">
                                <tr>
                                  <td style="text-align:center;vertical-align:middle;">
                                    <span style="font-size:12px;color:#FACC15;font-weight:800;line-height:24px;">P</span>
                                  </td>
                                </tr>
                              </table>
                            </td>
                            <td style="padding-left:10px;vertical-align:middle;">
                              <span style="font-size:12px;font-weight:700;color:#1E3A8A;">
                                {{ $reserva->cantidad_adultos }} adulto(s)
                                @if($reserva->cantidad_ninos > 0)
                                  &nbsp;+&nbsp; {{ $reserva->cantidad_ninos }} ni&ntilde;o(s)
                                @endif
                              </span>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>

                  {{-- Hotel --}}
                  @if($reserva->nombre_hotel)
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                         style="margin-top:10px;border-top:1px solid #BFDBFE;">
                    <tr>
                      <td style="padding-top:10px;">
                        <table cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td style="font-size:9px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;font-weight:700;vertical-align:middle;">
                              Alojamiento
                            </td>
                          </tr>
                          <tr>
                            <td style="font-size:12px;font-weight:700;color:#1E3A8A;padding-top:3px;">
                              {{ $reserva->nombre_hotel }}
                              @if($reserva->tipo_habitacion)
                                <span style="font-weight:400;color:#475569;">&nbsp;&bull;&nbsp; {{ $reserva->tipo_habitacion }}</span>
                              @endif
                              @if($reserva->plan_alimentacion)
                                @php $planes=['RO'=>'Solo habitaci&oacute;n','BB'=>'Con desayuno','HB'=>'Desayuno y cena','FB'=>'Pensi&oacute;n completa','AI'=>'Todo incluido']; @endphp
                                <span style="font-weight:400;color:#475569;">&nbsp;&bull;&nbsp; {{ $planes[$reserva->plan_alimentacion] ?? $reserva->plan_alimentacion }}</span>
                              @endif
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  @endif

                  {{-- Transporte --}}
                  @if($reserva->tipo_transporte)
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                         style="margin-top:8px;border-top:1px solid #BFDBFE;">
                    <tr>
                      <td style="padding-top:10px;">
                        <div style="font-size:9px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;font-weight:700;margin-bottom:3px;">
                          Transporte
                        </div>
                        <div style="font-size:12px;font-weight:700;color:#1E3A8A;">
                          {{ ucfirst($reserva->tipo_transporte) }}
                          @if($reserva->empresa_transporte)
                            <span style="font-weight:400;color:#475569;">&nbsp;&bull;&nbsp; {{ $reserva->empresa_transporte }}</span>
                          @endif
                          @if($reserva->aerolinea)
                            <span style="font-weight:400;color:#475569;">&nbsp;&bull;&nbsp; {{ $reserva->aerolinea }}</span>
                            @if($reserva->numero_vuelo)
                              <span style="font-weight:400;color:#475569;">&nbsp;({{ $reserva->numero_vuelo }})</span>
                            @endif
                          @endif
                        </div>
                      </td>
                    </tr>
                  </table>
                  @endif

                  {{-- Punto de encuentro --}}
                  @if($reserva->punto_encuentro)
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                         style="margin-top:8px;border-top:1px solid #BFDBFE;">
                    <tr>
                      <td style="padding-top:10px;">
                        <div style="font-size:9px;color:#64748B;text-transform:uppercase;letter-spacing:1.5px;font-weight:700;margin-bottom:3px;">
                          Punto de encuentro
                        </div>
                        <div style="font-size:12px;font-weight:700;color:#1E3A8A;">
                          {{ $reserva->punto_encuentro }}
                          @if($reserva->hora_recojo)
                            <span style="font-weight:400;color:#475569;">&nbsp;&bull;&nbsp; Recojo: {{ substr($reserva->hora_recojo,0,5) }}</span>
                          @endif
                        </div>
                      </td>
                    </tr>
                  </table>
                  @endif

                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══════════════════════════════════
             SECCIÓN: TITULAR
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#ffffff;padding:18px 36px 8px 36px;">

            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td style="padding-bottom:10px;">
                  <table cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td style="background:#1E40AF;border-radius:6px;width:4px;height:18px;"></td>
                      <td style="padding-left:10px;font-size:11px;font-weight:800;color:#1E40AF;text-transform:uppercase;letter-spacing:2px;vertical-align:middle;">
                        Titular de la Reserva
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="background:#F8FAFF;border:1.5px solid #BFDBFE;border-radius:10px;">
              <tr>
                <td style="padding:18px 22px;">
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">

                    <tr>
                      <td style="padding:5px 0;width:140px;">
                        <span style="font-size:10px;color:#64748B;font-weight:600;">Nombre completo</span>
                      </td>
                      <td style="padding:5px 0;">
                        <span style="font-size:13px;font-weight:800;color:#1E3A8A;">{{ $reserva->cliente->nombre_completo }}</span>
                      </td>
                    </tr>

                    @if($reserva->cliente->numero_documento)
                    <tr>
                      <td style="padding:5px 0;border-top:1px solid #DBEAFE;">
                        <span style="font-size:10px;color:#64748B;font-weight:600;">{{ strtoupper($reserva->cliente->tipo_documento ?? 'DNI') }}</span>
                      </td>
                      <td style="padding:5px 0;border-top:1px solid #DBEAFE;">
                        <span style="font-size:12px;color:#334155;font-family:'Courier New',monospace;font-weight:600;">{{ $reserva->cliente->numero_documento }}</span>
                      </td>
                    </tr>
                    @endif

                    <tr>
                      <td style="padding:5px 0;border-top:1px solid #DBEAFE;">
                        <span style="font-size:10px;color:#64748B;font-weight:600;">Celular</span>
                      </td>
                      <td style="padding:5px 0;border-top:1px solid #DBEAFE;">
                        <span style="font-size:12px;color:#334155;font-weight:600;">{{ $reserva->cliente->telefono ?? '&mdash;' }}</span>
                      </td>
                    </tr>

                    @if($reserva->cliente->email)
                    <tr>
                      <td style="padding:5px 0;border-top:1px solid #DBEAFE;">
                        <span style="font-size:10px;color:#64748B;font-weight:600;">Correo</span>
                      </td>
                      <td style="padding:5px 0;border-top:1px solid #DBEAFE;">
                        <span style="font-size:12px;color:#1E40AF;font-weight:600;">{{ $reserva->cliente->email }}</span>
                      </td>
                    </tr>
                    @endif

                  </table>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- ══════════════════════════════════
             SECCIÓN: RESUMEN DE PAGO
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#ffffff;padding:18px 36px 28px 36px;">

            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
              <tr>
                <td style="padding-bottom:10px;">
                  <table cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td style="background:#1E40AF;border-radius:6px;width:4px;height:18px;"></td>
                      <td style="padding-left:10px;font-size:11px;font-weight:800;color:#1E40AF;text-transform:uppercase;letter-spacing:2px;vertical-align:middle;">
                        Resumen de Pago
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            @php
              $total  = (float) $reserva->precio_total;
              $pagado = (float) $reserva->monto_pagado;
              $saldo  = max(0, $total - $pagado);
              $pago   = $reserva->pagos->first();
            @endphp

            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="background:#F8FAFF;border:1.5px solid #BFDBFE;border-radius:10px;">
              <tr>
                <td style="padding:18px 22px;">

                  {{-- Fila: Costo total --}}
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                      <td style="padding:6px 0;border-bottom:1px solid #DBEAFE;">
                        <span style="font-size:12px;color:#64748B;font-weight:500;">Costo total del servicio</span>
                      </td>
                      <td style="text-align:right;padding:6px 0;border-bottom:1px solid #DBEAFE;">
                        <span style="font-size:13px;font-weight:700;color:#1E3A8A;font-family:'Courier New',monospace;">
                          S/ {{ number_format($total,2) }}
                        </span>
                      </td>
                    </tr>

                    {{-- Fila: Adelanto --}}
                    <tr>
                      <td style="padding:6px 0;border-bottom:1px solid #DBEAFE;">
                        <span style="font-size:12px;color:#64748B;font-weight:500;">Adelanto pagado</span>
                        @if($pago && $pago->metodoPago)
                          <span style="font-size:10px;color:#94A3B8;">&nbsp;&bull;&nbsp; {{ $pago->metodoPago->nombre }}</span>
                        @endif
                      </td>
                      <td style="text-align:right;padding:6px 0;border-bottom:1px solid #DBEAFE;">
                        <span style="font-size:13px;font-weight:700;color:#15803D;font-family:'Courier New',monospace;">
                          S/ {{ number_format($pagado,2) }}
                        </span>
                      </td>
                    </tr>

                    {{-- Fila: Saldo --}}
                    <tr>
                      <td style="padding:12px 0 4px 0;">
                        <span style="font-size:14px;font-weight:800;color:#1E3A8A;">Saldo pendiente</span>
                      </td>
                      <td style="text-align:right;padding:12px 0 4px 0;">
                        <span style="font-size:20px;font-weight:900;color:{{ $saldo > 0 ? '#DC2626' : '#15803D' }};font-family:'Courier New',monospace;">
                          S/ {{ number_format($saldo,2) }}
                        </span>
                      </td>
                    </tr>
                  </table>

                  {{-- Badge pagado completo --}}
                  @if($saldo <= 0)
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top:12px;">
                    <tr>
                      <td style="text-align:center;">
                        <span style="background:#15803D;color:#ffffff;padding:5px 20px;border-radius:20px;font-size:10px;font-weight:800;letter-spacing:2px;text-transform:uppercase;display:inline-block;">
                          Pagado Completamente
                        </span>
                      </td>
                    </tr>
                  </table>
                  @else
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                         style="margin-top:12px;background:#FEF2F2;border:1px solid #FECACA;border-radius:7px;">
                    <tr>
                      <td style="padding:9px 14px;">
                        <span style="font-size:11px;color:#B91C1C;font-weight:600;line-height:1.5;">
                          Por favor, recuerda que tienes un saldo pendiente de
                          <strong>S/ {{ number_format($saldo,2) }}</strong>.
                          Coord&iacute;nalo con nosotros antes del servicio.
                        </span>
                      </td>
                    </tr>
                  </table>
                  @endif

                </td>
              </tr>
            </table>

          </td>
        </tr>

        {{-- ══════════════════════════════════
             AVISO PDF ADJUNTO
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#ffffff;padding:0 36px 32px 36px;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="background:#1E40AF;border-radius:10px;">
              <tr>
                <td style="padding:0;">
                  {{-- Franja amarilla superior --}}
                  <div style="background:#FACC15;height:4px;border-radius:10px 10px 0 0;"></div>
                </td>
              </tr>
              <tr>
                <td style="padding:18px 24px 20px 24px;">
                  <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>

                      {{-- Icono documento --}}
                      <td style="vertical-align:top;width:44px;">
                        <table cellpadding="0" cellspacing="0" role="presentation"
                               style="background:rgba(250,204,21,.15);border-radius:8px;width:38px;height:38px;">
                          <tr>
                            <td style="text-align:center;vertical-align:middle;">
                              <span style="font-size:18px;color:#FACC15;font-weight:900;line-height:38px;">&#128196;</span>
                            </td>
                          </tr>
                        </table>
                      </td>

                      <td style="padding-left:14px;vertical-align:middle;">
                        <div style="font-size:13px;font-weight:700;color:#FACC15;margin-bottom:4px;">
                          PDF de Confirmaci&oacute;n Adjunto
                        </div>
                        <div style="font-size:11px;color:#93C5FD;line-height:1.6;">
                          El documento oficial con todos los detalles est&aacute; adjunto a este correo.
                          Gu&aacute;rdalo y pres&eacute;ntalo el d&iacute;a del servicio.
                        </div>
                        <div style="margin-top:6px;font-size:10px;color:#60A5FA;">
                          Para consultas menciona tu c&oacute;digo:
                          <strong style="color:#FACC15;font-family:'Courier New',monospace;letter-spacing:1px;">{{ $reserva->codigo_reserva }}</strong>
                        </div>
                      </td>

                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══════════════════════════════════
             FOOTER
        ══════════════════════════════════ --}}
        <tr>
          <td style="background:#1E3A8A;border-radius:0 0 14px 14px;padding:26px 36px 24px 36px;text-align:center;">

            {{-- Franja amarilla superior --}}
            <div style="background:#FACC15;height:4px;border-radius:4px;margin-bottom:20px;"></div>

            <div style="font-size:22px;font-weight:900;color:#FACC15;letter-spacing:6px;text-transform:uppercase;margin-bottom:6px;">
              Adventur
            </div>
            <div style="font-size:9px;color:#93C5FD;letter-spacing:1.5px;margin-bottom:3px;text-transform:uppercase;font-weight:600;">
              Horizonte Andino Company E.I.R.L.
            </div>
            <div style="font-size:9px;color:#60A5FA;margin-bottom:16px;">
              Jr. Amazonas 5 Esquinas Oficina &mdash; Cajamarca, Per&uacute;
            </div>

            {{-- Divisor --}}
            <div style="height:1px;background:rgba(255,255,255,.1);margin:0 auto 16px auto;max-width:300px;"></div>

            <div style="font-size:13px;color:#FACC15;font-weight:700;margin-bottom:8px;">
              Gracias por elegirnos. Nos vemos pronto.
            </div>
            <div style="font-size:9px;color:#3B82F6;font-style:italic;line-height:1.5;">
              Correo generado autom&aacute;ticamente el
              {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}.
              No responder si la consulta no est&aacute; relacionada con esta reserva.
            </div>

          </td>
        </tr>

        {{-- Espacio inferior --}}
        <tr>
          <td style="height:32px;"></td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>