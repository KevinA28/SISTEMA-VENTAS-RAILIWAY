<?php
// UBICACIÓN: app/Models/Cliente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombre_completo',
        'razon_social',
        'direccion_fiscal',
        'fecha_nacimiento',
        'genero',
        'nacionalidad',
        'telefono',
        'telefono2',
        'email',
        'emergencia_nombre',
        'emergencia_parentesco',
        'emergencia_telefono',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function getNombreMostrarAttribute(): string
    {
        return $this->razon_social ?? $this->nombre_completo;
    }

    public function getEsEmpresaAttribute(): bool
    {
        return $this->tipo_documento === 'RUC';
    }
}