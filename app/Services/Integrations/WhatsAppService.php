<?php
// =====================================================================
// ARCHIVO: WhatsAppService.php
// UBICACIÓN: app/Services/Integrations/WhatsAppService.php
// =====================================================================

namespace App\Services\Integrations;

use App\Models\Reserva;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $token;
    private string $phoneId;
    private string $apiUrl;

    public function __construct()
    {
        $this->token   = config('services.whatsapp.token', '');
        $this->phoneId = config('services.whatsapp.phone_id', '');
        $this->apiUrl  = "https://graph.facebook.com/v19.0/{$this->phoneId}/messages";
    }

    // -----------------------------------------------------------------
    // ENVÍA CONFIRMACIÓN DE RESERVA AL CLIENTE
    // -----------------------------------------------------------------
    public function enviarConfirmacionReserva(Reserva $reserva): void
    {
        // Usar cliente.telefono (campo actualizado en la nueva migración)
        $telefono = $this->normalizarTelefono($reserva->cliente->telefono);

        if (!$telefono) {
            Log::info('WhatsAppService: cliente sin teléfono, omitiendo', [
                'reserva_id' => $reserva->id,
            ]);
            return;
        }

        $mensaje = $this->armarMensajeConfirmacion($reserva);
        $this->enviar($telefono, $mensaje, $reserva->id);
    }

    // -----------------------------------------------------------------
    // ENVÍA NOTIFICACIÓN DE PAGO VERIFICADO
    // -----------------------------------------------------------------
    public function enviarPagoVerificado(\App\Models\Pago $pago): void
    {
        $reserva  = $pago->reserva;
        $telefono = $this->normalizarTelefono($reserva->cliente->telefono);

        if (!$telefono) return;

        // Nombre del tour: puede venir de nombre_tour directo o de la relación
        $nombreTour = $reserva->nombre_tour
                   ?? $reserva->fechaTour?->tour?->nombre
                   ?? 'Tour';

        $mensaje = "✅ *Pago confirmado — Adventur*\n\n"
                 . "Hola {$reserva->cliente->nombre_completo},\n"
                 . "tu pago de S/ " . number_format($pago->monto, 2) . " ha sido verificado.\n\n"
                 . "📋 Reserva: *{$reserva->codigo_reserva}*\n"
                 . "🗺 Tour: {$nombreTour}\n\n"
                 . "¡Gracias por confiar en Adventur! 🌿";

        $this->enviar($telefono, $mensaje, $reserva->id);
    }

    // -----------------------------------------------------------------
    // MÉTODO BASE — envía a la API de Meta
    // -----------------------------------------------------------------
    private function enviar(string $telefono, string $mensaje, int $contextId = 0): void
    {
        if (empty($this->token) || empty($this->phoneId)) {
            Log::warning('WhatsAppService: token o phone_id no configurados en .env');
            return;
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->post($this->apiUrl, [
                    'messaging_product' => 'whatsapp',
                    'to'                => $telefono,
                    'type'              => 'text',
                    'text'              => ['body' => $mensaje],
                ]);

            if ($response->failed()) {
                Log::warning('WhatsAppService: envío fallido', [
                    'telefono'   => $telefono,
                    'context_id' => $contextId,
                    'status'     => $response->status(),
                    'body'       => $response->body(),
                ]);
                return;
            }

            Log::info('WhatsAppService: mensaje enviado', [
                'telefono'   => $telefono,
                'context_id' => $contextId,
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsAppService: excepción', [
                'telefono' => $telefono,
                'mensaje'  => $e->getMessage(),
            ]);
        }
    }

    // -----------------------------------------------------------------
    // NORMALIZA número a formato internacional sin +
    // -----------------------------------------------------------------
    private function normalizarTelefono(?string $telefono): ?string
    {
        if (!$telefono) return null;

        $limpio = preg_replace('/\D/', '', $telefono);

        if (strlen($limpio) === 9) return '51' . $limpio;      // Perú sin código
        if (strlen($limpio) >= 10) return $limpio;              // Ya tiene código

        return null;
    }

    // -----------------------------------------------------------------
    // ARMA EL MENSAJE DE CONFIRMACIÓN
    // -----------------------------------------------------------------
    private function armarMensajeConfirmacion(Reserva $reserva): string
    {
        $nombreTour = $reserva->nombre_tour
                   ?? $reserva->fechaTour?->tour?->nombre
                   ?? 'Tour';

        $fechaTour = $reserva->fecha_tour
            ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y')
            : ($reserva->fechaTour?->fecha
                ? \Carbon\Carbon::parse($reserva->fechaTour->fecha)->format('d/m/Y')
                : '—');

        $horaSalida = $reserva->hora_salida
            ? substr($reserva->hora_salida, 0, 5)
            : ($reserva->fechaTour?->hora_salida
                ? substr($reserva->fechaTour->hora_salida, 0, 5)
                : null);

        $adultos = $reserva->cantidad_adultos;
        $ninos   = $reserva->cantidad_ninos;
        $total   = number_format($reserva->precio_total, 2);
        $pagado  = number_format($reserva->monto_pagado, 2);
        $saldo   = number_format(max(0, $reserva->precio_total - $reserva->monto_pagado), 2);

        $lineas = [
            "🌿 *Reserva confirmada — Adventur*",
            "",
            "Hola *{$reserva->cliente->nombre_completo}*,",
            "tu reserva ha sido registrada exitosamente.",
            "",
            "📋 *Detalles de la reserva:*",
            "Código: *{$reserva->codigo_reserva}*",
            "Tour: {$nombreTour}",
            "Fecha: {$fechaTour}" . ($horaSalida ? " a las {$horaSalida}" : ""),
            "Pasajeros: {$adultos} adulto(s)" . ($ninos > 0 ? ", {$ninos} niño(s)" : ""),
        ];

        if ($reserva->ciudad_destino) {
            $lineas[] = "Destino: {$reserva->ciudad_destino}";
        }

        $lineas[] = "";
        $lineas[] = "💰 *Resumen de pago:*";
        $lineas[] = "Total: S/ {$total}";
        $lineas[] = "Pagado: S/ {$pagado}";

        if ((float)$saldo > 0) {
            $lineas[] = "Saldo pendiente: S/ {$saldo}";
        }

        $lineas[] = "";
        $lineas[] = "Para cualquier consulta escríbenos.";
        $lineas[] = "¡Gracias por elegir Adventur! 🎒";

        return implode("\n", $lineas);
    }
}