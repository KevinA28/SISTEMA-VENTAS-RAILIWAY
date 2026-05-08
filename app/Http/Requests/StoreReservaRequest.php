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

    protected function prepareForValidation(): void
    {
        $pasajeros = (array) $this->input('pasajeros', []);

        foreach ($pasajeros as $key => $p) {
            if (empty($p['tipo']) || !in_array($p['tipo'], ['adulto','nino','bebe','adolescente','adulto_mayor'])) {
                $pasajeros[$key]['tipo'] = 'adulto';
            }
        }

        $adultos = 1; // titular
        $ninos   = 0;

        foreach ($pasajeros as $p) {
            if (!empty(trim($p['nombre_completo'] ?? ''))) {
                ($p['tipo'] === 'nino' || $p['tipo'] === 'bebe') ? $ninos++ : $adultos++;
            }
        }

        $estadoInicial = $this->input('estado_inicial', 'mitad_pago');
        if (!in_array($estadoInicial, ['mitad_pago', 'pagado', 'cancelada'])) {
            $estadoInicial = 'mitad_pago';
        }

        // ── Normalizar horas con N/D → null ──────────────────────────
        // Si el usuario marcó N/D, el hidden manda "N/D" — lo convertimos
        // a null para que no rompa la validación date_format:H:i
        $normHora = fn($campo) => in_array($this->input($campo), ['N/D', 'nd', '']) ? null : $this->input($campo);

        // ── Resolver aerolinea: si eligió "otra" usar el campo manual ─
        $aerolinea = $this->input('aerolinea');
        if ($aerolinea === 'otra') {
            $aerolinea = $this->input('aerolinea_otra') ?: null;
        }

        $this->merge([
            'pasajeros'        => $pasajeros,
            'cantidad_adultos' => $adultos,
            'cantidad_ninos'   => $ninos,
            'estado_inicial'   => $estadoInicial,
            'tipo_pago'        => $estadoInicial === 'pagado' ? 'pago_completo' : 'adelanto',
            'fecha_tour'       => $this->input('fecha_tour') ?: now()->toDateString(),
            'hora_salida'      => $this->input('hora_salida') ?: '08:00',
            // Normalizar horas N/D
            'hora_arribo'      => $normHora('hora_arribo'),
            'hora_retorno'     => $normHora('hora_retorno'),
            'hora_salida_vuelo'  => $normHora('hora_salida_vuelo'),
            'hora_llegada_vuelo' => $normHora('hora_llegada_vuelo'),
            // Aerolinea resuelta
            'aerolinea'        => $aerolinea,
            // Fechas N/D → null
            'fecha_arribo'     => in_array($this->input('fecha_arribo'), ['N/D', '']) ? null : $this->input('fecha_arribo'),
            'fecha_retorno'    => in_array($this->input('fecha_retorno'), ['N/D', '']) ? null : $this->input('fecha_retorno'),
        ]);
    }

    public function rules(): array
    {
        return [
            // ── Bloque 1 — Tour / Viaje ────────────────────────────────
            'nombre_tour'              => 'required|string|max:200',
            'precio_tour'              => 'required|numeric|min:0',
            'fecha_tour'               => 'nullable|date',
            'hora_salida'              => 'nullable|date_format:H:i',
            'ciudad_procedencia'       => 'required|string|max:100',
            'estado_inicial'           => 'required|in:mitad_pago,pagado,cancelada',
            'canal_contacto'           => 'required|in:whatsapp,presencial,llamada,redes_sociales,web,referido',

            'ciudad_destino'           => 'nullable|string|max:150',
            'departamento_destino'     => 'nullable|string|max:100',
            'fecha_arribo'             => 'nullable|date',
            'fecha_retorno'            => 'nullable|date',
            'dias_viaje'               => 'nullable|string|max:20',
            'hora_arribo'              => 'nullable|date_format:H:i',
            'hora_retorno'             => 'nullable|date_format:H:i',   // ← CORREGIDO: estaba ausente
            'tipo_transporte'          => 'nullable|in:terrestre,aereo',
            'empresa_transporte'       => 'nullable|string|max:150',
            // Aéreo
            'aerolinea'                => 'nullable|string|max:150',    // ← AGREGADO (ya resuelto en prepareForValidation)
            'numero_vuelo'             => 'nullable|string|max:20',
            'hora_salida_vuelo'        => 'nullable|date_format:H:i',
            'hora_llegada_vuelo'       => 'nullable|date_format:H:i',
            // Hospedaje
            'nombre_hotel'             => 'nullable|string|max:200',
            'tipo_establecimiento'     => 'nullable|string|max:50',     // ← AGREGADO
            'tipo_habitacion'          => 'nullable|string|max:300',
            'tipo_cama'                => 'nullable|in:KB,QB,TB',       // ← AGREGADO
            'plan_alimentacion'        => 'nullable|in:RO,BB,HB,FB,AI', // ← AGREGADO

            'cantidad_adultos'         => 'required|integer|min:1|max:99',
            'cantidad_ninos'           => 'required|integer|min:0|max:99',

            // ── Bloque 2 — Titular ─────────────────────────────────────
            'cliente_id'                    => 'nullable|exists:clientes,id',
            'titular_nombre'                => 'required_without:cliente_id|nullable|string|max:200',
            'titular_telefono'              => 'required_without:cliente_id|nullable|string|max:15',
            'titular_telefono_codigo'       => 'nullable|string|max:10',  // ← AGREGADO
            'titular_email'                 => 'nullable|email|max:200',
            'titular_tipo_documento'        => 'nullable|in:DNI,CE,PASAPORTE,RUC',
            'titular_numero_documento'      => 'nullable|string|max:15',
            'titular_fecha_nacimiento'      => 'nullable|date',
            'titular_genero'                => 'nullable|in:M,F,otro',
            'titular_nacionalidad'          => 'nullable|string|max:80',
            'titular_telefono2'             => 'nullable|string|max:15',
            'titular_telefono2_codigo'      => 'nullable|string|max:10',  // ← AGREGADO
            'notif_whatsapp'                => 'nullable',
            'notif_email'                   => 'nullable',
            'solo_pasajero'                 => 'nullable|boolean',         // ← AGREGADO

            // Contacto de emergencia ── TODOS AGREGADOS
            'emergencia_nombre'             => 'nullable|string|max:200',
            'emergencia_parentesco'         => 'nullable|string|max:60',
            'emergencia_parentesco_manual'  => 'nullable|string|max:60',
            'emergencia_telefono'           => 'nullable|string|max:15',
            'emergencia_telefono_codigo'    => 'nullable|string|max:10',

            // ── Bloque 3 — Pasajeros adicionales ──────────────────────
            'pasajeros'                    => 'nullable|array',
            'pasajeros.*.nombre_completo'  => 'required|string|max:200',
            'pasajeros.*.tipo'             => 'nullable|in:adulto,nino,bebe,adolescente,adulto_mayor',
            'pasajeros.*.tipo_documento'   => 'nullable|in:DNI,CE,PASAPORTE',
            'pasajeros.*.numero_documento' => 'nullable|string|max:15',
            'pasajeros.*.edad'             => 'nullable|integer|min:0|max:120',
            'pasajeros.*.telefono'         => 'nullable|string|max:15',
            'pasajeros.*.tiene_alergias'   => 'nullable|in:si,no',
            'pasajeros.*.alergias_detalle' => 'nullable|string|max:500',
            'pasajeros.*.restricciones'    => 'nullable|string|max:500',
            'pasajeros.*.obs_medicas'      => 'nullable|string|max:500',

            // ── Bloque 4 — Salud del titular ───────────────────────────
            'titular_tiene_alergias'        => 'nullable|in:si,no',
            'titular_alergias_detalle'      => 'nullable|string|max:500',
            'titular_restricciones'         => 'nullable|string|max:500',
            'titular_obs_medicas'           => 'nullable|string|max:500',
            'titular_discapacidades'        => 'nullable|string|max:200', // ← AGREGADO
            'titular_discapacidad_otro'     => 'nullable|string|max:100', // ← AGREGADO
            'titular_seguro_salud'          => 'nullable|string|max:50',  // ← AGREGADO

            // ── Bloque 5 — Pago ────────────────────────────────────────
            'tipo_comprobante'         => 'required|in:boleta,factura',
            'ruc_factura'              => 'required_if:tipo_comprobante,factura|nullable|string|max:11',
            'razon_social'             => 'required_if:tipo_comprobante,factura|nullable|string|max:200',
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

            // ── Bloque 7 — Políticas ───────────────────────────────────
            'politica_descripcion'     => 'required|string|min:20',
            'politica_tipo'            => 'nullable|in:tours,viajes',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_tour.required'              => 'El nombre del tour es obligatorio.',
            'precio_tour.required'              => 'El precio del tour es obligatorio.',
            'precio_tour.numeric'               => 'El precio debe ser un número válido.',
            'ciudad_procedencia.required'       => 'La ciudad de origen es obligatoria.',
            'canal_contacto.required'           => 'El canal de contacto es obligatorio.',
            'canal_contacto.in'                 => 'El canal de contacto seleccionado no es válido.',
            'estado_inicial.required'           => 'Debes seleccionar un estado inicial para la reserva.',
            'estado_inicial.in'                 => 'El estado seleccionado no es válido.',
            'cantidad_adultos.min'              => 'Debe haber al menos 1 adulto en la reserva.',
            'titular_nombre.required_without'   => 'El nombre completo del titular es obligatorio.',
            'titular_telefono.required_without' => 'El celular / WhatsApp del titular es obligatorio.',
            'titular_email.email'               => 'El correo electrónico no tiene un formato válido.',
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
            'pasajeros.*.nombre_completo.required' => 'El nombre de cada pasajero adicional es obligatorio.',
            'pasajeros.*.tipo.in'                  => 'El tipo de pasajero debe ser "adulto" o "niño".',
            'politica_descripcion.required'        => 'La descripción de políticas es obligatoria.',
            'politica_descripcion.min'             => 'Las políticas deben tener al menos 20 caracteres.',
            'hora_arribo.date_format'              => 'La hora de arribo no tiene un formato válido.',
            'hora_retorno.date_format'             => 'La hora de retorno no tiene un formato válido.',
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
            'tipo_cama'             => 'tipo de cama',
            'plan_alimentacion'     => 'plan de alimentación',
            'tipo_establecimiento'  => 'tipo de establecimiento',
            'hora_arribo'           => 'hora de arribo',
            'hora_retorno'          => 'hora de retorno',
        ];
    }
}