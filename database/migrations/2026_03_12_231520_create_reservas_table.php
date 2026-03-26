<?php
// =====================================================================
// ARCHIVO: 2026_03_26_000001_add_campos_formulario_to_reservas_table.php
// UBICACIÓN: database/migrations/
// =====================================================================
// Agrega los campos nuevos del formulario completo:
// ciudad_procedencia, tipo_comprobante, ruc_factura,
// razon_social, punto_encuentro, hora_recojo
// =====================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('ciudad_procedencia', 100)->nullable()->after('canal_contacto');
            $table->enum('tipo_comprobante', ['boleta', 'factura'])->default('boleta')->after('ciudad_procedencia');
            $table->string('ruc_factura', 11)->nullable()->after('tipo_comprobante');
            $table->string('razon_social', 200)->nullable()->after('ruc_factura');
            $table->string('punto_encuentro', 300)->nullable()->after('razon_social');
            $table->time('hora_recojo')->nullable()->after('punto_encuentro');
        });

        // También agregar alergias y restricciones al titular
        // (los pasajeros ya tienen salud_pasajero, esto es para el titular)
        Schema::table('reservas', function (Blueprint $table) {
            $table->text('alergias_titular')->nullable()->after('hora_recojo');
            $table->text('restricciones_alimentarias_titular')->nullable()->after('alergias_titular');
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'ciudad_procedencia',
                'tipo_comprobante',
                'ruc_factura',
                'razon_social',
                'punto_encuentro',
                'hora_recojo',
                'alergias_titular',
                'restricciones_alimentarias_titular',
            ]);
        });
    }
};