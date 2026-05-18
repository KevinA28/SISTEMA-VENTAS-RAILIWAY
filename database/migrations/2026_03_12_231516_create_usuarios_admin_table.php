<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios_admin', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('foto_perfil')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('rol', ['administrador', 'ventas'])->default('ventas');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->foreignId('invited_by')->nullable()->constrained('usuarios_admin')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios_admin');
    }
};