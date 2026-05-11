<?php
// =====================================================================
// ARCHIVO: 2026_03_12_000006_create_pasajeros_table.php
// UBICACIÓN: database/migrations/
// CAMBIOS: agrega discapacidades y seguro_salud a salud_pasajero
// =====================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasajeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();

            $table->string('nombre_completo', 200);
            $table->enum('tipo_documento', ['DNI', 'CE', 'PASAPORTE'])->nullable();
            $table->string('numero_documento', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedTinyInteger('edad')->nullable();
            $table->enum('tipo', ['adulto', 'nino', 'bebe', 'adolescente', 'adulto_mayor'])->default('adulto');
            $table->boolean('es_titular')->default(false);

            $table->timestamps();
        });

        Schema::create('salud_pasajero', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasajero_id')->constrained('pasajeros')->cascadeOnDelete();

            // Campos originales
            $table->text('alergias')->nullable();
            $table->text('restricciones_alimentarias')->nullable();
            $table->text('condiciones_medicas')->nullable();
            $table->text('medicamentos')->nullable();

            // Campos nuevos
            $table->string('discapacidades', 200)->nullable();   // ej: "motora,visual"
            $table->string('discapacidad_otro', 100)->nullable(); // si eligió "otro"
            $table->string('seguro_salud', 50)->nullable();       // essalud, sis, eps, ffaa, otro

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salud_pasajero');
        Schema::dropIfExists('pasajeros');
    }
};