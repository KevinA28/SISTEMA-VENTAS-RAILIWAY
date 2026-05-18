<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UsuarioAdmin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios_admin';

    protected $fillable = [
        'nombre', 'apellido', 'foto_perfil', 'email', 'password', 'rol', 'activo', 'invited_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'activo'   => 'boolean',
        'password' => 'hashed',
    ];

    public function reservasRegistradas()
    {
        return $this->hasMany(Reserva::class, 'usuario_admin_id');
    }

    public function invitadoPor()
    {
        return $this->belongsTo(UsuarioAdmin::class, 'invited_by');
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto_perfil
            ? asset('storage/' . $this->foto_perfil)
            : '';
    }
}