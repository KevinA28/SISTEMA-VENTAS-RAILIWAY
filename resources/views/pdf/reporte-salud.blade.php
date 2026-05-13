{{-- resources/views/pdf/reporte-salud.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0d1117; background: white; }

.doc-header { background: #1e40af; color: white; padding: 16px 20px; margin-bottom: 16px; }
.doc-header h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
.doc-header .doc-meta { font-size: 10px; opacity: .85; }
.doc-header .doc-fecha { font-size: 18px; font-weight: bold; margin-top: 6px; }

.aviso { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 4px; padding: 8px 12px; margin: 0 20px 14px; font-size: 10px; color: #991b1b; }

.reserva-bloque { margin: 0 20px 18px; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; page-break-inside: avoid; }

.reserva-titulo { background: #f3f4f6; border-bottom: 1px solid #e5e7eb; padding: 8px 12px; display: table; width: 100%; }
.rt-codigo { display: table-cell; font-family: monospace; font-size: 11px; font-weight: bold; color: #1d4ed8; width: 130px; }
.rt-tour { display: table-cell; font-weight: bold; font-size: 11px; color: #0d1117; }
.rt-estado { display: table-cell; text-align: right; font-size: 10px; color: #6b7280; width: 100px; }
.rt-pax { display: table-cell; text-align: right; font-size: 10px; color: #374151; width: 80px; }

.cliente-info { padding: 7px 12px; border-bottom: 1px solid #f3f4f6; background: #fafafa; font-size: 10px; color: #374151; display: table; width: 100%; }
.ci-item { display: table-cell; }

.pasajeros-table { width: 100%; border-collapse: collapse; }
.pasajeros-table th { background: #1e40af; color: white; padding: 6px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: .05em; text-align: left; }
.pasajeros-table td { padding: 7px 10px; font-size: 10px; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
.pasajeros-table tr:last-child td { border-bottom: none; }
.pasajeros-table tr:nth-child(even) td { background: #f9fafb; }

.badge-titular { background: #dbeafe; color: #1e40af; font-size: 8px; font-weight: bold; padding: 1px 5px; border-radius: 3px; margin-left: 4px; }

.alerta-cell { background: #fffbeb; }
.alerta-item { margin-bottom: 3px; display: table; width: 100%; }
.alerta-lbl { display: table-cell; font-weight: bold; color: #92400e; font-size: 9px; width: 110px; vertical-align: top; }
.alerta-val { display: table-cell; color: #374151; font-size: 10px; vertical-align: top; }
.no-alerta { color: #9ca3af; font-size: 10px; font-style: italic; }

.resumen-final { margin: 0 20px 14px; background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 14px; display: table; width: calc(100% - 40px); }
.resumen-item { display: table-cell; text-align: center; padding: 0 10px; border-right: 1px solid #e5e7eb; }
.resumen-item:last-child { border-right: none; }
.resumen-num { font-size: 20px; font-weight: bold; color: #1e40af; display: block; }
.resumen-lbl { font-size: 9px; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; }

.doc-footer { margin: 16px 20px 0; border-top: 1px solid #e5e7eb; padding-top: 8px; font-size: 9px; color: #9ca3af; display: table; width: calc(100% - 40px); }
.df-left { display: table-cell; }
.df-right { display: table-cell; text-align: right; }
</style>
</head>
<body>

<div class="doc-header">
    <h1>Reporte de Salud y Documentos — Día de Tour</h1>
    <div class="doc-fecha">{{ \Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</div>
    <div class="doc-meta">
        Generado el {{ now()->format('d/m/Y H:i') }}
        @if($tourFiltro) &nbsp;·&nbsp; Tour: {{ $tourFiltro }} @endif
        @if($soloAlertas) &nbsp;·&nbsp; Solo pasajeros con alertas médicas @endif
    </div>
</div>

<div class="aviso">
    CONFIDENCIAL — Información médica sensible. Uso exclusivo del equipo operativo.
</div>

@if($reservas->isEmpty())
<div style="text-align:center;padding:40px 20px;color:#9ca3af;font-size:13px;">
    No se encontraron reservas para la fecha {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
    @if($tourFiltro) con el tour "{{ $tourFiltro }}" @endif.
</div>
@else

<div class="resumen-final">
    <div class="resumen-item">
        <span class="resumen-num">{{ $reservas->count() }}</span>
        <span class="resumen-lbl">Reservas</span>
    </div>
    <div class="resumen-item">
        <span class="resumen-num">{{ $totalPasajeros }}</span>
        <span class="resumen-lbl">Pasajeros</span>
    </div>
    <div class="resumen-item">
        <span class="resumen-num" style="color:#d97706;">{{ $conAlertas }}</span>
        <span class="resumen-lbl">Con alertas</span>
    </div>
    <div class="resumen-item">
        <span class="resumen-num" style="color:#059669;">{{ $totalPasajeros - $conAlertas }}</span>
        <span class="resumen-lbl">Sin alertas</span>
    </div>
</div>

@foreach($reservas as $reserva)
<div class="reserva-bloque">

    <div class="reserva-titulo">
        <div class="rt-codigo">{{ $reserva->codigo_reserva }}</div>
        <div class="rt-tour">{{ $reserva->nombre_tour ?? ($reserva->fechaTour->tour->nombre ?? '—') }}</div>
        <div class="rt-pax">{{ $reserva->pasajeros->count() }} pax</div>
        <div class="rt-estado">{{ ucfirst(str_replace('_', ' ', $reserva->estado->nombre ?? '')) }}</div>
    </div>

    <div class="cliente-info">
        <div class="ci-item"><strong>{{ $reserva->cliente->nombre_completo }}</strong></div>
        <div class="ci-item" style="padding-left:16px;">Tel: {{ $reserva->cliente->telefono ?? '—' }}</div>
        @if($reserva->cliente->emergencia_nombre)
        <div class="ci-item" style="padding-left:16px;color:#92400e;">
            Emergencia: {{ $reserva->cliente->emergencia_nombre }} — {{ $reserva->cliente->emergencia_telefono ?? '—' }}
        </div>
        @endif
        @if($reserva->hora_recojo || $reserva->punto_encuentro)
        <div class="ci-item" style="padding-left:16px;">
            @if($reserva->hora_recojo) Recojo: {{ \Carbon\Carbon::parse($reserva->hora_recojo)->format('H:i') }} @endif
            @if($reserva->punto_encuentro) · {{ $reserva->punto_encuentro }} @endif
        </div>
        @endif
    </div>

    <table class="pasajeros-table">
        <thead>
            <tr>
                <th style="width:22%;">Nombre</th>
                <th style="width:10%;">Tipo</th>
                <th style="width:15%;">Documento</th>
                <th style="width:53%;">Salud / Alertas</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reserva->pasajeros as $pasajero)
        @php
            $salud = $pasajero->salud;

            // Recopilar todos los datos de salud disponibles
            $alergias        = $salud?->alergias;
            $restricciones   = $salud?->restricciones_alimentarias;
            $obsMedicas      = $salud?->condiciones_medicas;
            $discapacidades  = $salud?->discapacidades;
            $seguro          = $salud?->seguro_salud;

            // Para el titular también revisar campos directos de la reserva como fallback
            if ($pasajero->es_titular) {
                $alergias      = $alergias      ?: $reserva->alergias_titular;
                $restricciones = $restricciones ?: $reserva->restricciones_alimentarias_titular;
                $obsMedicas    = $obsMedicas    ?: $reserva->titular_obs_medicas;
            }

            // Mapas de etiquetas legibles
            $mapaDiscap = [
                'motora'         => 'Motora',
                'visual'         => 'Visual',
                'auditiva'       => 'Auditiva',
                'cognitiva'      => 'Cognitiva',
                'habla_lenguaje' => 'Habla/Lenguaje',
                'otra'           => 'Otra',
            ];
            $mapaSeguros = [
                'essalud'  => 'EsSalud',
                'privado'  => 'Seguro privado',
                'sis'      => 'SIS',
                'extranjero' => 'Seguro extranjero',
                'ninguno'  => 'Sin seguro',
            ];

            $discapTexto = null;
            if ($discapacidades) {
                $items = array_map('trim', explode(',', $discapacidades));
                $discapTexto = implode(', ', array_map(fn($i) => $mapaDiscap[$i] ?? $i, $items));
            }
            $seguroTexto = $seguro ? ($mapaSeguros[$seguro] ?? $seguro) : null;

            $tieneAlgo = $alergias || $restricciones || $obsMedicas || $discapTexto || $seguroTexto;
        @endphp
        <tr>
            <td>
                {{ $pasajero->nombre_completo }}
                @if($pasajero->es_titular)
                    <span class="badge-titular">TITULAR</span>
                @endif
            </td>
            <td>{{ ucfirst(str_replace('_', ' ', $pasajero->tipo)) }}</td>
            <td style="font-family:monospace;font-size:9px;">
                {{ $pasajero->tipo_documento ? $pasajero->tipo_documento.': '.$pasajero->numero_documento : '—' }}
                @if($pasajero->fecha_nacimiento)
                <br><span style="color:#6b7280;">{{ \Carbon\Carbon::parse($pasajero->fecha_nacimiento)->format('d/m/Y') }}</span>
                @endif
            </td>
            <td class="{{ $tieneAlgo ? 'alerta-cell' : '' }}">
                @if($tieneAlgo)
                    @if($alergias)
                    <div class="alerta-item">
                        <div class="alerta-lbl">Alergias:</div>
                        <div class="alerta-val">{{ $alergias }}</div>
                    </div>
                    @endif
                    @if($restricciones)
                    <div class="alerta-item">
                        <div class="alerta-lbl">Restricciones:</div>
                        <div class="alerta-val">{{ $restricciones }}</div>
                    </div>
                    @endif
                    @if($obsMedicas)
                    <div class="alerta-item">
                        <div class="alerta-lbl">Obs. médicas:</div>
                        <div class="alerta-val">{{ $obsMedicas }}</div>
                    </div>
                    @endif
                    @if($discapTexto)
                    <div class="alerta-item">
                        <div class="alerta-lbl">Discapacidades:</div>
                        <div class="alerta-val">{{ $discapTexto }}</div>
                    </div>
                    @endif
                    @if($seguroTexto)
                    <div class="alerta-item">
                        <div class="alerta-lbl">Seguro:</div>
                        <div class="alerta-val">{{ $seguroTexto }}</div>
                    </div>
                    @endif
                @else
                    <span class="no-alerta">Sin datos de salud</span>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endforeach

@endif

<div class="doc-footer">
    <div class="df-left">Reporte generado por {{ config('app.name') }}</div>
    <div class="df-right">{{ now()->format('d/m/Y H:i') }} · CONFIDENCIAL</div>
</div>

</body>
</html>