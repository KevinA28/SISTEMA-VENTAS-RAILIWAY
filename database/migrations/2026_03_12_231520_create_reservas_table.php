<?php
// =====================================================================
// ARCHIVO: 2026_03_12_231520_create_reservas_table.php
// UBICACIÓN: database/migrations/
// =====================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reserva')->unique();
            $table->string('nombre_tour')->nullable();
            $table->date('fecha_tour')->nullable();
            $table->time('hora_salida')->nullable();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignId('fecha_tour_id')->nullable()->constrained('fechas_tour')->nullOnDelete();
            $table->foreignId('estado_id')->constrained('estados_reserva');
            $table->foreignId('usuario_admin_id')->constrained('usuarios_admin');
            $table->integer('cantidad_adultos')->default(1);
            $table->integer('cantidad_ninos')->default(0);
            $table->decimal('precio_total', 10, 2)->default(0);
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->string('canal_contacto', 50)->nullable();
            $table->string('ciudad_procedencia', 100)->nullable();
            $table->enum('tipo_comprobante', ['boleta', 'factura'])->default('boleta');
            $table->string('ruc_factura', 11)->nullable();
            $table->string('razon_social', 200)->nullable();
            $table->string('punto_encuentro', 300)->nullable();
            $table->time('hora_recojo')->nullable();
            $table->text('alergias_titular')->nullable();
            $table->text('restricciones_alimentarias_titular')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('politica_descripcion')->nullable();
            $table->string('politica_tipo', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};