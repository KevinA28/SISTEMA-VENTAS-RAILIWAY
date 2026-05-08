<?php 
// =====================================================================
// ARCHIVO: Pago.php
// UBICACIÓN: app/Models/Pago.php
// =====================================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'reserva_id',
        'metodo_pago_id',
        'registrado_por',
        'monto',
        'numero_operacion',
        'archivo_baucher',
        'tipo_pago',
        'estado_validacion',
        'fecha_pago',
        'observaciones',
    ];

    protected $casts = [
        'monto'      => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    // =========================================================
    // RELACIÓN: Pago pertenece a una reserva
    // =========================================================
    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    // =========================================================
    // RELACIÓN: Pago pertenece a un método de pago
    // =========================================================
    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class);
    }

    // =========================================================
    // RELACIÓN: Usuario administrador que registró el pago
    // =========================================================
    public function registradoPor()
    {
        return $this->belongsTo(\App\Models\UsuarioAdmin::class, 'registrado_por');
    }
}