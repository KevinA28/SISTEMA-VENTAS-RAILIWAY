<?php
// =====================================================================
// ARCHIVO: Tour.php
// UBICACIÓN: app/Models/Tour.php
// =====================================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'nombre', 'categoria', 'veces_usado',
        'descripcion', 'precio_adulto',
        'precio_nino', 'duracion_horas', 'activo',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'precio_adulto' => 'decimal:2',
        'precio_nino'   => 'decimal:2',
        'veces_usado'   => 'integer',
    ];

    public function fechas()
    {
        return $this->hasMany(FechaTour::class);
    }

    public function fechasDisponibles()
    {
        return $this->hasMany(FechaTour::class)
            ->where('estado', 'disponible')
            ->where('fecha', '>=', now()->toDateString())
            ->orderBy('fecha');
    }

    // Scope para búsqueda de autocomplete
    public function scopeBuscar($query, string $texto)
    {
        return $query->where('activo', true)
                     ->where('nombre', 'LIKE', '%' . $texto . '%')
                     ->orderByDesc('veces_usado')
                     ->orderBy('nombre');
    }

    // Scope por categoría
    public function scopeDeCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    // Incrementar contador cuando se usa en una reserva
    public function registrarUso(): void
    {
        $this->increment('veces_usado');
    }

    // Categorías válidas como constante
    const CATEGORIAS = [
        'full_day'       => 'Full Day',
        'half_day'       => 'Half Day',
        'nacional'       => 'Nacional',
        'internacional'  => 'Internacional',
        'escolar'        => 'Viaje Escolar',
        'crucero'        => 'Crucero',
        'fecha_festiva'  => 'Fecha Festiva',
    ];
}