<?php
// =====================================================================
// ARCHIVO: Reserva.php
// UBICACIÓN: app/Models/Reserva.php
// =====================================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'codigo_reserva',
        'cliente_id',
        'fecha_tour_id',
        'estado_id',
        'usuario_admin_id',
        'cantidad_adultos',
        'cantidad_ninos',
        'precio_total',
        'monto_pagado',
        'canal_contacto',
        'ciudad_procedencia',
        'tipo_comprobante',
        'ruc_factura',
        'razon_social',
        'punto_encuentro',
        'hora_recojo',
        'alergias_titular',
        'restricciones_alimentarias_titular',
        'observaciones',
        // campos de entrada manual
        'nombre_tour',
        'fecha_tour',
        'hora_salida',
        'politica_descripcion',
        'politica_tipo',
    ];

    protected $casts = [
        'precio_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'hora_recojo'  => 'datetime:H:i',
        'fecha_tour'   => 'date',
    ];

    public function cliente()      { return $this->belongsTo(Cliente::class); }
    public function fechaTour()    { return $this->belongsTo(FechaTour::class); }
    public function estado()       { return $this->belongsTo(EstadoReserva::class, 'estado_id'); }
    public function usuarioAdmin() { return $this->belongsTo(UsuarioAdmin::class, 'usuario_admin_id'); }
    public function pasajeros()    { return $this->hasMany(Pasajero::class); }
    public function pagos()        { return $this->hasMany(Pago::class); }
    public function comprobantes() { return $this->hasMany(Comprobante::class); }
    public function logistica()    { return $this->hasOne(LogisticaReserva::class); }
    public function historialEstados() { return $this->hasMany(HistorialEstado::class); }

    public function getSaldoPendienteAttribute(): float
    {
        return $this->precio_total - $this->monto_pagado;
    }

    public function getPorcentajePagadoAttribute(): float
    {
        if ($this->precio_total <= 0) return 0;
        return round(($this->monto_pagado / $this->precio_total) * 100, 1);
    }

    public function getEsFacturaAttribute(): bool
    {
        return $this->tipo_comprobante === 'factura';
    }

    public static function generarCodigo(): string
    {
        $año    = date('Y');
        $ultimo = self::whereYear('created_at', $año)->count() + 1;
        return 'ADV-' . $año . '-' . str_pad($ultimo, 3, '0', STR_PAD_LEFT);
    }
}