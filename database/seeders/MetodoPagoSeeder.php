<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('metodos_pago')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $metodos = [
            'efectivo',
            'yape',
            'plin',
            'tunki',
            'transf_bcp',
            'transf_bbva',
            'transf_inter',
            'transf_sc',
            'transf_bn',
            'transf_otros',
            'dep_bcp',
            'dep_bbva',
            'dep_inter',
            'dep_otros',
            'tarjeta_credito',
            'tarjeta_debito',
        ];

        foreach ($metodos as $nombre) {
            DB::table('metodos_pago')->insert([
                'nombre'     => $nombre,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}