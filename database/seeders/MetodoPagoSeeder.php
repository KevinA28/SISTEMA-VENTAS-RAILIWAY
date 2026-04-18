<?php
// =====================================================================
// ARCHIVO: MetodoPagoSeeder.php
// UBICACIÓN: database/seeders/MetodoPagoSeeder.php
// =====================================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    public function run(): void
    {
        $metodos = [
            'Efectivo',
            'Yape',
            'Plin',
            'Tunki',
            'Transferencia BCP',
            'Transferencia BBVA',
            'Transferencia Interbank',
            'Transferencia Scotiabank',
            'Transferencia Banco Nación',
            'Transferencia otro banco',
            'Depósito BCP',
            'Depósito BBVA',
            'Depósito Interbank',
            'Depósito otro banco',
            'Tarjeta crédito',
            'Tarjeta débito',
        ];

        foreach ($metodos as $nombre) {
            DB::table('metodos_pago')->insertOrIgnore([
                'nombre'     => $nombre,
                'activo'     => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}