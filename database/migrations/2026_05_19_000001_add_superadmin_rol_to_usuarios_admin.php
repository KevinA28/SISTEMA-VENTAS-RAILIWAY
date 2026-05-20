<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE usuarios_admin MODIFY COLUMN rol ENUM('administrador','ventas','superadmin') NOT NULL DEFAULT 'ventas'");
    }

    public function down(): void
    {
        DB::statement("UPDATE usuarios_admin SET rol = 'administrador' WHERE rol = 'superadmin'");
        DB::statement("ALTER TABLE usuarios_admin MODIFY COLUMN rol ENUM('administrador','ventas') NOT NULL DEFAULT 'ventas'");
    }
};
