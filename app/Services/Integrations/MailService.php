<?php
// =====================================================================
// ARCHIVO: MailService.php
// UBICACIÓN: app/Services/Integrations/MailService.php
// =====================================================================

namespace App\Services\Integrations;

use App\Models\Reserva;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailService
{
    // -----------------------------------------------------------------
    // ENVÍA CORREO DE CONFIRMACIÓN AL CLIENTE
    // BUG 1 FIX: usa email_contacto de la reserva, fallback al cliente
    // -----------------------------------------------------------------
    public function enviarConfirmacion(Reserva $reserva, ?string $pdfPath = null): void
    {
        $emailDestino = $reserva->email_contacto ?? $reserva->cliente->email;

        if (! $emailDestino) {
            Log::info('MailService: sin email de destino, omitiendo envío', [
                'reserva_id' => $reserva->id,
                'cliente_id' => $reserva->cliente_id,
            ]);
            return;
        }

        try {
            Mail::send(
                'emails.confirmacion-reserva',
                ['reserva' => $reserva],
                function ($message) use ($reserva, $pdfPath, $emailDestino) {
                    $message
                        ->to($emailDestino, $reserva->cliente->nombre_completo)
                        ->subject('Confirmación de Reserva — ' . $reserva->codigo_reserva);

                    if ($pdfPath && Storage::disk('public')->exists($pdfPath)) {
                        $message->attachData(
                            Storage::disk('public')->get($pdfPath),
                            'confirmacion-' . $reserva->codigo_reserva . '.pdf',
                            ['mime' => 'application/pdf']
                        );
                    }
                }
            );

            Log::info('MailService: confirmación enviada', [
                'reserva_id' => $reserva->id,
                'email'      => $emailDestino,
            ]);

        } catch (\Exception $e) {
            Log::error('MailService: error al enviar correo', [
                'reserva_id' => $reserva->id,
                'mensaje'    => $e->getMessage(),
            ]);
        }
    }

    // -----------------------------------------------------------------
    // ENVÍA CORREO CUANDO UN PAGO ES VERIFICADO
    // -----------------------------------------------------------------
    public function enviarPagoVerificado(\App\Models\Pago $pago): void
    {
        if (! $pago->reserva->cliente->email) {
            return;
        }

        try {
            Mail::send(
                'emails.pago-verificado',
                ['pago' => $pago],
                function ($message) use ($pago) {
                    $message
                        ->to($pago->reserva->cliente->email, $pago->reserva->cliente->nombre_completo)
                        ->subject('Pago confirmado — ' . $pago->reserva->codigo_reserva);
                }
            );
        } catch (\Exception $e) {
            Log::error('MailService: error enviando confirmación de pago', [
                'pago_id' => $pago->id,
                'mensaje' => $e->getMessage(),
            ]);
        }
    }
}