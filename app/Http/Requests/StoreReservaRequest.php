<?php
// =====================================================================
// ARCHIVO: StoreReservaRequest.php
// UBICACIÓN: app/Http/Requests/StoreReservaRequest.php
// =====================================================================

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Bloque 1 — Tour y fecha
            'fecha_tour_id'               => 'required|exists:fechas_tour,id',
            'cantidad_adultos'            => 'required|integer|min:1|max:99',
            'cantidad_ninos'              => 'required|integer|min:0|max:99',
            'ciudad_procedencia'          => 'required|string|max:100',
            'canal_contacto'              => 'required|in:whatsapp,presencial,llamada,redes_sociales,web',

            // Bloque 2 — Titular (se resuelve a cliente_id en el controller)
            'cliente_id'                  => 'nullable|exists:clientes,id',
            'titular_nombre'              => 'required_without:cliente_id|string|max:200',
            'titular_telefono'            => 'required_without:cliente_id|string|max:9',
            'titular_email'               => 'nullable|email|max:200',

            // Bloque 3 — Pasajeros adicionales
            'pasajeros'                   => 'nullable|array',
            'pasajeros.*.nombre_completo' => 'required|string|max:200',
            'pasajeros.*.tipo'            => 'required|in:adulto,nino',
            'pasajeros.*.tipo_documento'  => 'nullable|in:DNI,CE,PASAPORTE',
            'pasajeros.*.numero_documento'=> 'nullable|string|max:15',
            'pasajeros.*.edad'            => 'nullable|integer|min:0|max:120',

            // Bloque 4 — Salud
            'tiene_alergias'                    => 'nullable|in:si,no',
            'alergias_detalle'                  => 'nullable|string|max:500',
            'restricciones_alimentarias'        => 'nullable|string|max:500',

            // Bloque 5 — Pago y comprobante
            'tipo_comprobante'            => 'required|in:boleta,factura',
            'ruc_factura'                 => 'required_if:tipo_comprobante,factura|nullable|digits:11',
            'razon_social'                => 'required_if:tipo_comprobante,factura|nullable|string|max:200',
            'metodo_pago_id'              => 'required|exists:metodos_pago,id',
            'monto_pagado_inicial'        => 'required|numeric|min:0',
            'tipo_pago'                   => 'required|in:adelanto,pago_completo',
            'archivo_baucher'             => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',

            // Bloque 6 — Punto de encuentro
            'punto_encuentro'             => 'nullable|string|max:300',
            'hora_recojo'                 => 'nullable|date_format:H:i',
            'observaciones'               => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_tour_id.required'           => 'Debes seleccionar un tour y fecha.',
            'cantidad_adultos.min'             => 'Debe haber al menos 1 adulto.',
            'ciudad_procedencia.required'      => 'La ciudad de procedencia es obligatoria.',
            'titular_nombre.required_without'  => 'El nombre del titular es obligatorio.',
            'titular_telefono.required_without'=> 'El teléfono del titular es obligatorio.',
            'ruc_factura.required_if'          => 'El RUC es obligatorio para facturas.',
            'ruc_factura.digits'               => 'El RUC debe tener exactamente 11 dígitos.',
            'razon_social.required_if'         => 'La razón social es obligatoria para facturas.',
            'metodo_pago_id.required'          => 'El método de pago es obligatorio.',
            'monto_pagado_inicial.required'    => 'El monto pagado es obligatorio.',
            'archivo_baucher.mimes'            => 'El comprobante debe ser JPG, PNG, PDF o WEBP.',
            'archivo_baucher.max'              => 'El comprobante no puede superar 5MB.',
            'pasajeros.*.nombre_completo.required' => 'El nombre de cada pasajero es obligatorio.',
        ];
    }
}