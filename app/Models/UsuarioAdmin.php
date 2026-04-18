<?php
// =====================================================================
// ARCHIVO: UsuarioAdmin.php
// UBICACIÓN: app/Models/UsuarioAdmin.php
// =====================================================================

namespace App\Models;

// 1. Importamos la clase especial Authenticatable en lugar de Model
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// 2. Extendemos de Authenticatable
class UsuarioAdmin extends Authenticatable
{
    // 3. Agregamos los traits necesarios para autenticación
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios_admin';

    // Tus campos originales
    protected $fillable = [
        'nombre', 'apellido', 'email', 'password', 'rol', 'activo',
    ];

    // Ocultamos el password y agregamos remember_token (necesario para el "Recuérdame" del login)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tus conversiones originales + el encriptado de contraseña
    protected $casts = [
        'activo' => 'boolean',
        'password' => 'hashed',
    ];

    // ==========================================
    // TUS RELACIONES Y MÉTODOS ORIGINALES
    // ==========================================

    public function reservasRegistradas()
    {
        return $this->hasMany(Reserva::class, 'usuario_admin_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }
}