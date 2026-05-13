<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    if (!Schema::hasTable('salud_pasajero')) {
        Schema::create('salud_pasajero', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasajero_id')->constrained('pasajeros')->cascadeOnDelete();
            $table->text('alergias')->nullable();
            $table->text('restricciones_alimentarias')->nullable();
            $table->text('condiciones_medicas')->nullable();
            $table->text('medicamentos')->nullable();
            $table->string('discapacidades')->nullable();
            $table->string('discapacidad_otro')->nullable();
            $table->string('seguro_salud')->nullable();
            $table->timestamps();
        });
    }
}
    public function down(): void
    {
        Schema::dropIfExists('salud_pasajero');
    }
};