<?php
// =====================================================================
// ARCHIVO: ReservasExport.php
// UBICACIÓN: app/Exports/ReservasExport.php
// =====================================================================

namespace App\Exports;

use App\Models\Reserva;
use Illuminate\Http\Response;

class ReservasExport
{
    public function __construct(private array $filtros = []) {}

    public function download(): Response
    {
        $rows   = $this->getData();
        $xml    = $this->buildXml($rows);
        $nombre = 'reservas-' . now()->format('Y-m-d-His') . '.xls';

        return response($xml, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
            'Cache-Control'       => 'max-age=0',
            'Pragma'              => 'public',
        ]);
    }

    private function getData(): array
    {
        $query = Reserva::with(['cliente', 'estado', 'pagos'])->latest();

        // ── FIX: soporte de múltiples estados (viene como "1,2,3") ──
        if (!empty($this->filtros['estados'])) {
            $ids = array_filter(explode(',', $this->filtros['estados']));
            if (count($ids)) {
                $query->whereIn('estado_id', $ids);
            }
        } elseif (!empty($this->filtros['estado'])) {
            // compatibilidad con el filtro simple anterior
            $query->where('estado_id', $this->filtros['estado']);
        }

        // ── FIX: soporte de múltiples canales (viene como "yape,efectivo") ──
        if (!empty($this->filtros['canales'])) {
            $canales = array_filter(explode(',', $this->filtros['canales']));
            if (count($canales)) {
                $query->whereIn('canal_contacto', $canales);
            }
        } elseif (!empty($this->filtros['canal'])) {
            $query->where('canal_contacto', $this->filtros['canal']);
        }

        if (!empty($this->filtros['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $this->filtros['fecha_desde']);
        }
        if (!empty($this->filtros['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $this->filtros['fecha_hasta']);
        }
        if (!empty($this->filtros['buscar'])) {
            $b = $this->filtros['buscar'];
            $query->where(fn($q) =>
                $q->where('codigo_reserva', 'like', "%{$b}%")
                  ->orWhere('nombre_tour', 'like', "%{$b}%")
                  ->orWhereHas('cliente', fn($c) =>
                      $c->where('nombre_completo',   'like', "%{$b}%")
                        ->orWhere('numero_documento', 'like', "%{$b}%")
                        ->orWhere('telefono',         'like', "%{$b}%")
                  )
            );
        }

        return $query->get()->map(function ($r) {
            $pagado = $r->pagos->sum('monto');
            $total  = (float) $r->precio_total;
            $saldo  = max(0, $total - $pagado);
            $pct    = $total > 0 ? round($pagado / $total * 100, 1) : 0;

            return [
                $r->codigo_reserva,
                $r->created_at->format('d/m/Y H:i'),
                ucfirst(str_replace('_', ' ', $r->estado->nombre ?? '—')),
                $r->nombre_tour ?? '—',
                $r->fecha_tour
                    ? \Carbon\Carbon::parse($r->fecha_tour)->format('d/m/Y')
                    : '—',
                $r->hora_salida ? substr($r->hora_salida, 0, 5) : '—',
                $r->cliente->nombre_completo ?? '—',
                $r->cliente->numero_documento ?? '—',
                $r->cliente->telefono ?? '—',
                $r->email_contacto ?? $r->cliente->email ?? '—',
                $r->ciudad_procedencia ?? '—',
                $r->ciudad_destino ?? '—',
                (string) $r->cantidad_adultos,
                (string) $r->cantidad_ninos,
                number_format($total,  2, '.', ''),
                number_format($pagado, 2, '.', ''),
                number_format($saldo,  2, '.', ''),
                $pct . '%',
                ucfirst(str_replace('_', ' ', $r->canal_contacto ?? '—')),
                ucfirst($r->tipo_comprobante ?? '—'),
                $r->nombre_hotel ?? '—',
                $r->tipo_habitacion ?? '—',
                $r->tipo_transporte ? ucfirst($r->tipo_transporte) : '—',
                $r->observaciones ?? '—',
            ];
        })->toArray();
    }

    private function headings(): array
    {
        return [
            'Código', 'Fecha Registro', 'Estado', 'Tour / Servicio',
            'Fecha Tour', 'Hora Salida', 'Cliente', 'DNI / Doc.',
            'Teléfono', 'Email', 'Ciudad Origen', 'Ciudad Destino',
            'Adultos', 'Niños', 'Total S/', 'Pagado S/', 'Saldo S/',
            '% Pagado', 'Canal', 'Comprobante', 'Hotel',
            'Tipo Habitación', 'Transporte', 'Observaciones',
        ];
    }

    private function buildXml(array $rows): string
    {
        $headings = $this->headings();

        $xml  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<?mso-application progid=\"Excel.Sheet\"?>\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:x="urn:schemas-microsoft-com:office:excel">' . "\n";

        $xml .= '<Styles>
  <Style ss:ID="h">
    <Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>
    <Interior ss:Color="#1E40AF" ss:Pattern="Solid"/>
    <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
  </Style>
  <Style ss:ID="e">
    <Interior ss:Color="#EFF6FF" ss:Pattern="Solid"/>
    <Font ss:Size="10"/>
    <Alignment ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="o">
    <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
    <Font ss:Size="10"/>
    <Alignment ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="m">
    <Interior ss:Color="#F0FDF4" ss:Pattern="Solid"/>
    <Font ss:Size="10" ss:Bold="1" ss:Color="#059669"/>
    <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
    <NumberFormat ss:Format="0.00"/>
  </Style>
  <Style ss:ID="s">
    <Interior ss:Color="#FEF2F2" ss:Pattern="Solid"/>
    <Font ss:Size="10" ss:Bold="1" ss:Color="#DC2626"/>
    <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
    <NumberFormat ss:Format="0.00"/>
  </Style>
</Styles>' . "\n";

        $xml .= '<Worksheet ss:Name="Reservas">' . "\n";
        $xml .= '<Table>' . "\n";

        $ws = [80,100,80,160,80,60,160,80,90,150,90,100,50,50,75,75,75,60,80,80,120,120,80,160];
        foreach ($ws as $w) {
            $xml .= '<Column ss:Width="' . $w . '"/>' . "\n";
        }

        $xml .= '<Row ss:Height="24">' . "\n";
        foreach ($headings as $h) {
            $xml .= '<Cell ss:StyleID="h"><Data ss:Type="String">' . $this->esc($h) . '</Data></Cell>' . "\n";
        }
        $xml .= '</Row>' . "\n";

        foreach ($rows as $i => $row) {
            $base = ($i % 2 === 0) ? 'e' : 'o';
            $xml .= '<Row ss:Height="17">' . "\n";
            foreach ($row as $j => $cell) {
                if ($j === 14 || $j === 15) {
                    $sty = 'm'; $type = 'Number';
                } elseif ($j === 16) {
                    $sty = ((float)$cell > 0) ? 's' : 'm'; $type = 'Number';
                } elseif (in_array($j, [12, 13])) {
                    $sty = $base; $type = 'Number';
                } else {
                    $sty = $base; $type = 'String';
                }
                $val = $type === 'Number' ? $cell : $this->esc((string) $cell);
                $xml .= '<Cell ss:StyleID="' . $sty . '"><Data ss:Type="' . $type . '">' . $val . '</Data></Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
  <FreezePanes/>
  <FrozenNoSplit/>
  <SplitHorizontal>1</SplitHorizontal>
  <TopRowBottomPane>1</TopRowBottomPane>
  <ActivePane>2</ActivePane>
</WorksheetOptions>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return $xml;
    }

    private function esc(string $v): string
    {
        return htmlspecialchars($v, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}