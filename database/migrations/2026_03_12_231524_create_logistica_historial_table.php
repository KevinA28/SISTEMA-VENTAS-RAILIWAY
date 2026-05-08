<?php
// UBICACIÓN: database/migrations/2026_03_12_000009_create_logistica_historial_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logistica_reserva', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();
            $table->string('punto_encuentro', 300)->nullable();
            $table->string('direccion_recojo', 300)->nullable();
            $table->time('hora_recojo')->nullable();
            $table->string('hotel', 200)->nullable();
            $table->string('nombre_guia', 150)->nullable();
            $table->string('telefono_guia', 20)->nullable();
            $table->text('instrucciones_especiales')->nullable();
            $table->timestamps();
        });

        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();
            $table->foreignId('estado_anterior_id')->nullable()->constrained('estados_reserva')->nullOnDelete();
            $table->foreignId('estado_nuevo_id')->constrained('estados_reserva');
            $table->foreignId('cambiado_por')->constrained('usuarios_admin');
            $table->text('motivo')->nullable();
            $table->timestamp('fecha_cambio')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
        Schema::dropIfExists('logistica_reserva');
    }
};