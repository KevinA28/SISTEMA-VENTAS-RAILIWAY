{{-- =====================================================================
     ARCHIVO: resources/views/pdf/confirmacion-reserva.blade.php
     LOGO   : guardar en public/images/logo.png
     ===================================================================== --}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'DejaVu Sans',sans-serif; font-size:8pt; color:#1a2540; background:#fff; }

  .header { background:#1b3a6b; padding:0; }
  .header-top { padding:12px 26px 10px 26px; display:table; width:100%; }
  .header-left { display:table-cell; vertical-align:middle; width:55%; }
  .header-right { display:table-cell; vertical-align:middle; text-align:right; width:45%; }

  .brand-tagline { font-size:6.5pt; color:#a8c8e8; letter-spacing:2px; margin-top:3px; }
  .brand-datos { margin-top:5px; font-size:6pt; color:#a8c8e8; line-height:1.5; }
  .brand-datos span { color:#f5c842; font-weight:700; }

  .doc-title { font-size:6.5pt; color:#a8c8e8; letter-spacing:1.5px; text-transform:uppercase; margin-bottom:2px; }
  .doc-codigo { font-size:14pt; font-weight:700; color:#f5c842; letter-spacing:2px; line-height:1; }
  .doc-meta { font-size:6pt; color:#a8c8e8; margin-top:3px; line-height:1.5; }

  .banner { background:#f5c842; color:#1b3a6b; text-align:center; padding:5px;
            font-size:7.5pt; font-weight:700; letter-spacing:3px; text-transform:uppercase; }

  .contenido { padding:11px 26px 8px 26px; }
  .seccion { margin-bottom:9px; }

  .sec-titulo { font-size:6.5pt; font-weight:700; color:#fff; background:#1b3a6b;
                text-transform:uppercase; letter-spacing:2px; padding:4px 9px;
                border-left:4px solid #f5c842; margin-bottom:6px; }
  .sec-sub { font-size:6pt; font-weight:700; color:#1b3a6b; text-transform:uppercase;
             letter-spacing:1px; border-bottom:1px solid #c8dff0;
             padding-bottom:2px; margin:6px 0 4px 0; }

  .caja-info { background:#eaf3fb; border-left:3px solid #4a90c4; border-radius:3px; padding:7px 11px; }

  .dos-col { display:table; width:100%; }
  .col-l   { display:table-cell; width:50%; vertical-align:top; padding-right:6px; }
  .col-r   { display:table-cell; width:50%; vertical-align:top; padding-left:6px; }

  .t-datos { width:100%; border-collapse:collapse; }
  .t-datos td { padding:2px 4px; vertical-align:top; font-size:7.5pt; }
  .lbl { color:#6b7280; font-size:6pt; font-weight:700; text-transform:uppercase;
         letter-spacing:0.4px; width:95px; white-space:nowrap; }
  .val { color:#1a2540; }
  .val-strong { color:#1b3a6b; font-weight:700; font-size:8pt; }

  /* Fechas */
  .fechas-grid { display:table; width:100%; margin-top:6px; }
  .fecha-cell { display:table-cell; width:50%; vertical-align:top; padding:5px 8px;
                background:#fff; border:1px solid #c8dff0; border-radius:3px; }
  .fecha-sep  { display:table-cell; width:6px; }
  .fecha-lbl  { font-size:5.5pt; font-weight:700; color:#4a90c4; text-transform:uppercase;
                letter-spacing:0.8px; margin-bottom:2px; }
  .fecha-val  { font-size:9pt; font-weight:700; color:#1b3a6b; line-height:1.1; }
  .fecha-hora { font-size:6.5pt; color:#4a90c4; margin-top:1px; }

  /* Habitación */
  .hab-card { background:#fff; border:1px solid #c8dff0; border-left:3px solid #4a90c4;
              border-radius:3px; padding:5px 8px; margin-bottom:4px; }
  .hab-nombre { font-size:7.5pt; font-weight:700; color:#1b3a6b; margin-bottom:2px; }
  .hab-fila   { font-size:6.5pt; color:#374151; line-height:1.6; }
  .hab-lbl    { color:#6b7280; font-weight:700; text-transform:uppercase; font-size:5.5pt; }
  .star-on  { color:#f5c842; }
  .star-off { color:#c8dff0; }

  /* Pasajeros */
  .t-pax { width:100%; border-collapse:collapse; font-size:6.5pt; }
  .t-pax thead tr { background:#1b3a6b; color:#fff; }
  .t-pax thead td { padding:4px 5px; font-weight:700; text-transform:uppercase;
                    letter-spacing:0.3px; font-size:6pt; }
  .t-pax tbody tr.fila-tit { background:#eaf3fb; }
  .t-pax tbody tr:not(.fila-tit):nth-child(even) { background:#f8faff; }
  .t-pax tbody tr:not(.fila-tit):nth-child(odd)  { background:#fff; }
  .t-pax tbody td { padding:3px 5px; border-bottom:0.5px solid #e5e7eb;
                    color:#374151; vertical-align:top; }

  .badge { display:inline-block; padding:1px 4px; border-radius:5px;
           font-size:5.5pt; font-weight:700; text-transform:uppercase; }
  .b-tit   { background:#f5c842; color:#1b3a6b; }
  .b-adt   { background:#c8dff0; color:#1b3a6b; }
  .b-nio   { background:#fef3c7; color:#92400e; }
  .b-si    { background:#fee2e2; color:#991b1b; }
  .b-no    { background:#d1fae5; color:#065f46; }
  .b-seg   { background:#dcfce7; color:#166534; }
  .b-noseg { background:#f3f4f6; color:#6b7280; }

  /* Emergencia */
  .caja-emerg { background:#fffbea; border:1.5px solid #f5c842; border-left:4px solid #f5c842;
                border-radius:4px; padding:6px 11px; margin-top:5px; }
  .emerg-tit  { font-size:6pt; font-weight:700; color:#92400e; text-transform:uppercase;
                letter-spacing:1px; margin-bottom:4px; }
  .emerg-grid { display:table; width:100%; }
  .emerg-col  { display:table-cell; vertical-align:top; width:33%; }
  .emerg-lbl  { font-size:5.5pt; color:#92400e; font-weight:700; text-transform:uppercase; }
  .emerg-val  { font-size:7.5pt; color:#1a2540; font-weight:700; }

  /* Pago */
  .caja-pago { background:#eaf3fb; border:1px solid #c8dff0; border-radius:4px; padding:9px 11px; }
  .p-row { display:table; width:100%; margin-bottom:4px; }
  .p-lbl { display:table-cell; color:#6b7280; font-size:6.5pt; text-transform:uppercase; letter-spacing:0.3px; }
  .p-val { display:table-cell; text-align:right; font-weight:700; font-size:8pt; color:#1a2540; }
  .p-div { border-top:1px solid #c8dff0; margin:4px 0; }
  .p-lbl-tot { display:table-cell; color:#1b3a6b; font-size:7.5pt; font-weight:700; text-transform:uppercase; }
  .p-val-tot { display:table-cell; text-align:right; font-size:10pt; font-weight:700; color:#1b3a6b; }
  .c-green { color:#059669; }
  .c-red   { color:#dc2626; }

  .footer { border-top:2px solid #f5c842; padding:7px 26px 10px 26px; text-align:center;
            color:#6b7280; font-size:6pt; margin-top:8px; }
  .footer-brand { font-size:8pt; font-weight:700; color:#1b3a6b; letter-spacing:3px;
                  text-transform:uppercase; margin-bottom:2px; }
  .footer-aviso { font-size:5.5pt; color:#9ca3af; margin-top:2px; font-style:italic; }

  .caja-pol { background:#fffbea; border:1px solid #fde68a; border-left:4px solid #f5c842;
              border-radius:4px; padding:12px 16px; font-size:7.5pt; color:#78350f; line-height:1.65; }
  .page-break { page-break-before:always; }
</style>
</head>
<body>

@php
/* ═══════════════════════════════════════════════════════
   HELPERS PHP — se calculan una sola vez aquí arriba
═══════════════════════════════════════════════════════ */

// ── Logo ──────────────────────────────────────────────
$logoPath = public_path('images/logo.png');
$logoB64  = '';
if (file_exists($logoPath)) {
    $logoB64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
}

// ── Estrellas del establecimiento ─────────────────────
// Formato guardado: "hotel_2", "hotel_3", etc.
$tipoEstab    = strtolower($reserva->tipo_establecimiento ?? '');
$numEstrellas = 0;
if (preg_match('/hotel_(\d)/', $tipoEstab, $mE)) {
    $numEstrellas = min((int)$mE[1], 5);
}
// Etiqueta legible sin el código
$etiquetaEstab = match($tipoEstab) {
    'hostal'   => 'Hostal',
    'apart'    => 'Apart-hotel',
    'resort'   => 'Resort',
    'ecolodge' => 'Ecolodge',
    'albergue' => 'Albergue / Hostel',
    default    => ($numEstrellas > 0 ? 'Hotel ' . $numEstrellas . ' estrellas' : ($reserva->tipo_establecimiento ?? '')),
};

// ── Parsear habitaciones ──────────────────────────────
// Campo tipo_habitacion guarda: "DBL — Doble matrimonial | King Bed | Con desayuno / SGL — Simple"
// Separador entre habitaciones: " / "
// Separador entre campos: " | "
$planesMap = [
    'RO' => 'Solo habitación',
    'BB' => 'Aloj. + Desayuno',
    'HB' => 'Desayuno y cena',
    'FB' => 'Pensión completa',
    'AI' => 'Todo incluido',
    // también puede venir el texto completo del ALIM_LABELS del JS:
    'Solo habitación'   => 'Solo habitación',
    'Con desayuno'      => 'Con desayuno',
    'Desayuno y cena'   => 'Desayuno y cena',
    'Pensión completa'  => 'Pensión completa',
    'Todo incluido'     => 'Todo incluido',
];

$habitaciones = [];
$rawHabs = trim($reserva->tipo_habitacion ?? '');
if ($rawHabs) {
    foreach (explode(' / ', $rawHabs) as $parte) {
        $campos = array_map('trim', explode(' | ', $parte));
        $habitaciones[] = [
            'nombre'      => $campos[0] ?? '—',
            'cama'        => $campos[1] ?? null,
            'alimentacion'=> isset($campos[2]) ? ($planesMap[$campos[2]] ?? $campos[2]) : null,
        ];
    }
}

// ── Pasajeros ─────────────────────────────────────────
$titular     = $reserva->pasajeros->firstWhere('es_titular', true);
$adicionales = $reserva->pasajeros->where('es_titular', false)->values();

$saludTit       = $titular?->salud ?? null;
$tieneSaludTit  = $saludTit && ($saludTit->alergias || $saludTit->medicamentos
                    || $saludTit->restricciones_alimentarias || $saludTit->condiciones_medicas);
$seguroTit      = $saludTit?->seguro_salud ?? null;
$edadTit        = $reserva->cliente->fecha_nacimiento
                    ? \Carbon\Carbon::parse($reserva->cliente->fecha_nacimiento)->age
                    : null;

// ── Pago ──────────────────────────────────────────────
$total  = (float)$reserva->precio_total;
$pagado = (float)$reserva->monto_pagado;
$saldo  = max(0, $total - $pagado);
$pago   = $reserva->pagos->first();

$tiposLabel = [
    'adulto'      => 'Adulto',
    'adolescente' => 'Adol.',
    'nino'        => 'Niño',
    'niño'        => 'Niño',
    'bebe'        => 'Bebé',
    'adulto_mayor'=> 'Mayor',
];
@endphp

{{-- ════════ ENCABEZADO ════════ --}}
<div class="header">
  <div class="header-top">
    <div class="header-left">
      {{-- Logo si existe, sino texto de marca --}}
      @if($logoB64)
        <img src="{{ $logoB64 }}" alt="Adventur" style="max-height:52px;max-width:180px;">
      @else
        <div style="font-size:22pt;font-weight:700;color:#f5c842;letter-spacing:5px;text-transform:uppercase;line-height:1">Adventur</div>
      @endif
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
        <strong style="color:#f5c842">Emisión:</strong>
        {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}<br>
        @if($reserva->fecha_retorno)
          <strong style="color:#f5c842">Retorno:</strong>
          {{ \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') }}
        @endif
      </div>
    </div>
  </div>
</div>
<div class="banner">&#10003; &nbsp; Reserva Registrada Exitosamente &nbsp; &#10003;</div>

<div class="contenido">

  {{-- ══ 1. DETALLE DEL VIAJE ══ --}}
  <div class="seccion">
    <div class="sec-titulo">1. Detalle del Viaje</div>
    <div class="caja-info">

      <table class="t-datos">
        <tr>
          <td class="lbl">Paquete / Tour</td>
          <td class="val val-strong" colspan="3">{{ $reserva->nombre_tour }}</td>
        </tr>
        <tr>
          <td class="lbl">Destino</td>
          <td class="val">
            {{ $reserva->ciudad_destino ?? '—' }}
            {{ $reserva->departamento_destino ? ', '.$reserva->departamento_destino : '' }}
          </td>
          <td class="lbl">Días</td>
          <td class="val">{{ $reserva->dias_viaje ? $reserva->dias_viaje.' día(s)' : '—' }}</td>
        </tr>
        @if($reserva->punto_encuentro)
        <tr>
          <td class="lbl">Punto encuentro</td>
          <td class="val" colspan="3">
            {{ $reserva->punto_encuentro }}
            {{ $reserva->hora_recojo ? ' — Recojo: '.substr($reserva->hora_recojo,0,5) : '' }}
          </td>
        </tr>
        @endif
      </table>

      {{-- Fechas --}}
      <div class="fechas-grid">
        <div class="fecha-cell">
          <div class="fecha-lbl">&#8595; Salida al destino</div>
          <div class="fecha-val">
            {{ $reserva->fecha_arribo ? \Carbon\Carbon::parse($reserva->fecha_arribo)->format('d/m/Y') : '—' }}
          </div>
          <div class="fecha-hora">
            Hora: {{ $reserva->hora_arribo ? substr($reserva->hora_arribo,0,5) : '—' }}
          </div>
        </div>
        <div class="fecha-sep"></div>
        <div class="fecha-cell">
          <div class="fecha-lbl">&#8593; Retorno / Regreso</div>
          <div class="fecha-val">
            {{ $reserva->fecha_retorno ? \Carbon\Carbon::parse($reserva->fecha_retorno)->format('d/m/Y') : '—' }}
          </div>
          <div class="fecha-hora">
            Hora: {{ $reserva->hora_retorno ? substr($reserva->hora_retorno,0,5) : '—' }}
          </div>
        </div>
      </div>

      {{-- Transporte --}}
      @if($reserva->tipo_transporte)
      <div class="sec-sub" style="margin-top:7px">Transporte</div>
      <table class="t-datos">
        <tr>
          <td class="lbl">Tipo</td>
          <td class="val">{{ ucfirst($reserva->tipo_transporte) }}</td>
          @if($reserva->tipo_transporte==='terrestre' && $reserva->empresa_transporte)
            <td class="lbl">Empresa</td><td class="val">{{ $reserva->empresa_transporte }}</td>
          @elseif($reserva->tipo_transporte==='aereo')
            <td class="lbl">Aerolínea</td><td class="val">{{ $reserva->aerolinea ?? '—' }}</td>
          @else
            <td class="lbl"></td><td class="val"></td>
          @endif
        </tr>
        @if($reserva->tipo_transporte==='aereo')
        <tr>
          <td class="lbl">N° vuelo</td>
          <td class="val">{{ $reserva->numero_vuelo ?? '—' }}</td>
          <td class="lbl">Salida / Llegada</td>
          <td class="val">
            {{ $reserva->hora_salida_vuelo ? substr($reserva->hora_salida_vuelo,0,5) : '—' }}
            &nbsp;/&nbsp;
            {{ $reserva->hora_llegada_vuelo ? substr($reserva->hora_llegada_vuelo,0,5) : '—' }}
          </td>
        </tr>
        @endif
      </table>
      @endif

      {{-- Hospedaje --}}
      @if($reserva->nombre_hotel || count($habitaciones) > 0)
      <div class="sec-sub" style="margin-top:7px">Hospedaje</div>

      {{-- Hotel + estrellas (sin mostrar "hotel_2", solo nombre y estrellas) --}}
      @if($reserva->nombre_hotel)
      <table class="t-datos" style="margin-bottom:5px">
        <tr>
          <td class="lbl">Hotel</td>
          <td class="val val-strong">
            {{ $reserva->nombre_hotel }}
            @if($numEstrellas > 0)
              &nbsp;@for($s=1;$s<=5;$s++)<span class="{{ $s<=$numEstrellas ? 'star-on' : 'star-off' }}">&#9733;</span>@endfor
            @endif
          </td>
          @if($etiquetaEstab)
          <td class="lbl">Categoría</td>
          <td class="val">{{ $etiquetaEstab }}</td>
          @endif
        </tr>
      </table>
      @endif

      {{-- Habitaciones — una tarjeta por cada una --}}
      @if(count($habitaciones) > 0)
        @foreach($habitaciones as $numHab => $hab)
        <div class="hab-card">
          <div class="hab-nombre">
            Habitación {{ $numHab + 1 }} &mdash; {{ $hab['nombre'] }}
          </div>
          <div class="hab-fila">
            <span class="hab-lbl">Tipo de cama:</span>
            {{ $hab['cama'] ?? '—' }}
            &nbsp;&nbsp;
            <span class="hab-lbl">Pensión:</span>
            {{ $hab['alimentacion'] ?? '—' }}
          </div>
        </div>
        @endforeach
      @endif
      @endif

    </div>
  </div>

  {{-- ══ 2. TITULAR ══ --}}
  <div class="seccion">
    <div class="sec-titulo">2. Titular de la Reserva</div>
    <div class="caja-info">
      <table class="t-datos">
        <tr>
          <td class="lbl">Nombre completo</td>
          <td class="val val-strong">{{ $reserva->cliente->nombre_completo }}</td>
          <td class="lbl">{{ strtoupper($reserva->cliente->tipo_documento ?? 'DNI') }}</td>
          <td class="val">{{ $reserva->cliente->numero_documento ?? '—' }}</td>
        </tr>
        <tr>
          <td class="lbl">Correo</td>
          <td class="val">{{ $reserva->cliente->email ?? '—' }}</td>
          <td class="lbl">Celular</td>
          <td class="val">{{ $reserva->cliente->telefono ?? '—' }}</td>
        </tr>
        <tr>
          <td class="lbl">Fecha nacimiento</td>
          <td class="val">
            {{ $reserva->cliente->fecha_nacimiento
                ? \Carbon\Carbon::parse($reserva->cliente->fecha_nacimiento)->format('d/m/Y')
                : '—' }}
          </td>
          <td class="lbl">Ciudad origen</td>
          <td class="val">{{ $reserva->ciudad_procedencia ?? '—' }}</td>
        </tr>
        @if($reserva->cliente->telefono2)
        <tr>
          <td class="lbl">Tel. secundario</td>
          <td class="val" colspan="3">{{ $reserva->cliente->telefono2 }}</td>
        </tr>
        @endif
      </table>
    </div>
  </div>

  {{-- ══ 3. PASAJEROS ══ --}}
  <div class="seccion">
    <div class="sec-titulo">3. Pasajeros del Grupo</div>

    <table class="t-pax">
      <thead>
        <tr>
          <td style="width:16px">#</td>
          <td>Nombre Completo</td>
          <td style="width:82px">Documento</td>
          <td style="width:50px">Fecha Nac.</td>
          <td style="width:22px;text-align:center">Edad</td>
          <td style="width:38px">Tipo</td>
          <td style="width:27px;text-align:center">Salud</td>
          <td style="width:36px;text-align:center">Seguro</td>
        </tr>
      </thead>
      <tbody>

        {{-- TITULAR --}}
        <tr class="fila-tit">
          <td style="text-align:center"><span class="badge b-tit">T</span></td>
          <td style="font-weight:700;color:#1b3a6b">
            {{ $reserva->cliente->nombre_completo }}
            <br><span style="font-size:5.5pt;color:#4a90c4;font-weight:400">Titular</span>
          </td>
          <td style="font-size:6pt;color:#6b7280">
            {{ strtoupper($reserva->cliente->tipo_documento ?? 'DNI') }}: {{ $reserva->cliente->numero_documento ?? '—' }}
          </td>
          <td style="font-size:6pt;color:#6b7280">
            {{ $reserva->cliente->fecha_nacimiento
                ? \Carbon\Carbon::parse($reserva->cliente->fecha_nacimiento)->format('d/m/Y')
                : '—' }}
          </td>
          <td style="text-align:center;font-weight:700;color:#1b3a6b">{{ $edadTit ?? '—' }}</td>
          <td><span class="badge b-adt">Adulto</span></td>
          <td style="text-align:center">
            <span class="badge {{ $tieneSaludTit ? 'b-si' : 'b-no' }}">{{ $tieneSaludTit ? 'Sí' : 'No' }}</span>
          </td>
          <td style="text-align:center">
            <span class="badge {{ $seguroTit ? 'b-seg' : 'b-noseg' }}">{{ $seguroTit ? 'Sí' : 'No' }}</span>
          </td>
        </tr>

        {{-- ADICIONALES --}}
        @foreach($adicionales as $i => $pax)
        @php
          $sp  = $pax->salud ?? null;
          $ts  = $sp && ($sp->alergias || $sp->medicamentos || $sp->restricciones_alimentarias || $sp->condiciones_medicas);
          $seg = $sp?->seguro_salud ?? null;
          $esNio = in_array($pax->tipo ?? '', ['nino','niño','bebe','adolescente']);
        @endphp
        <tr>
          <td style="text-align:center;color:#9ca3af;font-size:6pt">{{ $i+2 }}</td>
          <td style="font-weight:600">{{ $pax->nombre_completo }}</td>
          <td style="font-size:6pt;color:#6b7280">
            {{ $pax->numero_documento ? strtoupper($pax->tipo_documento ?? 'DNI').': '.$pax->numero_documento : '—' }}
          </td>
          <td style="font-size:6pt;color:#6b7280">
            {{ $pax->fecha_nacimiento ? \Carbon\Carbon::parse($pax->fecha_nacimiento)->format('d/m/Y') : '—' }}
          </td>
          <td style="text-align:center;font-weight:700;color:#374151">{{ $pax->edad ?? '—' }}</td>
          <td>
            <span class="badge {{ $esNio ? 'b-nio' : 'b-adt' }}">
              {{ $tiposLabel[$pax->tipo ?? 'adulto'] ?? 'Adulto' }}
            </span>
          </td>
          <td style="text-align:center">
            <span class="badge {{ $ts ? 'b-si' : 'b-no' }}">{{ $ts ? 'Sí' : 'No' }}</span>
          </td>
          <td style="text-align:center">
            <span class="badge {{ $seg ? 'b-seg' : 'b-noseg' }}">{{ $seg ? 'Sí' : 'No' }}</span>
          </td>
        </tr>
        @endforeach

      </tbody>
    </table>

    {{-- Emergencia --}}
    @if($reserva->cliente->emergencia_nombre || $reserva->cliente->emergencia_telefono)
    <div class="caja-emerg">
      <div class="emerg-tit">&#9888; Contacto de Emergencia</div>
      <div class="emerg-grid">
        <div class="emerg-col">
          <div class="emerg-lbl">Nombre</div>
          <div class="emerg-val">{{ $reserva->cliente->emergencia_nombre ?? '—' }}</div>
        </div>
        <div class="emerg-col">
          <div class="emerg-lbl">Parentesco</div>
          <div class="emerg-val">{{ $reserva->cliente->emergencia_parentesco ?? '—' }}</div>
        </div>
        <div class="emerg-col">
          <div class="emerg-lbl">Celular</div>
          <div class="emerg-val">{{ $reserva->cliente->emergencia_telefono ?? '—' }}</div>
        </div>
      </div>
    </div>
    @endif
  </div>

  {{-- ══ 4. PAGO ══ --}}
  <div class="seccion">
    <div class="sec-titulo">4. Información de Pago</div>
    <div class="dos-col">
      <div class="col-l">
        <div class="caja-pago">
          <div class="p-row">
            <div class="p-lbl">Precio total</div>
            <div class="p-val">S/ {{ number_format($total,2) }}</div>
          </div>
          <div class="p-div"></div>
          <div class="p-row">
            <div class="p-lbl">Adelanto pagado</div>
            <div class="p-val c-green">S/ {{ number_format($pagado,2) }}</div>
          </div>
          <div class="p-div"></div>
          <div class="p-row">
            <div class="p-lbl-tot">Saldo pendiente</div>
            <div class="p-val-tot {{ $saldo>0 ? 'c-red' : 'c-green' }}">S/ {{ number_format($saldo,2) }}</div>
          </div>
          @if($saldo<=0)
          <div style="margin-top:6px;text-align:center">
            <span style="background:#059669;color:#fff;padding:2px 10px;border-radius:8px;font-size:6pt;font-weight:700;">
              &#10003; PAGADO COMPLETAMENTE
            </span>
          </div>
          @endif
        </div>
      </div>

      <div class="col-r">
        <div class="caja-info">
          <table class="t-datos">
            <tr>
              <td class="lbl">Método de pago</td>
              <td class="val val-strong">{{ $pago?->metodoPago?->nombre ?? 'No registrado' }}</td>
            </tr>
            @if($reserva->tipo_comprobante)
            <tr>
              <td class="lbl">Comprobante</td>
              <td class="val">{{ ucfirst($reserva->tipo_comprobante) }}</td>
            </tr>
            @endif
            @if($pago)
            <tr>
              <td class="lbl">Monto</td>
              <td class="val c-green">S/ {{ number_format($pago->monto,2) }}</td>
            </tr>
            @if($pago->numero_operacion)
            <tr>
              <td class="lbl">N° operación</td>
              <td class="val">{{ $pago->numero_operacion }}</td>
            </tr>
            @endif
            @if($pago->fecha_pago)
            <tr>
              <td class="lbl">Fecha pago</td>
              <td class="val">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
            </tr>
            @endif
            @endif
            @if($reserva->tipo_comprobante==='factura' && $reserva->razon_social)
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
      </div>
    </div>
  </div>

</div>

<div class="footer">
  <div class="footer-brand">Adventur — Horizonte Andino Company E.I.R.L.</div>
  <div>Jr. Amazonas 5 Esquinas Oficina – Cajamarca, Perú &nbsp;|&nbsp; RUC: 20123456789</div>
  <div>Este documento es tu comprobante oficial de reserva. Preséntalo el día del servicio.</div>
  <div class="footer-aviso">
    Generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}
  </div>
</div>

{{-- ════════ PÁGINA 2: POLÍTICAS ════════ --}}
@if($reserva->politica_descripcion)
<div class="page-break">
  <div class="header" style="padding:10px 26px">
    <div style="display:table;width:100%">
      <div style="display:table-cell;vertical-align:middle">
        @if($logoB64)
          <img src="{{ $logoB64 }}" alt="Adventur" style="max-height:40px;max-width:140px;">
        @else
          <span style="font-size:15pt;font-weight:700;color:#f5c842;letter-spacing:4px">Adventur</span>
        @endif
        <span style="font-size:6.5pt;color:#a8c8e8;margin-left:10px;letter-spacing:1px">Turismo &amp; Experiencias</span>
      </div>
      <div style="display:table-cell;vertical-align:middle;text-align:right">
        <span style="font-size:6pt;color:#a8c8e8">Reserva: </span>
        <span style="font-size:9pt;font-weight:700;color:#f5c842">{{ $reserva->codigo_reserva }}</span>
      </div>
    </div>
  </div>
  <div class="banner" style="letter-spacing:2px;font-size:7pt">
    Políticas, Términos y Condiciones
  </div>
  <div class="contenido">
    <div class="caja-pol">
      {!! nl2br(e($reserva->politica_descripcion)) !!}
    </div>
  </div>
  <div class="footer">
    <div class="footer-brand">Adventur — Horizonte Andino Company E.I.R.L.</div>
    <div>Jr. Amazonas 5 Esquinas Oficina – Cajamarca, Perú &nbsp;|&nbsp; RUC: 20123456789</div>
    <div class="footer-aviso">
      Generado automáticamente el {{ \Carbon\Carbon::now()->format('d/m/Y \a \l\a\s H:i') }}
    </div>
  </div>
</div>
@endif

</body>
</html>