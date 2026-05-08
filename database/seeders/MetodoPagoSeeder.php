<?php
// UBICACIÓN: database/seeders/MetodoPagoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            // clave (lo que manda el form)  =>  nombre (lo que se muestra)
            ['clave' => 'efectivo',          'nombre' => 'Efectivo'],
            ['clave' => 'yape',              'nombre' => 'Yape'],
            ['clave' => 'plin',              'nombre' => 'Plin'],
            ['clave' => 'tunki',             'nombre' => 'Tunki'],
            ['clave' => 'transf_bcp',        'nombre' => 'Transferencia BCP'],
            ['clave' => 'transf_bbva',       'nombre' => 'Transferencia BBVA'],
            ['clave' => 'transf_inter',      'nombre' => 'Transferencia Interbank'],
            ['clave' => 'transf_sc',         'nombre' => 'Transferencia Scotiabank'],
            ['clave' => 'transf_bn',         'nombre' => 'Transferencia Banco Nación'],
            ['clave' => 'transf_otros',      'nombre' => 'Transferencia otro banco'],
            ['clave' => 'dep_bcp',           'nombre' => 'Depósito BCP'],
            ['clave' => 'dep_bbva',          'nombre' => 'Depósito BBVA'],
            ['clave' => 'dep_inter',         'nombre' => 'Depósito Interbank'],
            ['clave' => 'dep_otros',         'nombre' => 'Depósito otro banco'],
            ['clave' => 'tarjeta_credito',   'nombre' => 'Tarjeta de crédito'],
            ['clave' => 'tarjeta_debito',    'nombre' => 'Tarjeta de débito'],
        ];

        foreach ($metodos as $m) {
            DB::table('metodos_pago')->updateOrInsert(
                ['clave' => $m['clave']],
                ['nombre' => $m['nombre'], 'activo' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}