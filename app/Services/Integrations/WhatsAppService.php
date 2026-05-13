<?php

namespace App\Services\Integrations;

use App\Models\Reserva;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private ?string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    public function enviarConfirmacionReserva(Reserva $reserva): void
    {
        $telefono = $this->normalizarTelefono($reserva->cliente->telefono);

        if (!$telefono) {
            Log::info('WhatsAppService: cliente sin teléfono', ['reserva_id' => $reserva->id]);
            return;
        }

        $mensaje = $this->armarMensajeConfirmacion($reserva);
        $this->enviar($telefono, $mensaje, $reserva->id);
    }

    public function enviarPagoVerificado(\App\Models\Pago $pago): void
    {
        $reserva  = $pago->reserva;
        $telefono = $this->normalizarTelefono($reserva->cliente->telefono);

        if (!$telefono) return;

        $nombreTour = $reserva->nombre_tour ?? $reserva->fechaTour?->tour?->nombre ?? 'Tour';

        $mensaje = "✅ Pago confirmado — Adventur\n\n"
                 . "Hola {$reserva->cliente->nombre_completo},\n"
                 . "tu pago de S/ " . number_format($pago->monto, 2) . " ha sido verificado.\n\n"
                 . "Reserva: {$reserva->codigo_reserva}\n"
                 . "Tour: {$nombreTour}\n\n"
                 . "Gracias por confiar en Adventur!";

        $this->enviar($telefono, $mensaje, $reserva->id);
    }

    private function enviar(string $telefono, string $mensaje, int $contextId = 0): void
    {
        if (empty($this->token)) {
            Log::warning('WhatsAppService: FONNTE_TOKEN no configurado en .env');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->timeout(15)->post('https://api.fonnte.com/send', [
                'target'  => $telefono,
                'message' => $mensaje,
            ]);

            $body = $response->json();

            if (!($body['status'] ?? false)) {
                Log::warning('WhatsAppService: envío fallido', [
                    'telefono'   => $telefono,
                    'context_id' => $contextId,
                    'response'   => $body,
                ]);
                return;
            }

            Log::info('WhatsAppService: mensaje enviado via Fonnte', [
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

    private function normalizarTelefono(?string $telefono): ?string
    {
        if (!$telefono) return null;
        $limpio = preg_replace('/\D/', '', $telefono);
        if (strlen($limpio) === 9) return '51' . $limpio;
        if (strlen($limpio) >= 10) return $limpio;
        return null;
    }

    private function armarMensajeConfirmacion(Reserva $reserva): string
    {
        $nombreTour = $reserva->nombre_tour
                   ?? $reserva->fechaTour?->tour?->nombre
                   ?? 'Tour';

        $fechaTour = $reserva->fecha_tour
            ? \Carbon\Carbon::parse($reserva->fecha_tour)->format('d/m/Y')
            : '—';

        $total  = number_format($reserva->precio_total, 2);
        $pagado = number_format($reserva->monto_pagado, 2);
        $saldo  = number_format(max(0, $reserva->precio_total - $reserva->monto_pagado), 2);

        $lineas = [
            "🌿 *Reserva confirmada — Adventur*",
            "",
            "Hola *{$reserva->cliente->nombre_completo}*,",
            "tu reserva ha sido registrada exitosamente.",
            "",
            "📋 *Código:* {$reserva->codigo_reserva}",
            "🗺 *Tour:* {$nombreTour}",
            "📅 *Fecha:* {$fechaTour}",
            "👥 *Pasajeros:* {$reserva->cantidad_adultos} adulto(s)" .
                ($reserva->cantidad_ninos > 0 ? ", {$reserva->cantidad_ninos} niño(s)" : ""),
            "",
            "💰 *Total:* S/ {$total}",
            "✅ *Pagado:* S/ {$pagado}",
        ];

        if ((float) $saldo > 0) {
            $lineas[] = "⏳ *Saldo pendiente:* S/ {$saldo}";
        }

        $lineas[] = "";
        $lineas[] = "📧 Revisa tu correo para ver la confirmación completa con todos los detalles.";
        $lineas[] = "";
        $lineas[] = "¡Gracias por elegir Adventur! 🎒";

        return implode("\n", $lineas);
    }
}