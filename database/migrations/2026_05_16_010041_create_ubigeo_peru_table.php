<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubigeo_peru', function (Blueprint $table) {
            $table->id();
            $table->string('departamento', 100);
            $table->string('provincia', 100);
            $table->string('distrito', 100);
            $table->string('codigo_ubigeo', 10)->nullable();
            $table->index('departamento');
            $table->index('distrito');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubigeo_peru');
    }
};