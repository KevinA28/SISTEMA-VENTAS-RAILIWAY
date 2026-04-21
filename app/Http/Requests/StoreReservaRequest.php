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

    // ──────────────────────────────────────────────────────────────────
    //  CORRECCIÓN PRINCIPAL:
    //  El formulario NO envía cantidad_adultos ni cantidad_ninos como
    //  campos de input. Se calculan aquí automáticamente contando los
    //  pasajeros del array antes de que arranque la validación.
    //  El titular siempre suma como 1 adulto.
    // ──────────────────────────────────────────────────────────────────
    protected function prepareForValidation(): void
    {
        $pasajeros = (array) $this->input('pasajeros', []);

        $adultos = 1; // el titular
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
            // ── Bloque 1 — Tour ────────────────────────────────────────
            'nombre_tour'              => 'required|string|max:200',
            'precio_tour'              => 'required|numeric|min:0',
            'fecha_tour'               => 'required|date',
            'hora_salida'              => 'required|date_format:H:i',
            'ciudad_procedencia'       => 'required|string|max:100',
            'canal_contacto' => 'required|in:whatsapp,presencial,llamada,redes_sociales,web,referido',
            'estado_inicial'           => 'required|in:mitad_pago,pagado,cancelada',

            // Calculados en prepareForValidation — nunca fallan por falta de input
            'cantidad_adultos'         => 'required|integer|min:1|max:99',
            'cantidad_ninos'           => 'required|integer|min:0|max:99',

            // ── Bloque 2 — Titular ─────────────────────────────────────
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

            // ── Bloque 3 — Pasajeros adicionales ──────────────────────
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

            // ── Bloque 4 — Salud del titular ───────────────────────────
            'titular_tiene_alergias'   => 'nullable|in:si,no',
            'titular_alergias_detalle' => 'nullable|string|max:500',
            'titular_restricciones'    => 'nullable|string|max:500',
            'titular_obs_medicas'      => 'nullable|string|max:500',

            // ── Bloque 5 — Pago ────────────────────────────────────────
            'tipo_comprobante'         => 'required|in:boleta,factura',
            'ruc_factura'              => 'required_if:tipo_comprobante,factura|nullable|string|max:11',
            'razon_social'             => 'required_if:tipo_comprobante,factura|nullable|string|max:200',

            // CORRECCIÓN: era exists:metodos_pago,nombre → el form envía el slug (ej: "yape"),
            // no el nombre legible. Se valida solo como string para evitar falsos errores.
            'metodo_pago'              => 'required|string',

            'monto_pagado_inicial'     => 'required|numeric|min:0',
            'tipo_pago'                => 'required|in:adelanto,pago_completo',
            'fecha_pago'               => 'nullable|date',
            'numero_operacion'         => 'nullable|string|max:100',
            'archivo_baucher'          => 'nullable|file|mimes:jpg,jpeg,png,pdf,webp|max:5120',

            // ── Bloque 6 — Logística ───────────────────────────────────
            'punto_encuentro'          => 'nullable|string|max:300',
            'hora_recojo'              => 'nullable|date_format:H:i',
            'guia_asignado'            => 'nullable|string|max:150',
            'observaciones'            => 'nullable|string|max:1000',
            // ── AÑADIR: Bloque 7 — Políticas ────────────────────────────
            'politica_descripcion'     => 'required|string|min:20',
            'politica_tipo'            => 'nullable|in:tours,viajes',
        ];
    }

    public function messages(): array
    {
        return [
            // Tour
            'nombre_tour.required'              => 'El nombre del tour es obligatorio.',
            'precio_tour.required'              => 'El precio del tour es obligatorio.',
            'precio_tour.numeric'               => 'El precio debe ser un número válido.',
            'fecha_tour.required'               => 'La fecha del tour es obligatoria.',
            'fecha_tour.date'                   => 'La fecha del tour no tiene un formato válido.',
            'hora_salida.required'              => 'La hora de salida es obligatoria.',
            'hora_salida.date_format'           => 'La hora de salida no tiene un formato válido (HH:MM).',
            'ciudad_procedencia.required'       => 'La ciudad de origen es obligatoria.',
            'canal_contacto.required'           => 'El canal de contacto es obligatorio.',
            'canal_contacto.in'                 => 'El canal de contacto seleccionado no es válido.',
            'canal_contacto'                    => 'required|in:whatsapp,presencial,llamada,redes_sociales,web,referido',
            'estado_inicial.required'           => 'Debes seleccionar un estado inicial para la reserva.',
            'cantidad_adultos.min'              => 'Debe haber al menos 1 adulto en la reserva.',

            // Titular
            'titular_nombre.required_without'   => 'El nombre completo del titular es obligatorio.',
            'titular_telefono.required_without' => 'El celular / WhatsApp del titular es obligatorio.',
            'titular_email.email'               => 'El correo electrónico no tiene un formato válido.',

            // Pago
            'tipo_comprobante.required'         => 'El tipo de comprobante es obligatorio.',
            'tipo_comprobante.in'               => 'El tipo de comprobante debe ser boleta o factura.',
            'metodo_pago.required'              => 'El método de pago es obligatorio.',
            'monto_pagado_inicial.required'     => 'El monto pagado es obligatorio.',
            'monto_pagado_inicial.numeric'      => 'El monto pagado debe ser un número válido.',
            'monto_pagado_inicial.min'          => 'El monto pagado no puede ser negativo.',
            'tipo_pago.required'                => 'El tipo de pago es obligatorio.',
            'ruc_factura.required_if'           => 'El RUC es obligatorio cuando se emite una factura.',
            'razon_social.required_if'          => 'La razón social es obligatoria cuando se emite una factura.',
            'archivo_baucher.mimes'             => 'El comprobante debe ser JPG, PNG, PDF o WEBP.',
            'archivo_baucher.max'               => 'El comprobante no puede superar los 5 MB.',

            // Pasajeros
            'pasajeros.*.nombre_completo.required' => 'El nombre de cada pasajero adicional es obligatorio.',
            'pasajeros.*.tipo.in'                  => 'El tipo de pasajero debe ser "adulto" o "niño".',
            'politica_descripcion.required' => 'La descripción de políticas es obligatoria.',
            'politica_descripcion.min'      => 'Las políticas deben tener al menos 20 caracteres.',
            'canal_contacto.in'             => 'El canal de contacto seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre_tour'           => 'nombre del tour',
            'precio_tour'           => 'precio del tour',
            'fecha_tour'            => 'fecha del tour',
            'hora_salida'           => 'hora de salida',
            'ciudad_procedencia'    => 'ciudad de origen',
            'canal_contacto'        => 'canal de contacto',
            'estado_inicial'        => 'estado inicial',
            'cantidad_adultos'      => 'cantidad de adultos',
            'cantidad_ninos'        => 'cantidad de niños',
            'titular_nombre'        => 'nombre del titular',
            'titular_telefono'      => 'teléfono del titular',
            'titular_email'         => 'correo electrónico',
            'tipo_comprobante'      => 'tipo de comprobante',
            'metodo_pago'           => 'método de pago',
            'monto_pagado_inicial'  => 'monto pagado',
            'tipo_pago'             => 'tipo de pago',
            'fecha_pago'            => 'fecha de pago',
            'archivo_baucher'       => 'comprobante de pago',
            'punto_encuentro'       => 'punto de encuentro',
            'hora_recojo'           => 'hora de recojo',
            'guia_asignado'         => 'guía asignado',
        ];
    }
}