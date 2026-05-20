<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservasTestSeeder extends Seeder
{
    public function run(): void
    {
        // Evitar re-seedear si ya existen reservas de prueba
        if (DB::table('reservas')->where('codigo_reserva', 'like', 'TEST-%')->exists()) {
            $this->command->info('ReservasTestSeeder: ya existen reservas de prueba, se omite.');
            return;
        }

        // ── IDs base ──────────────────────────────────────────────────────
        $adminId = DB::table('usuarios_admin')->value('id');
        if (!$adminId) {
            $this->command->error('No hay usuarios_admin. Ejecuta DatabaseSeeder primero.');
            return;
        }

        $estados = DB::table('estados_reserva')->pluck('id', 'nombre');
        $metodos = DB::table('metodos_pago')->pluck('id', 'clave');

        $estadoPagado    = $estados['pagado']            ?? null;
        $estadoMitad     = $estados['mitad_pago']        ?? null;
        $estadoCancelada = $estados['cancelada']         ?? null;
        $estadoOtros     = $estados['otros_porcentajes'] ?? null;

        $yape      = $metodos['yape']       ?? null;
        $efectivo  = $metodos['efectivo']   ?? null;
        $transBcp  = $metodos['transf_bcp'] ?? null;
        $transBbva = $metodos['transf_bbva']?? null;
        $plin      = $metodos['plin']       ?? null;

        // ── Definición de las 10 reservas ────────────────────────────────
        $reservas = [
            [
                'codigo'        => 'TEST-001',
                'tour'          => 'Cumbemayo',
                'fecha_tour'    => now()->subDays(40)->toDateString(),
                'hora_salida'   => '07:00:00',
                'estado_id'     => $estadoPagado,
                'canal'         => 'whatsapp',
                'adultos'       => 1, 'ninos' => 0,
                'precio'        => 35.00,
                'monto_pagado'  => 35.00,
                'metodo_id'     => $yape,
                'tipo_pago'     => 'pago_completo',
                'ciudad_origen' => 'Lima',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(42),
                'pasajeros' => [
                    ['nombre' => 'María Fernanda López Quispe', 'dni' => '45812367', 'tipo' => 'adulto',
                     'nacimiento' => '1990-05-14', 'titular' => true, 'salud' => null],
                ],
            ],
            [
                'codigo'        => 'TEST-002',
                'tour'          => 'Baños del Inca',
                'fecha_tour'    => now()->addDays(10)->toDateString(),
                'hora_salida'   => '08:30:00',
                'estado_id'     => $estadoMitad,
                'canal'         => 'llamada',
                'adultos'       => 2, 'ninos' => 1,
                'precio'        => 65.00,
                'monto_pagado'  => 35.00,
                'metodo_id'     => $efectivo,
                'tipo_pago'     => 'adelanto',
                'ciudad_origen' => 'Trujillo',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(5),
                'pasajeros' => [
                    ['nombre' => 'Carlos Alberto Mendoza Ruiz', 'dni' => '32145678', 'tipo' => 'adulto',
                     'nacimiento' => '1985-11-20', 'titular' => true,
                     'salud' => ['alergias' => 'Mariscos', 'restricciones_alimentarias' => 'Sin lactosa']],
                    ['nombre' => 'Ana Sofía Mendoza Torres', 'dni' => '48923401', 'tipo' => 'adulto',
                     'nacimiento' => '1988-03-07', 'titular' => false, 'salud' => null],
                    ['nombre' => 'Luciana Mendoza Torres', 'dni' => null, 'tipo' => 'nino',
                     'nacimiento' => '2018-06-15', 'titular' => false, 'salud' => null],
                ],
            ],
            [
                'codigo'        => 'TEST-003',
                'tour'          => 'Ventanillas de Otuzco',
                'fecha_tour'    => now()->subDays(15)->toDateString(),
                'hora_salida'   => '09:00:00',
                'estado_id'     => $estadoPagado,
                'canal'         => 'presencial',
                'adultos'       => 2, 'ninos' => 0,
                'precio'        => 60.00,
                'monto_pagado'  => 60.00,
                'metodo_id'     => $transBcp,
                'tipo_pago'     => 'pago_completo',
                'ciudad_origen' => 'Chiclayo',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'factura',
                'ruc'           => '20601423874',
                'razon_social'  => 'VIAJES DEL NORTE S.A.C.',
                'fecha_reg'     => now()->subDays(20),
                'pasajeros' => [
                    ['nombre' => 'Roberto Enrique Silva Paz', 'dni' => '17823456', 'tipo' => 'adulto',
                     'nacimiento' => '1975-08-30', 'titular' => true, 'salud' => null],
                    ['nombre' => 'Patricia Silva Gómez', 'dni' => '18934512', 'tipo' => 'adulto',
                     'nacimiento' => '1978-12-01', 'titular' => false,
                     'salud' => ['condiciones_medicas' => 'Hipertensión controlada', 'seguro_salud' => 'essalud']],
                ],
            ],
            [
                'codigo'        => 'TEST-004',
                'tour'          => 'Granja Porcón',
                'fecha_tour'    => now()->subDays(60)->toDateString(),
                'hora_salida'   => '07:30:00',
                'estado_id'     => $estadoCancelada,
                'canal'         => 'redes_sociales',
                'adultos'       => 1, 'ninos' => 0,
                'precio'        => 40.00,
                'monto_pagado'  => 20.00,
                'metodo_id'     => $yape,
                'tipo_pago'     => 'adelanto',
                'ciudad_origen' => 'Lima',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(65),
                'pasajeros' => [
                    ['nombre' => 'Jorge Luis Ramírez Castro', 'dni' => '41267890', 'tipo' => 'adulto',
                     'nacimiento' => '1992-04-18', 'titular' => true, 'salud' => null],
                ],
            ],
            [
                'codigo'        => 'TEST-005',
                'tour'          => 'Machu Picchu + Cusco',
                'fecha_tour'    => now()->addDays(25)->toDateString(),
                'hora_salida'   => '06:00:00',
                'estado_id'     => $estadoPagado,
                'canal'         => 'whatsapp',
                'adultos'       => 3, 'ninos' => 1,
                'precio'        => 720.00,
                'monto_pagado'  => 720.00,
                'metodo_id'     => $plin,
                'tipo_pago'     => 'pago_completo',
                'ciudad_origen' => 'Piura',
                'ciudad_dest'   => 'Cusco', 'dpto_dest' => 'Cusco',
                'comprobante'   => 'boleta',
                'tipo_transporte' => 'aereo',
                'aerolinea'     => 'LATAM',
                'numero_vuelo'  => 'LA2047',
                'nombre_hotel'  => 'Casa Andina Premium Cusco',
                'plan_alim'     => 'BB',
                'fecha_reg'     => now()->subDays(10),
                'pasajeros' => [
                    ['nombre' => 'Diego Alejandro Vargas Herrera', 'dni' => '43891234', 'tipo' => 'adulto',
                     'nacimiento' => '1987-09-22', 'titular' => true,
                     'salud' => ['alergias' => 'Polen', 'seguro_salud' => 'eps']],
                    ['nombre' => 'Valeria Vargas Herrera', 'dni' => '44123890', 'tipo' => 'adulto',
                     'nacimiento' => '1989-02-14', 'titular' => false, 'salud' => null],
                    ['nombre' => 'Andrés Vargas Herrera', 'dni' => '47563201', 'tipo' => 'adulto',
                     'nacimiento' => '1995-07-05', 'titular' => false, 'salud' => null],
                    ['nombre' => 'Matías Vargas Herrera', 'dni' => null, 'tipo' => 'nino',
                     'nacimiento' => '2016-11-30', 'titular' => false,
                     'salud' => ['alergias' => 'Nueces', 'condiciones_medicas' => 'Asma leve']],
                ],
            ],
            [
                'codigo'        => 'TEST-006',
                'tour'          => 'City Tour Cajamarca',
                'fecha_tour'    => now()->addDays(3)->toDateString(),
                'hora_salida'   => '10:00:00',
                'estado_id'     => $estadoMitad,
                'canal'         => 'whatsapp',
                'adultos'       => 2, 'ninos' => 0,
                'precio'        => 50.00,
                'monto_pagado'  => 25.00,
                'metodo_id'     => $efectivo,
                'tipo_pago'     => 'adelanto',
                'ciudad_origen' => 'Arequipa',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(2),
                'pasajeros' => [
                    ['nombre' => 'Lucía Beatriz Flores Paredes', 'dni' => '29834512', 'tipo' => 'adulto',
                     'nacimiento' => '1982-06-10', 'titular' => true, 'salud' => null],
                    ['nombre' => 'Miguel Flores Paredes', 'dni' => '30123456', 'tipo' => 'adulto',
                     'nacimiento' => '1980-01-25', 'titular' => false, 'salud' => null],
                ],
            ],
            [
                'codigo'        => 'TEST-007',
                'tour'          => 'Laguna de Sangrar',
                'fecha_tour'    => now()->subDays(90)->toDateString(),
                'hora_salida'   => '06:30:00',
                'estado_id'     => $estadoPagado,
                'canal'         => 'web',
                'adultos'       => 1, 'ninos' => 0,
                'precio'        => 45.00,
                'monto_pagado'  => 45.00,
                'metodo_id'     => $transBbva,
                'tipo_pago'     => 'pago_completo',
                'ciudad_origen' => 'Lima',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(95),
                'pasajeros' => [
                    ['nombre' => 'Sandra Patricia Ríos Vera', 'dni' => '38712904', 'tipo' => 'adulto',
                     'nacimiento' => '1993-10-08', 'titular' => true,
                     'salud' => ['restricciones_alimentarias' => 'Vegetariana', 'seguro_salud' => 'sis']],
                ],
            ],
            [
                'codigo'        => 'TEST-008',
                'tour'          => 'Complejo Qhapaq Ñan',
                'fecha_tour'    => now()->addDays(18)->toDateString(),
                'hora_salida'   => '08:00:00',
                'estado_id'     => $estadoOtros,
                'canal'         => 'referido',
                'adultos'       => 2, 'ninos' => 1,
                'precio'        => 105.00,
                'monto_pagado'  => 60.00,
                'metodo_id'     => $yape,
                'tipo_pago'     => 'adelanto',
                'ciudad_origen' => 'Huancayo',
                'ciudad_dest'   => 'Cajamarca', 'dpto_dest' => 'Cajamarca',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(8),
                'pasajeros' => [
                    ['nombre' => 'Fernando José Castillo Mora', 'dni' => '20934781', 'tipo' => 'adulto',
                     'nacimiento' => '1970-03-15', 'titular' => true,
                     'salud' => ['condiciones_medicas' => 'Diabetes tipo 2', 'medicamentos' => 'Metformina 500mg',
                                 'seguro_salud' => 'ffaa']],
                    ['nombre' => 'Elena Castillo Mora', 'dni' => '22145678', 'tipo' => 'adulto',
                     'nacimiento' => '1972-07-19', 'titular' => false, 'salud' => null],
                    ['nombre' => 'Joaquín Castillo Mora', 'dni' => null, 'tipo' => 'nino',
                     'nacimiento' => '2015-04-02', 'titular' => false, 'salud' => null],
                ],
            ],
            [
                'codigo'        => 'TEST-009',
                'tour'          => 'Huamachuco - Wiracochapampa',
                'fecha_tour'    => now()->subDays(30)->toDateString(),
                'hora_salida'   => '05:30:00',
                'estado_id'     => $estadoPagado,
                'canal'         => 'whatsapp',
                'adultos'       => 2, 'ninos' => 0,
                'precio'        => 180.00,
                'monto_pagado'  => 180.00,
                'metodo_id'     => $efectivo,
                'tipo_pago'     => 'pago_completo',
                'ciudad_origen' => 'Lima',
                'ciudad_dest'   => 'Huamachuco', 'dpto_dest' => 'La Libertad',
                'comprobante'   => 'boleta',
                'tipo_transporte' => 'terrestre',
                'empresa_transporte' => 'Turismo Días',
                'nombre_hotel'  => 'Hotel Gran Huamachuco',
                'plan_alim'     => 'BB',
                'fecha_reg'     => now()->subDays(35),
                'pasajeros' => [
                    ['nombre' => 'Alejandro Martín Torres Quispe', 'dni' => '44678123', 'tipo' => 'adulto',
                     'nacimiento' => '1988-12-05', 'titular' => true, 'salud' => null],
                    ['nombre' => 'Camila Torres Quispe', 'dni' => '46890234', 'tipo' => 'adulto',
                     'nacimiento' => '1991-08-17', 'titular' => false, 'salud' => null],
                ],
            ],
            [
                'codigo'        => 'TEST-010',
                'tour'          => 'Chan Chan - Trujillo',
                'fecha_tour'    => now()->addDays(7)->toDateString(),
                'hora_salida'   => '09:00:00',
                'estado_id'     => $estadoMitad,
                'canal'         => 'llamada',
                'adultos'       => 1, 'ninos' => 0,
                'precio'        => 55.00,
                'monto_pagado'  => 25.00,
                'metodo_id'     => $transBcp,
                'tipo_pago'     => 'adelanto',
                'ciudad_origen' => 'Chiclayo',
                'ciudad_dest'   => 'Trujillo', 'dpto_dest' => 'La Libertad',
                'comprobante'   => 'boleta',
                'fecha_reg'     => now()->subDays(3),
                'pasajeros' => [
                    ['nombre' => 'Isabel Cristina Vega Soto', 'dni' => '39012345', 'tipo' => 'adulto',
                     'nacimiento' => '1995-01-30', 'titular' => true, 'salud' => null],
                ],
            ],
        ];

        // ── Insertar todo ────────────────────────────────────────────────
        foreach ($reservas as $r) {
            $fechaReg = $r['fecha_reg'] instanceof Carbon ? $r['fecha_reg'] : Carbon::parse($r['fecha_reg']);

            // 1. Cliente
            $clienteId = DB::table('clientes')->insertGetId([
                'nombre_completo' => $r['pasajeros'][0]['nombre'],
                'tipo_documento'  => 'DNI',
                'numero_documento'=> $r['pasajeros'][0]['dni'],
                'telefono'        => '9' . rand(10000000, 99999999),
                'email'           => strtolower(str_replace(' ', '.', explode(' ', $r['pasajeros'][0]['nombre'])[0])) . rand(10,99) . '@gmail.com',
                'nacionalidad'    => 'Peruana',
                'created_at'      => $fechaReg,
                'updated_at'      => $fechaReg,
            ]);

            // 2. Reserva
            $reservaId = DB::table('reservas')->insertGetId([
                'codigo_reserva'      => $r['codigo'],
                'nombre_tour'         => $r['tour'],
                'fecha_tour'          => $r['fecha_tour'],
                'hora_salida'         => $r['hora_salida'],
                'cliente_id'          => $clienteId,
                'fecha_tour_id'       => null,
                'estado_id'           => $r['estado_id'],
                'usuario_admin_id'    => $adminId,
                'registrado_por_nombre' => 'Admin ADVENTUR',
                'cantidad_adultos'    => $r['adultos'],
                'cantidad_ninos'      => $r['ninos'],
                'precio_total'        => $r['precio'],
                'monto_pagado'        => $r['monto_pagado'],
                'canal_contacto'      => $r['canal'],
                'ciudad_procedencia'  => $r['ciudad_origen'],
                'ciudad_destino'      => $r['ciudad_dest'],
                'departamento_destino'=> $r['dpto_dest'],
                'tipo_comprobante'    => $r['comprobante'],
                'ruc_factura'         => $r['ruc'] ?? null,
                'razon_social'        => $r['razon_social'] ?? null,
                'tipo_transporte'     => $r['tipo_transporte'] ?? null,
                'empresa_transporte'  => $r['empresa_transporte'] ?? null,
                'aerolinea'           => $r['aerolinea'] ?? null,
                'numero_vuelo'        => $r['numero_vuelo'] ?? null,
                'nombre_hotel'        => $r['nombre_hotel'] ?? null,
                'plan_alimentacion'   => $r['plan_alim'] ?? null,
                'email_contacto'      => strtolower(str_replace(' ', '.', explode(' ', $r['pasajeros'][0]['nombre'])[0])) . rand(10,99) . '@gmail.com',
                'notificacion_enviada'=> false,
                'created_at'          => $fechaReg,
                'updated_at'          => $fechaReg,
            ]);

            // 3. Pasajeros
            foreach ($r['pasajeros'] as $p) {
                $pasajeroId = DB::table('pasajeros')->insertGetId([
                    'reserva_id'       => $reservaId,
                    'nombre_completo'  => $p['nombre'],
                    'tipo_documento'   => $p['dni'] ? 'DNI' : null,
                    'numero_documento' => $p['dni'],
                    'fecha_nacimiento' => $p['nacimiento'],
                    'tipo'             => $p['tipo'],
                    'es_titular'       => $p['titular'],
                    'created_at'       => $fechaReg,
                    'updated_at'       => $fechaReg,
                ]);

                // Salud del pasajero (si tiene datos)
                if (!empty($p['salud'])) {
                    DB::table('salud_pasajero')->insert([
                        'pasajero_id'                => $pasajeroId,
                        'alergias'                   => $p['salud']['alergias'] ?? null,
                        'restricciones_alimentarias' => $p['salud']['restricciones_alimentarias'] ?? null,
                        'condiciones_medicas'        => $p['salud']['condiciones_medicas'] ?? null,
                        'medicamentos'               => $p['salud']['medicamentos'] ?? null,
                        'discapacidades'             => $p['salud']['discapacidades'] ?? null,
                        'seguro_salud'               => $p['salud']['seguro_salud'] ?? null,
                        'created_at'                 => $fechaReg,
                        'updated_at'                 => $fechaReg,
                    ]);
                }
            }

            // 4. Pago
            DB::table('pagos')->insert([
                'reserva_id'        => $reservaId,
                'metodo_pago_id'    => $r['metodo_id'],
                'registrado_por'    => $adminId,
                'monto'             => $r['monto_pagado'],
                'tipo_pago'         => $r['tipo_pago'],
                'estado_validacion' => 'verificado',
                'fecha_pago'        => $fechaReg->toDateString(),
                'created_at'        => $fechaReg,
                'updated_at'        => $fechaReg,
            ]);

            // 5. Historial de estado
            DB::table('historial_estados')->insert([
                'reserva_id'      => $reservaId,
                'estado_nuevo_id' => $r['estado_id'],
                'cambiado_por'    => $adminId,
                'motivo'          => 'Reserva creada — datos de prueba',
                'fecha_cambio'    => $fechaReg,
            ]);

            $this->command->line("  ✓ {$r['codigo']} — {$r['tour']} ({$r['adultos']}A {$r['ninos']}N)");
        }

        $this->command->info('ReservasTestSeeder: 10 reservas de prueba creadas correctamente.');
    }
}
