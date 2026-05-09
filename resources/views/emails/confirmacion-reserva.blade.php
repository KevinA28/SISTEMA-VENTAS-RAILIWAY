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
<body style="margin:0;padding:0;background:#eef2f7;font-family:'Helvetica Neue',Arial,sans-serif;font-size:14px;color:#1a1a2e;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#eef2f7;padding:36px 0;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">

        {{-- ══ CABECERA ══ --}}
        <tr>
          <td style="background:#0f3460;border-radius:12px 12px 0 0;padding:28px 40px 22px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="vertical-align:middle;">
                  <div style="font-size:30px;font-weight:900;color:#e2b96f;letter-spacing:6px;text-transform:uppercase;line-height:1;">
                    Adventur
                  </div>
                  <div style="font-size:10px;color:#a8c8e8;letter-spacing:2.5px;margin-top:5px;text-transform:uppercase;">
                    Turismo &amp; Experiencias
                  </div>
                  <div style="font-size:9px;color:#6b8dbe;margin-top:5px;">
                    HORIZONTE ANDINO COMPANY E.I.R.L. &nbsp;|&nbsp; Cajamarca, Perú
                  </div>
                </td>
                <td style="text-align:right;vertical-align:middle;">
                  <div style="font-size:9px;color:#a8c8e8;letter-spacing:2px;text-transform:uppercase;margin-bottom:4px;">
                    Código de Reserva
                  </div>
                  <div style="font-size:22px;font-weight:800;color:#e2b96f;letter-spacing:2px;line-height:1;">
                    {{ $reserva->codigo_reserva }}
                  </div>
                  <div style="font-size:9px;color:#6b8dbe;margin-top:6px;">
                    {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══ BANNER ══ --}}
        <tr>
          <td style="background:#e2b96f;padding:9px 40px;text-align:center;">
            <span style="font-size:10px;font-weight:800;color:#0f3460;letter-spacing:4px;text-transform:uppercase;">
              ✓ &nbsp; Reserva Registrada Exitosamente &nbsp; ✓
            </span>
          </td>
        </tr>

        {{-- ══ SALUDO ══ --}}
        <tr>
          <td style="background:#ffffff;padding:28px 40px 16px 40px;">
            <p style="font-size:17px;color:#0f3460;margin:0 0 8px 0;font-weight:700;">
              Hola, {{ $reserva->cliente->nombre_completo }} 👋
            </p>
            <p style="color:#4b5563;line-height:1.7;margin:0;font-size:13px;">
              Tu reserva ha sido registrada exitosamente. A continuación encontrarás el resumen.
              El <strong>PDF con todos los detalles oficiales</strong> está adjunto a este correo.
            </p>
          </td>
        </tr>

        {{-- ══ CARD VIAJE ══ --}}
        <tr>
          <td style="background:#ffffff;padding:12px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f0f7ff;border-left:5px solid #0f3460;border-radius:6px;">
              <tr>
                <td style="padding:18px 20px;">
                  <div style="font-size:15px;font-weight:800;color:#0f3460;margin-bottom:12px;padding-bottom:10px;border-bottom:1px solid #dbeafe;">
                    🗺 {{ $reserva->nombre_tour }}
                  </div>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      @if($reserva->fecha_arribo)
                      <td style="vertical-align:top;padding-right:14px;">
                        <div style="font-size:9px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Arribo</div>
                        <div style="font-size:12px;font-weight:700;color:#0f3460;">{{ \Carbon\Carbon::parse($reserva->fecha_arribo)->format('d/m/Y') }}</div>
                      </td>
                      @endif
                      @if($reserva->fecha_retorno)
                      <td style="vertical-align:top;padding-right:14px;">
                        <div style="font-size:9px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Retorno</div>
                        <div style="font-size:12px;font-weight:700;color:#0f3460;">{{ \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') }}</div>
                      </td>
                      @endif
                      @if($reserva->ciudad_destino)
                      <td style="vertical-align:top;padding-right:14px;">
                        <div style="font-size:9px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Destino</div>
                        <div style="font-size:12px;font-weight:700;color:#0f3460;">{{ $reserva->ciudad_destino }}</div>
                      </td>
                      @endif
                      @if($reserva->dias_viaje)
                      <td style="vertical-align:top;padding-right:14px;">
                        <div style="font-size:9px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Días</div>
                        <div style="font-size:12px;font-weight:700;color:#0f3460;">{{ $reserva->dias_viaje }}</div>
                      </td>
                      @endif
                      <td style="vertical-align:top;">
                        <div style="font-size:9px;color:#6b7280;text-transform:uppercase;letter-spacing:1px;margin-bottom:2px;">Pasajeros</div>
                        <div style="font-size:12px;font-weight:700;color:#0f3460;">
                          {{ $reserva->cantidad_adultos }} adulto(s)
                          @if($reserva->cantidad_ninos > 0) + {{ $reserva->cantidad_ninos }} niño(s) @endif
                        </div>
                      </td>
                    </tr>
                  </table>
                  @if($reserva->nombre_hotel)
                  <div style="margin-top:10px;padding-top:8px;border-top:1px solid #dbeafe;font-size:11px;color:#374151;">
                    🏨 <strong>{{ $reserva->nombre_hotel }}</strong>
                    @if($reserva->tipo_habitacion) &nbsp;·&nbsp; {{ $reserva->tipo_habitacion }} @endif
                    @if($reserva->plan_alimentacion)
                      @php $planes=['RO'=>'Solo hab.','BB'=>'Con desayuno','HB'=>'Desayuno y cena','FB'=>'Pensión completa','AI'=>'Todo incluido']; @endphp
                      &nbsp;·&nbsp; {{ $planes[$reserva->plan_alimentacion] ?? $reserva->plan_alimentacion }}
                    @endif
                  </div>
                  @endif
                  @if($reserva->tipo_transporte)
                  <div style="margin-top:5px;font-size:11px;color:#374151;">
                    ✈ <strong>{{ ucfirst($reserva->tipo_transporte) }}</strong>
                    @if($reserva->empresa_transporte) — {{ $reserva->empresa_transporte }} @endif
                    @if($reserva->aerolinea) — {{ $reserva->aerolinea }} @if($reserva->numero_vuelo) ({{ $reserva->numero_vuelo }}) @endif @endif
                  </div>
                  @endif
                  @if($reserva->punto_encuentro)
                  <div style="margin-top:5px;font-size:11px;color:#374151;">
                    📍 <strong>Encuentro:</strong> {{ $reserva->punto_encuentro }}
                    @if($reserva->hora_recojo) &nbsp;·&nbsp; Recojo: {{ substr($reserva->hora_recojo,0,5) }} @endif
                  </div>
                  @endif
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══ TITULAR ══ --}}
        <tr>
          <td style="background:#ffffff;padding:12px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f8faff;border:1px solid #dbeafe;border-radius:6px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:9px;font-weight:800;color:#0f3460;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;padding-bottom:7px;border-bottom:1px solid #dbeafe;">
                    👤 Titular de la Reserva
                  </div>
                  <table width="100%" cellpadding="3" cellspacing="0">
                    <tr>
                      <td style="color:#6b7280;font-size:11px;width:130px;">Nombre completo</td>
                      <td style="font-weight:700;color:#0f3460;font-size:12px;">{{ $reserva->cliente->nombre_completo }}</td>
                    </tr>
                    @if($reserva->cliente->numero_documento)
                    <tr>
                      <td style="color:#6b7280;font-size:11px;">{{ strtoupper($reserva->cliente->tipo_documento ?? 'DNI') }}</td>
                      <td style="color:#374151;font-size:12px;">{{ $reserva->cliente->numero_documento }}</td>
                    </tr>
                    @endif
                    <tr>
                      <td style="color:#6b7280;font-size:11px;">Celular</td>
                      <td style="color:#374151;font-size:12px;">{{ $reserva->cliente->telefono ?? '—' }}</td>
                    </tr>
                    @if($reserva->cliente->email)
                    <tr>
                      <td style="color:#6b7280;font-size:11px;">Correo</td>
                      <td style="color:#374151;font-size:12px;">{{ $reserva->cliente->email }}</td>
                    </tr>
                    @endif
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══ RESUMEN PAGO ══ --}}
        <tr>
          <td style="background:#ffffff;padding:12px 40px 24px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="background:#f8faff;border:1px solid #dbeafe;border-radius:6px;">
              <tr>
                <td style="padding:14px 18px;">
                  <div style="font-size:9px;font-weight:800;color:#0f3460;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;padding-bottom:7px;border-bottom:1px solid #dbeafe;">
                    💰 Resumen de Pago
                  </div>
                  @php
                    $total  = (float) $reserva->precio_total;
                    $pagado = (float) $reserva->monto_pagado;
                    $saldo  = max(0, $total - $pagado);
                    $pago   = $reserva->pagos->first();
                  @endphp
                  <table width="100%" cellpadding="4" cellspacing="0">
                    <tr>
                      <td style="color:#6b7280;font-size:12px;">Costo total</td>
                      <td style="text-align:right;font-weight:600;font-size:13px;color:#1a1a2e;">S/ {{ number_format($total,2) }}</td>
                    </tr>
                    <tr>
                      <td style="color:#6b7280;font-size:12px;">Adelanto pagado</td>
                      <td style="text-align:right;font-weight:600;font-size:13px;color:#059669;">S/ {{ number_format($pagado,2) }}</td>
                    </tr>
                    @if($pago && $pago->metodoPago)
                    <tr>
                      <td style="color:#6b7280;font-size:12px;">Medio de pago</td>
                      <td style="text-align:right;font-size:12px;color:#374151;">{{ $pago->metodoPago->nombre }}</td>
                    </tr>
                    @endif
                    <tr>
                      <td colspan="2"><div style="border-top:2px solid #dbeafe;margin:6px 0;"></div></td>
                    </tr>
                    <tr>
                      <td style="font-weight:800;font-size:14px;color:#0f3460;">Saldo pendiente</td>
                      <td style="text-align:right;font-weight:800;font-size:18px;color:{{ $saldo > 0 ? '#dc2626' : '#059669' }};">
                        S/ {{ number_format($saldo,2) }}
                      </td>
                    </tr>
                  </table>
                  @if($saldo <= 0)
                  <div style="margin-top:10px;text-align:center;">
                    <span style="background:#059669;color:#fff;padding:4px 18px;border-radius:12px;font-size:10px;font-weight:800;letter-spacing:1px;">
                      ✓ PAGADO COMPLETAMENTE
                    </span>
                  </div>
                  @endif
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══ AVISO PDF ══ --}}
        <tr>
          <td style="background:#ffffff;padding:0 40px 28px 40px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f3460;border-radius:8px;">
              <tr>
                <td style="padding:18px 22px;">
                  <div style="font-size:13px;font-weight:700;color:#e2b96f;margin-bottom:5px;">
                    📎 PDF de Confirmación Adjunto
                  </div>
                  <div style="font-size:11px;color:#a8c8e8;line-height:1.6;">
                    El documento oficial con todos los detalles de tu reserva está adjunto.
                    Guárdalo y preséntalo el día del servicio.
                  </div>
                  <div style="margin-top:8px;font-size:10px;color:#6b8dbe;">
                    Consultas: menciona tu código <strong style="color:#e2b96f;">{{ $reserva->codigo_reserva }}</strong>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ══ FOOTER ══ --}}
        <tr>
          <td style="background:#0f3460;border-top:3px solid #e2b96f;border-radius:0 0 12px 12px;padding:22px 40px;text-align:center;">
            <div style="font-size:20px;font-weight:900;color:#e2b96f;letter-spacing:5px;text-transform:uppercase;margin-bottom:4px;">
              Adventur
            </div>
            <div style="font-size:9px;color:#a8c8e8;letter-spacing:1px;margin-bottom:3px;">
              HORIZONTE ANDINO COMPANY E.I.R.L.
            </div>
            <div style="font-size:9px;color:#6b8dbe;">
              Jr. Amazonas 5 Esquinas Oficina – Cajamarca, Perú
            </div>
            <div style="margin-top:12px;font-size:12px;color:#e2b96f;font-weight:700;">
              ¡Gracias por elegirnos! Nos vemos pronto 🌿
            </div>
            <div style="margin-top:6px;font-size:9px;color:#4a6fa5;font-style:italic;">
              Correo generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}.
              No responder si la consulta no está relacionada con esta reserva.
            </div>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>