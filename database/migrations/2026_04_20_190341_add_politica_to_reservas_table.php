<?php
// =====================================================================
// ARCHIVO: 2026_04_20_190341_add_politica_to_reservas_table.php
// UBICACIÓN: database/migrations/
// =====================================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->text('politica_descripcion')
                  ->nullable()
                  ->after('observaciones');

            $table->string('politica_tipo', 20)
                  ->nullable()
                  ->after('politica_descripcion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn([
                'politica_descripcion',
                'politica_tipo'
            ]);
        });
    }
};