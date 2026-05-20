<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación al sistema de reservas</title>
</head>
<body style="margin:0;padding:0;background:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f4f8;padding:40px 20px;">
  <tr><td align="center">
    <table width="580" cellpadding="0" cellspacing="0" style="max-width:580px;width:100%;">

      {{-- HEADER --}}
      <tr>
        <td style="background:linear-gradient(135deg,#1b3a6b 0%,#22478a 100%);border-radius:14px 14px 0 0;padding:0;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="padding:32px 40px 0;text-align:center;">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Adventur"
                     width="160"
                     style="display:block;margin:0 auto;max-width:160px;height:auto;">
              </td>
            </tr>
            <tr>
              <td style="padding:20px 40px 32px;text-align:center;">
                <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0;line-height:1.3;">
                  Has sido invitado al sistema
                </h1>
                <p style="color:#93c5fd;font-size:13px;margin:8px 0 0;">
                  Configura tu cuenta en menos de 2 minutos
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

      {{-- BODY --}}
      <tr>
        <td style="background:#ffffff;padding:36px 40px;">

          <p style="font-size:15px;color:#475569;line-height:1.8;margin:0 0 16px;">
            Hola,
          </p>
          <p style="font-size:15px;color:#475569;line-height:1.8;margin:0 0 24px;">
            <strong style="color:#1e293b;">{{ $invitadoPor }}</strong>
            te ha dado acceso al sistema interno de gestión de reservas de
            <strong style="color:#1b3a6b;">Adventur</strong>.
          </p>

          {{-- ROL CARD --}}
          <table width="100%" cellpadding="0" cellspacing="0"
                 style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;margin-bottom:28px;">
            <tr>
              <td style="padding:18px 22px;">
                <table cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="padding-right:16px;">
                      <div style="width:48px;height:48px;background:linear-gradient(135deg,#1b3a6b,#2a56a8);
                                  border-radius:12px;display:inline-flex;align-items:center;justify-content:center;">
                        <span style="font-size:22px;line-height:48px;display:block;text-align:center;width:48px;">
                          @if($invitacion->rol === 'superadmin')&#11088;
                          @elseif($invitacion->rol === 'administrador')&#128737;
                          @elseif($invitacion->rol === 'ventas')&#128188;
                          @else&#128295;@endif
                        </span>
                      </div>
                    </td>
                    <td>
                      <div style="font-size:11px;color:#94a3b8;font-weight:700;
                                  text-transform:uppercase;letter-spacing:.08em;">
                        Rol asignado
                      </div>
                      <div style="font-size:16px;font-weight:800;color:#1b3a6b;margin-top:3px;">
                        {{ ucfirst($invitacion->rol) }}
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <p style="font-size:15px;color:#475569;line-height:1.8;margin:0 0 24px;">
            Haz clic en el botón para crear tu contraseña y acceder al sistema:
          </p>

          {{-- BOTON --}}
          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
            <tr>
              <td align="center">
                <a href="{{ $link }}"
                   style="display:inline-block;padding:16px 44px;
                          background:linear-gradient(135deg,#f5c842 0%,#d4a800 100%);
                          color:#1b3a6b;text-decoration:none;border-radius:10px;
                          font-weight:800;font-size:15px;letter-spacing:.02em;
                          box-shadow:0 4px 14px rgba(212,168,0,.4);">
                  Crear mi cuenta &rarr;
                </a>
              </td>
            </tr>
          </table>

          {{-- WARNING --}}
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="background:#fffbeb;border:1.5px solid #fde68a;
                         border-left:4px solid #f59e0b;border-radius:8px;
                         padding:14px 18px;">
                <p style="font-size:13px;color:#92400e;line-height:1.7;margin:0;">
                  <strong>&#9200; Enlace válido por 3 días</strong> y de uso único.
                  Si no esperabas este correo puedes ignorarlo con seguridad.
                </p>
              </td>
            </tr>
          </table>

        </td>
      </tr>

      {{-- FOOTER --}}
      <tr>
        <td style="background:#f8fafc;border:1px solid #e2e8f0;border-top:none;
                   border-radius:0 0 14px 14px;padding:20px 40px;">
          <p style="font-size:12px;color:#94a3b8;margin:0 0 8px;">
            Si el botón no funciona copia este enlace en tu navegador:
          </p>
          <a href="{{ $link }}"
             style="font-size:12px;color:#2a56a8;word-break:break-all;">
            {{ $link }}
          </a>
          <p style="font-size:11px;color:#cbd5e1;margin:16px 0 0;
                    font-weight:700;text-transform:uppercase;letter-spacing:.1em;">
            &copy; Adventur &mdash; Sistema de Reservas
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>