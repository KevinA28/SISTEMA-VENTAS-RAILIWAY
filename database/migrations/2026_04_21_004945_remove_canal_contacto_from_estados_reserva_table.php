<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::table('estados_reserva', function (Blueprint $table) {
        if (Schema::hasColumn('estados_reserva', 'canal_contacto')) {
            $table->dropColumn('canal_contacto');
        }
    });
}

    public function down(): void
    {
        Schema::table('estados_reserva', function (Blueprint $table) {
            $table->string('canal_contacto')->nullable();
        });
    }
};