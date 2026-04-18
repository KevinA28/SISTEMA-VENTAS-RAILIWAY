<?php
// =====================================================================
// ARCHIVO: add_campos_manuales_to_reservas_table.php
// UBICACIÓN: database/migrations/
// =====================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('nombre_tour')->nullable()->after('codigo_reserva');
            $table->date('fecha_tour')->nullable()->after('nombre_tour');
            $table->time('hora_salida')->nullable()->after('fecha_tour');
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['nombre_tour', 'fecha_tour', 'hora_salida']);
        });
    }
};