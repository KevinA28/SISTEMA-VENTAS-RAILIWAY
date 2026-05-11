<?php
// =====================================================================
// ARCHIVO: PdfService.php
// UBICACIÓN: app/Services/Integrations/PdfService.php
// =====================================================================

namespace App\Services\Integrations;

use App\Models\Pago;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfService
{
    // -----------------------------------------------------------------
    // GENERA PDF DE CONFIRMACIÓN DE RESERVA
    // -----------------------------------------------------------------
    public function generarConfirmacion(Reserva $reserva): string
    {
        $pdf = Pdf::loadView('pdf.confirmacion-reserva', [
            'reserva' => $reserva,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $path = 'reservas/confirmacion-' . $reserva->codigo_reserva . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    // -----------------------------------------------------------------
    // GENERA PDF DE COMPROBANTE DE PAGO
    // -----------------------------------------------------------------
    public function generarComprobantePago(Pago $pago): string
    {
        $pdf = Pdf::loadView('pdf.comprobante-pago', [
            'pago' => $pago,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $path = 'pagos/comprobante-' . $pago->id . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    // -----------------------------------------------------------------
    // GENERA PDF DE REPORTE DE SALUD POR DÍA DE TOUR
    // Retorna el contenido del PDF como string (no lo guarda en disco)
    // -----------------------------------------------------------------
    public function generarReporteSalud(
        $reservas,
        string $fecha,
        ?string $tourFiltro,
        bool $soloAlertas,
        int $totalPasajeros,
        int $conAlertas
    ): string {
        $pdf = Pdf::loadView('pdf.reporte-salud', compact(
            'reservas',
            'fecha',
            'tourFiltro',
            'soloAlertas',
            'totalPasajeros',
            'conAlertas'
        ));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->output();
    }

    // -----------------------------------------------------------------
    // GENERA PDF DE REPORTE DE RESERVAS
    // -----------------------------------------------------------------
    public function generarReporteReservas(array $reservas, array $filtros): string
    {
        $pdf = Pdf::loadView('pdf.reporte-reservas', [
            'reservas' => $reservas,
            'filtros'  => $filtros,
            'fecha'    => now()->format('d/m/Y H:i'),
        ]);

        $pdf->setPaper('A4', 'landscape');

        $path = 'reportes/reservas-' . now()->format('Y-m-d-His') . '.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}