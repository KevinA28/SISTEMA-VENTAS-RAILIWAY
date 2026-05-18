<?php
// =====================================================================
// ARCHIVO: DatabaseSeeder.php
// UBICACIÓN: database/seeders/DatabaseSeeder.php
// =====================================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Estados de reserva
        $estados = [
                 ['nombre' => 'mitad_pago',        'color_hex' => '#f59e0b'],
                 ['nombre' => 'otros_porcentajes', 'color_hex' => '#8b5cf6'],
                 ['nombre' => 'pagado',            'color_hex' => '#10b981'],
                 ['nombre' => 'cancelada',         'color_hex' => '#ef4444'],
                 ];
        foreach ($estados as $e) {
            DB::table('estados_reserva')->insertOrIgnore([...$e, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Métodos de pago
        
        $this->call(MetodoPagoSeeder::class);

        // Usuario administrador inicial
        DB::table('usuarios_admin')->insertOrIgnore([
            'nombre'     => 'Admin',
            'apellido'   => 'ADVENTUR',
            'email'      => 'admin@adventur.pe',
            'password'   => Hash::make('password'),
            'rol'        => 'administrador',
            'activo'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tours de ejemplo — Cajamarca
        $tours = [
            ['nombre' => 'Cumbemayo',            'precio_adulto' => 35.00, 'precio_nino' => 20.00, 'duracion_horas' => 4],
            ['nombre' => 'Baños del Inca',        'precio_adulto' => 25.00, 'precio_nino' => 15.00, 'duracion_horas' => 3],
            ['nombre' => 'Ventanillas de Otuzco', 'precio_adulto' => 30.00, 'precio_nino' => 18.00, 'duracion_horas' => 5],
            ['nombre' => 'Granja Porcón',         'precio_adulto' => 40.00, 'precio_nino' => 25.00, 'duracion_horas' => 6],
        ];
        foreach ($tours as $t) {
            DB::table('tours')->insertOrIgnore([...$t, 'activo' => true, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}