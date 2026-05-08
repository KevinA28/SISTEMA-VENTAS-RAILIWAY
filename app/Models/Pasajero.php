<?php
// UBICACIÓN: app/Models/Pasajero.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasajero extends Model
{
    protected $fillable = [
        'reserva_id',
        'nombre_completo',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'edad',
        'tipo',
        'es_titular',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'es_titular'       => 'boolean',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function salud()
    {
        return $this->hasOne(SaludPasajero::class);
    }
}