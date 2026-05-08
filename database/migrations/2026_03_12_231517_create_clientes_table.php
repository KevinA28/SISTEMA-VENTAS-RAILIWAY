<?php
// UBICACIÓN: database/migrations/2026_03_12_000002_create_clientes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            // ── Documento ──────────────────────────────────────────────
            $table->enum('tipo_documento', ['DNI', 'RUC', 'CE', 'PASAPORTE'])->nullable();
            $table->string('numero_documento', 20)->nullable()->unique();

            // ── Datos personales ───────────────────────────────────────
            $table->string('nombre_completo');
            $table->string('razon_social')->nullable();          // para RUC/empresa
            $table->string('direccion_fiscal')->nullable();

            // ── Datos que necesita el service al crear cliente ─────────
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['M', 'F', 'otro'])->nullable();
            $table->string('nacionalidad', 80)->nullable()->default('Peruana');

            // ── Contacto ───────────────────────────────────────────────
            $table->string('telefono', 20)->nullable();          // teléfono / WhatsApp principal
            $table->string('telefono2', 20)->nullable();         // teléfono secundario
            $table->string('email')->nullable();

            // ── Contacto de emergencia ─────────────────────────────────
            $table->string('emergencia_nombre', 200)->nullable();
            $table->string('emergencia_parentesco', 60)->nullable();
            $table->string('emergencia_telefono', 20)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};