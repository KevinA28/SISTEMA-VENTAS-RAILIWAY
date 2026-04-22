<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $pasajeros = (array) $this->input('pasajeros', []);

        $adultos = 1;
        $ninos   = 0;

        foreach ($pasajeros as $p) {
            if (!empty(trim($p['nombre_completo'] ?? ''))) {
                ($p['tipo'] ?? 'adulto') === 'nino' ? $ninos++ : $adultos++;
            }
        }

        $this->merge([
            'cantidad_adultos' => $adultos,
            'cantidad_ninos'   => $ninos,
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre_tour'              => 'required|string|max:200',
            'precio_tour'              => 'required|numeric|min:0',
            'fecha_tour'               => 'required|date',
            'hora_salida'              => 'required|date_format:H:i',
            'ciudad_procedencia'       => 'required|string|max:100',
            'estado_inicial'           => 'required|in:mitad_pago,pagado,cancelada',
            'canal_contacto'           => 'required|in:whatsapp,presencial,llamada,redes_sociales,web,referido',
            'cantidad_adultos'         => 'required|integer|min:1|max:99',
            'cantidad_ninos'           => 'required|integer|min:0|max:99',

            'cliente_id'               => 'nullable|exists:clientes,id',
            'titular_nombre'           => 'required_without:cliente_id|nullable|string|max:200',
            'titular_telefono'         => 'required_without:cliente_id|nullable|string|max:15',
            'titular_email'            => 'nullable|email|max:200',
            'titular_tipo_documento'   => 'nullable|in:DNI,CE,PASAPORTE,RUC',
            'titular_numero_documento' => 'nullable|string|max:15',
            'titular_fecha_nacimiento' => 'nullable|date',
            'titular_genero'           => 'nullable|in:M,F,otro',
            'titular_nacionalidad'     => 'nullable|string|max:80',
            'titular_telefono2'        => 'nullable|string|max:15',
            'notif_whatsapp'           => 'nullable',
            'notif_email'              => 'nullable',

            'pasajeros'                    => 'nullable|array',
            'pasajeros.*.nombre_completo'  => 'required|string|max:200',
            'pasajeros.*.tipo'             => 'nullable|in:adulto,nino',
            'pasajeros.*.tipo_documento'   => 'nullable|in:DNI,CE,PASAPORTE',
            'pasajeros.*.numero_documento' => 'nullable|string|max:15',
            'pasajeros.*.edad'             => 'nullable|integer|min:0|max:120',
            'pasajeros.*.telefono'         => 'nullable|string|max:15',
            'pasajeros.*.tiene_alergias'   => 'nullable|in:si,no',
            'pasajeros.*.alergias_detalle' => 'nullable|string|max:500',
            'pasajeros.*.restricciones'    => 'nullable|string|max:500',
            'pasajeros.*.obs_medicas'      => 'nullable|string|max:500',

            'titular_tiene_alergias'   => 'nullable|in:si,no',
            'titular_alergias_detalle' => 'nullable|string|max:500',
            'titular_restricciones'    => 'nullable|string|max:500',
            'titular_obs_medicas'      => 'nullable|string|max:500',

            'tipo_comprobante'         => 'required|in:boleta,factura',
            'ruc_factura'              => 'required_if:tipo_comprobante,factura|nullable|string|max:11',
            'razon_social'             => 'required_if:tipo_comprobante,factura|nullable|string|max:200',
            'metodo_pago'              => 'nullable|string',
            'monto_pagado_inicial'     => 'nullable|numeric|min:0',
            'tipo_pago'                => 'nullable|in:adelanto,pago_completo',
            'fecha_pago'               => 'nullable|date',
            'numero_operacion'         => 'nullable|string|max:100',
            'archivo_baucher'          => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',

            'punto_encuentro'          => 'nullable|string|max:300',
            'hora_recojo'              => 'nullable|date_format:H:i',
            'guia_asignado'            => 'nullable|string|max:150',
            'observaciones'            => 'nullable|string|max:1000',

            'politica_descripcion'     => 'required|string|min:20',
            'politica_tipo'            => 'nullable|in:tours,viajes',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_tour.required'              => 'El nombre del tour es obligatorio.',
            'precio_tour.required'              => 'El precio del tour es obligatorio.',
            'fecha_tour.required'               => 'La fecha del tour es obligatoria.',
            'hora_salida.required'              => 'La hora de salida es obligatoria.',
            'hora_salida.date_format'           => 'La hora de salida no tiene un formato válido (HH:MM).',
            'ciudad_procedencia.required'       => 'La ciudad de origen es obligatoria.',
            'canal_contacto.required'           => 'El canal de contacto es obligatorio.',
            'canal_contacto.in'                 => 'El canal de contacto seleccionado no es válido.',
            'estado_inicial.required'           => 'Debes seleccionar un estado inicial para la reserva.',
            'cantidad_adultos.min'              => 'Debe haber al menos 1 adulto en la reserva.',
            'titular_nombre.required_without'   => 'El nombre completo del titular es obligatorio.',
            'titular_telefono.required_without' => 'El celular / WhatsApp del titular es obligatorio.',
            'tipo_comprobante.required'         => 'El tipo de comprobante es obligatorio.',
            'ruc_factura.required_if'           => 'El RUC es obligatorio cuando se emite una factura.',
            'razon_social.required_if'          => 'La razón social es obligatoria cuando se emite una factura.',
            'archivo_baucher.mimes'             => 'El comprobante debe ser JPG, PNG, PDF o WEBP.',
            'archivo_baucher.max'               => 'El comprobante no puede superar los 5 MB.',
            'politica_descripcion.required'     => 'La descripción de políticas es obligatoria.',
            'politica_descripcion.min'          => 'Las políticas deben tener al menos 20 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre_tour'          => 'nombre del tour',
            'precio_tour'          => 'precio del tour',
            'fecha_tour'           => 'fecha del tour',
            'hora_salida'          => 'hora de salida',
            'ciudad_procedencia'   => 'ciudad de origen',
            'canal_contacto'       => 'canal de contacto',
            'estado_inicial'       => 'estado inicial',
            'titular_nombre'       => 'nombre del titular',
            'titular_telefono'     => 'teléfono del titular',
            'tipo_comprobante'     => 'tipo de comprobante',
            'monto_pagado_inicial' => 'monto pagado',
            'archivo_baucher'      => 'comprobante de pago',
            'punto_encuentro'      => 'punto de encuentro',
            'hora_recojo'          => 'hora de recojo',
        ];
    }
}