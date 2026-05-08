{{-- =====================================================================
     ARCHIVO: resources/views/emails/confirmacion-reserva.blade.php
     USADO POR: MailService::enviarConfirmacion()
     ===================================================================== --}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Confirmación de Reserva — {{ $reserva->codigo_reserva }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:'Helvetica Neue',Arial,sans-serif;font-size:14px;color:#1a1a2e;">

  {{-- Wrapper --}}
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f3f4f6;padding:30px 0;">
    <tr>
      <td align="center">
        <table width="580" cellpadding="0" cellspacing="0" border="0" style="max-width:580px;width:100%;">

          {{-- ── CABECERA ─────────────────────────────────────────── --}}
          <tr>
            <td style="background:linear-gradient(135deg,#0f3460 0%,#16213e 100%);border-radius:10px 10px 0 0;padding:28px 36px;">
              <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="vertical-align:middle;">
                    <div style="font-size:26px;font-weight:900;color:#e2b96f;letter-spacing:5px;text-transform:uppercase;">
                      Adventur
                    </div>
                    <div style="font-size:11px;color:#a8c8e8;letter-spacing:2px;margin-top:3px;">
                      Turismo &amp; Experiencias
                    </div>
                  </td>
                  <td style="text-align:right;vertical-align:middle;">
                    <div style="font-size:11px;color:#a8c8e8;letter-spacing:1px;text-transform:uppercase;">
                      Reserva
                    </div>
                    <div style="font-size:20px;font-weight:700;color:#e2b96f;letter-spacing:2px;">
                      {{ $reserva->codigo_reserva }}
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          {{-- ── BANNER ───────────────────────────────────────────── --}}
          <tr>
            <td style="background:#e2b96f;padding:10px;text-align:center;">
              <span style="font-size:13px;font-weight:700;color:#0f3460;letter-spacing:3px;text-transform:uppercase;">
                ✓ &nbsp; Tu reserva está registrada
              </span>
            </td>
          </tr>

          {{-- ── CUERPO ───────────────────────────────────────────── --}}
          <tr>
            <td style="background:#ffffff;padding:32px 36px;">

              {{-- Saludo --}}
              <p style="font-size:15px;color:#0f3460;margin:0 0 20px 0;">
                Hola, <strong>{{ $reserva->cliente->nombre_completo }}</strong> 👋
              </p>
              <p style="color:#374151;line-height:1.6;margin:0 0 24px 0;">
                Te confirmamos que tu reserva ha sido registrada exitosamente.
                A continuación el resumen de tu viaje. El PDF con los detalles
                completos está adjunto a este correo.
              </p>

              {{-- ── CARD TOUR ──────────────────────────────────── --}}
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7ff;border-left:4px solid #0f3460;border-radius:6px;margin-bottom:20px;">
                <tr>
                  <td style="padding:16px 20px;">
                    <div style="font-size:17px;font-weight:700;color:#0f3460;margin-bottom:12px;">
                      {{ $reserva->nombre_tour }}
                    </div>
                    <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="padding-right:12px;vertical-align:top;">
                          <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Fecha</div>
                          <div style="font-size:13px;font-weight:700;color:#0f3460;">
                            {{ $reserva->fecha_tour
                                ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y')
                                : '—' }}
                          </div>
                        </td>
                        @if($reserva->hora_salida)
                        <td style="padding-right:12px;vertical-align:top;">
                          <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Hora salida</div>
                          <div style="font-size:13px;font-weight:700;color:#0f3460;">
                            {{ substr($reserva->hora_salida, 0, 5) }}
                          </div>
                        </td>
                        @endif
                        @if($reserva->ciudad_destino)
                        <td style="padding-right:12px;vertical-align:top;">
                          <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Destino</div>
                          <div style="font-size:13px;font-weight:700;color:#0f3460;">
                            {{ $reserva->ciudad_destino }}
                          </div>
                        </td>
                        @endif
                        <td style="vertical-align:top;">
                          <div style="font-size:10px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;">Pasajeros</div>
                          <div style="font-size:13px;font-weight:700;color:#0f3460;">
                            {{ $reserva->cantidad_adultos }} adulto(s)
                            @if($reserva->cantidad_ninos > 0)
                              + {{ $reserva->cantidad_ninos }} niño(s)
                            @endif
                          </div>
                        </td>
                      </tr>
                    </table>

                    @if($reserva->punto_encuentro)
                    <div style="margin-top:10px;font-size:12px;color:#374151;">
                      📍 <strong>Punto de encuentro:</strong> {{ $reserva->punto_encuentro }}
                      @if($reserva->hora_recojo)
                        &nbsp;—&nbsp; <strong>Recojo:</strong> {{ substr($reserva->hora_recojo, 0, 5) }}
                      @endif
                    </div>
                    @endif
                  </td>
                </tr>
              </table>

              {{-- ── RESUMEN DE PAGO ────────────────────────────── --}}
              @php
                $total  = (float) $reserva->precio_total;
                $pagado = (float) $reserva->monto_pagado;
                $saldo  = max(0, $total - $pagado);
              @endphp
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8faff;border:1px solid #dbeafe;border-radius:6px;margin-bottom:20px;">
                <tr>
                  <td style="padding:16px 20px;">
                    <div style="font-size:11px;font-weight:700;color:#0f3460;text-transform:uppercase;letter-spacing:2px;margin-bottom:12px;border-bottom:1px solid #dbeafe;padding-bottom:8px;">
                      Resumen de Pago
                    </div>
                    <table width="100%" cellpadding="4" cellspacing="0">
                      <tr>
                        <td style="color:#6b7280;font-size:13px;">Total del servicio</td>
                        <td style="text-align:right;font-weight:600;font-size:13px;color:#1a1a2e;">
                          S/ {{ number_format($total, 2) }}
                        </td>
                      </tr>
                      <tr>
                        <td style="color:#6b7280;font-size:13px;">Monto pagado</td>
                        <td style="text-align:right;font-weight:600;font-size:13px;color:#059669;">
                          S/ {{ number_format($pagado, 2) }}
                        </td>
                      </tr>
                      <tr style="border-top:1px solid #dbeafe;">
                        <td style="font-weight:700;font-size:14px;color:#0f3460;padding-top:8px;">
                          Saldo pendiente
                        </td>
                        <td style="text-align:right;font-weight:700;font-size:16px;padding-top:8px;color:{{ $saldo > 0 ? '#dc2626' : '#059669' }};">
                          S/ {{ number_format($saldo, 2) }}
                        </td>
                      </tr>
                    </table>
                    @if($saldo <= 0)
                    <div style="margin-top:10px;text-align:center;">
                      <span style="background:#059669;color:#fff;padding:4px 16px;border-radius:12px;font-size:11px;font-weight:700;">
                        ✓ PAGADO COMPLETAMENTE
                      </span>
                    </div>
                    @endif
                  </td>
                </tr>
              </table>

              {{-- ── POLÍTICAS (si existen) ──────────────────────── --}}
              @if($reserva->politica_descripcion)
              <table width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:4px;margin-bottom:20px;">
                <tr>
                  <td style="padding:12px 16px;">
                    <div style="font-size:11px;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">
                      Políticas y Condiciones
                    </div>
                    <div style="font-size:12px;color:#78350f;line-height:1.6;">
                      {!! nl2br(e($reserva->politica_descripcion)) !!}
                    </div>
                  </td>
                </tr>
              </table>
              @endif

              {{-- Mensaje adjunto PDF --}}
              <p style="color:#374151;font-size:13px;line-height:1.6;margin:0 0 8px 0;">
                📎 Adjunto encontrarás el <strong>PDF de confirmación</strong> con todos los detalles.
                Por favor guárdalo y preséntalo el día del tour.
              </p>
              <p style="color:#374151;font-size:13px;line-height:1.6;margin:0;">
                Para cualquier consulta, responde este correo o contáctanos directamente
                con tu código de reserva: <strong>{{ $reserva->codigo_reserva }}</strong>.
              </p>

            </td>
          </tr>

          {{-- ── FOOTER ───────────────────────────────────────────── --}}
          <tr>
            <td style="background:#0f3460;border-radius:0 0 10px 10px;padding:20px 36px;text-align:center;">
              <div style="font-size:16px;font-weight:700;color:#e2b96f;letter-spacing:4px;text-transform:uppercase;margin-bottom:4px;">
                Adventur
              </div>
              <div style="font-size:11px;color:#a8c8e8;">
                ¡Gracias por elegirnos! Nos vemos pronto 🌿
              </div>
              <div style="font-size:10px;color:#4a6fa5;margin-top:8px;">
                Este correo fue generado automáticamente. No responder a este correo
                si la consulta no está relacionada con la reserva indicada.
              </div>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>