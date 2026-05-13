<?php

namespace App\Console\Commands;

use App\Models\Reserva;
use App\Services\Integrations\MailService;
use App\Services\Integrations\PdfService;
use App\Services\Integrations\WhatsAppService;
use Illuminate\Console\Command;

class NotificarReserva extends Command
{
    protected $signature   = 'reserva:notificar-file {file}';
protected $description = 'Envía notificaciones de confirmación en segundo plano';

public function handle(
    PdfService      $pdfService,
    MailService     $mailService,
    WhatsAppService $whatsAppService
): void {
    $file = $this->argument('file');

    if (!file_exists($file)) {
        \Log::error('NotificarReserva: archivo no encontrado', ['file' => $file]);
        return;
    }

    $payload = json_decode(file_get_contents($file), true);
    @unlink($file); // ✅ Limpiar archivo temporal

    $reservaId     = $payload['reserva_id']     ?? null;
    $notifEmail    = $payload['notif_email']    ?? false;
    $notifWhatsapp = $payload['notif_whatsapp'] ?? false;

    if (!$reservaId) return;

    try {
        $reserva = Reserva::with(['cliente', 'pasajeros.salud', 'pagos.metodoPago'])
            ->findOrFail($reservaId);

        $pdfPath = null;
        if ($notifEmail) {
            $pdfPath = $pdfService->generarConfirmacion($reserva);
            $mailService->enviarConfirmacion($reserva, $pdfPath);
        }

        if ($notifWhatsapp) {
            $whatsAppService->enviarConfirmacionReserva($reserva);
        }

        $reserva->update(['notificacion_enviada' => true]);

        \Log::info('NotificarReserva: OK', ['reserva_id' => $reservaId]);

    } catch (\Exception $e) {
        \Log::error('NotificarReserva: error', [
            'reserva_id' => $reservaId,
            'mensaje'    => $e->getMessage(),
        ]);
    }
}
}