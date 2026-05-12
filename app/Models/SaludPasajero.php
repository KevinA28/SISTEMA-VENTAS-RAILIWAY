<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaludPasajero extends Model
{
    protected $table = 'salud_pasajero';

    protected $fillable = [
        'pasajero_id',
        'alergias',
        'restricciones_alimentarias',
        'condiciones_medicas',
        'medicamentos',
        'discapacidades',
        'discapacidad_otro',
        'seguro_salud',
    ];

    public function pasajero()
    {
        return $this->belongsTo(Pasajero::class);
    }
}