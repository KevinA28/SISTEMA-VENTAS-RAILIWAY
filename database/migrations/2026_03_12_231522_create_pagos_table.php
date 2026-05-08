<?php
// UBICACIÓN: database/migrations/2026_03_12_000007_create_pagos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            // 'clave' es el valor que manda el formulario (ej: 'yape', 'transf_bcp')
            // 'nombre' es el texto legible para mostrar (ej: 'Yape', 'Transferencia BCP')
            $table->string('clave', 50)->unique();   // ← AGREGADO: para el lookup del service
            $table->string('nombre', 100);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();
            $table->foreignId('metodo_pago_id')->constrained('metodos_pago');
            $table->foreignId('registrado_por')->constrained('usuarios_admin');
            $table->decimal('monto', 10, 2);
            $table->string('numero_operacion', 100)->nullable();
            $table->string('archivo_baucher')->nullable();
            $table->enum('tipo_pago', ['adelanto', 'saldo', 'pago_completo'])->default('adelanto');
            $table->enum('estado_validacion', ['pendiente', 'verificado', 'rechazado'])->default('pendiente');
            $table->date('fecha_pago');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('metodos_pago');
    }
};