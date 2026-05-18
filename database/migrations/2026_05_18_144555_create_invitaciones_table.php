<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->enum('rol', ['administrador', 'ventas'])->default('ventas');
            $table->string('token', 64)->unique();
            $table->foreignId('invitado_por')->constrained('usuarios_admin')->cascadeOnDelete();
            $table->timestamp('expires_at');
            $table->timestamp('usado_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitaciones');
    }
};