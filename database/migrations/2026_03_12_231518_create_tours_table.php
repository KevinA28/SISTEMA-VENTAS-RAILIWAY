<?php
// UBICACIÓN: database/migrations/2026_03_12_000003_create_tours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('categoria')->default('nacional');
            $table->unsignedInteger('veces_usado')->default(0);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_adulto', 8, 2)->nullable();
            $table->decimal('precio_nino', 8, 2)->nullable();
            $table->integer('duracion_horas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('fechas_tour', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_salida');
            $table->integer('cupo_total');
            $table->integer('cupo_disponible');
            $table->enum('estado', ['disponible', 'lleno', 'cancelado'])->default('disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechas_tour');
        Schema::dropIfExists('tours');
    }
};