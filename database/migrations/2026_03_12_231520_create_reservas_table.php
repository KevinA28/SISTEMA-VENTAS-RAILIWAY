<?php
// UBICACIÓN: database/migrations/2026_03_12_000005_create_reservas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();

            // ── Identificación ─────────────────────────────────────────
            $table->string('codigo_reserva', 20)->unique();

            // ── Tour / servicio ────────────────────────────────────────
            $table->string('nombre_tour', 200)->nullable();
            $table->date('fecha_tour')->nullable();
            $table->time('hora_salida')->nullable();

            // ── Relaciones ─────────────────────────────────────────────
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('fecha_tour_id')->nullable()->constrained('fechas_tour')->nullOnDelete();
            $table->foreignId('estado_id')->constrained('estados_reserva');
            $table->foreignId('usuario_admin_id')->nullable()->constrained('usuarios_admin')->nullOnDelete();
            $table->string('registrado_por_nombre', 150)->nullable();

            // ── Pasajeros y precio ─────────────────────────────────────
            $table->unsignedSmallInteger('cantidad_adultos')->default(1);
            $table->unsignedSmallInteger('cantidad_ninos')->default(0);
            $table->decimal('precio_total', 10, 2)->default(0);
            $table->decimal('monto_pagado', 10, 2)->default(0);

            // ── Comercial ──────────────────────────────────────────────
            $table->enum('canal_contacto', ['whatsapp','presencial','llamada','redes_sociales','web','referido'])->default('whatsapp');
            $table->string('ciudad_procedencia', 100)->nullable();

            // ── Destino ────────────────────────────────────────────────
            $table->string('ciudad_destino', 150)->nullable();
            $table->string('departamento_destino', 100)->nullable();

            // ── Fechas del viaje ───────────────────────────────────────
            // Guardadas como date (pueden ser null si el usuario no las completó)
            $table->date('fecha_arribo')->nullable();
            $table->date('fecha_retorno')->nullable();
            $table->string('dias_viaje', 20)->nullable();        // ej: "5 días"
            $table->time('hora_arribo')->nullable();
            $table->time('hora_retorno')->nullable();

            // ── Transporte ─────────────────────────────────────────────
            $table->enum('tipo_transporte', ['terrestre', 'aereo'])->nullable();
            $table->string('empresa_transporte', 150)->nullable();
            // Aéreo
            $table->string('aerolinea', 150)->nullable();
            $table->string('numero_vuelo', 20)->nullable();
            $table->time('hora_salida_vuelo')->nullable();
            $table->time('hora_llegada_vuelo')->nullable();

            // ── Hospedaje ──────────────────────────────────────────────
            $table->string('nombre_hotel', 200)->nullable();
            $table->string('tipo_establecimiento', 50)->nullable();
            $table->string('tipo_habitacion', 300)->nullable();  // acumulable: "DBL x1, TWN x1"
            $table->string('tipo_cama', 10)->nullable();         // KB, QB, TB
            $table->string('plan_alimentacion', 10)->nullable(); // RO, BB, HB, FB, AI

            // ── Comprobante ────────────────────────────────────────────
            $table->enum('tipo_comprobante', ['boleta', 'factura'])->default('boleta');
            $table->string('ruc_factura', 11)->nullable();
            $table->string('razon_social', 200)->nullable();

            // ── Logística ──────────────────────────────────────────────
            $table->string('punto_encuentro', 300)->nullable();
            $table->time('hora_recojo')->nullable();

            // ── Salud del titular ──────────────────────────────────────
            $table->text('alergias_titular')->nullable();
            $table->text('restricciones_alimentarias_titular')->nullable();
            $table->text('titular_obs_medicas')->nullable();

            // ── Políticas ──────────────────────────────────────────────
            $table->text('politica_descripcion')->nullable();
            $table->string('politica_tipo', 20)->nullable();

            // ── Misc ───────────────────────────────────────────────────
            
            $table->text('observaciones')->nullable();
            $table->string('email_contacto', 200)->nullable();      // ← AGREGAR
            $table->boolean('notificacion_enviada')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};