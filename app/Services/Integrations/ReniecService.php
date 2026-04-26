<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReniecService
{
    private string $baseUrl = 'https://apiperu.dev/api';
    private string $token;

    public function __construct()
    {
        $this->token = config('services.apiperu.token', '');
    }

    /**
     * Detecta tipo y consulta el endpoint correcto.
     */
    public function consultar(string $numero, string $tipo): ?array
    {
        return match (strtoupper($tipo)) {
            'DNI'   => $this->consultarDni($numero),
            'RUC'   => $this->consultarRuc($numero),
            default => null,
        };
    }

    /**
     * Consultar DNI en RENIEC via apiperu.dev
     * GET https://apiperu.dev/api/dni/{dni}
     * Respuesta exitosa:
     * {
     *   "success": true,
     *   "data": {
     *     "numero": "73147366",
     *     "nombre_completo": "FLORES YGNACIO, KEVIN ANTONY",
     *     "nombres": "KEVIN ANTONY",
     *     "apellido_paterno": "FLORES",
     *     "apellido_materno": "YGNACIO"
     *   }
     * }
     */
    public function consultarDni(string $dni): ?array
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(8)
                ->get("{$this->baseUrl}/dni/{$dni}");

            if ($response->failed()) {
                Log::warning('ReniecService: DNI no encontrado', [
                    'dni'    => $dni,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $body = $response->json();

            // Verificar que la respuesta sea exitosa y tenga datos
            if (empty($body['success']) || empty($body['data'])) {
                Log::warning('ReniecService: respuesta sin datos para DNI', ['dni' => $dni]);
                return null;
            }

            $data = $body['data'];

            return [
                'nombre_completo'  => trim(
                    ($data['apellido_paterno'] ?? '') . ' ' .
                    ($data['apellido_materno'] ?? '') . ' ' .
                    ($data['nombres']          ?? '')
                ),
                'nombres'          => $data['nombres']          ?? '',
                'apellido_paterno' => $data['apellido_paterno'] ?? '',
                'apellido_materno' => $data['apellido_materno'] ?? '',
                'tipo_documento'   => 'DNI',
                'numero_documento' => $dni,
                'direccion'        => $data['direccion_completa'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('ReniecService: excepcion DNI', [
                'dni'     => $dni,
                'mensaje' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Consultar RUC en SUNAT via apiperu.dev
     * GET https://apiperu.dev/api/ruc/{ruc}
     */
    public function consultarRuc(string $ruc): ?array
    {
        try {
            $response = Http::withToken($this->token)
                ->timeout(8)
                ->get("{$this->baseUrl}/ruc/{$ruc}");

            if ($response->failed()) {
                Log::warning('ReniecService: RUC no encontrado', [
                    'ruc'    => $ruc,
                    'status' => $response->status(),
                ]);
                return null;
            }

            $body = $response->json();

            if (empty($body['success']) || empty($body['data'])) {
                Log::warning('ReniecService: respuesta sin datos para RUC', ['ruc' => $ruc]);
                return null;
            }

            $data = $body['data'];

            return [
                'nombre_completo'  => $data['nombre_o_razon_social'] ?? $data['razon_social'] ?? '',
                'tipo_documento'   => 'RUC',
                'numero_documento' => $ruc,
                'direccion'        => $data['direccion'] ?? null,
                'estado'           => $data['estado']    ?? null,
                'condicion'        => $data['condicion'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('ReniecService: excepcion RUC', [
                'ruc'     => $ruc,
                'mensaje' => $e->getMessage(),
            ]);
            return null;
        }
    }
}