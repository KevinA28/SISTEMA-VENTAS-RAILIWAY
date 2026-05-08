<?php
// UBICACIÓN: database/migrations/2026_03_12_000004_create_estados_reserva_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados_reserva', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('etiqueta', 100)->nullable(); // nombre legible para mostrar en UI
            $table->string('color_hex', 7)->default('#6b7280');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_reserva');
    }
};