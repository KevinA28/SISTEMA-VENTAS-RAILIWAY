<?php
namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\EstadoReserva;

class DashboardController extends Controller
{
    public function index()
    {
        $reservasHoy = Reserva::whereDate('created_at', today())->count();

        $pagadas = Reserva::whereHas('estado', fn($q) => $q->where('nombre', 'pagado'))
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalClientes = Cliente::count();

        $ultimasReservas = Reserva::with(['cliente', 'estado', 'fechaTour.tour'])
            ->latest()->limit(8)->get();

        $estadosResumen = EstadoReserva::withCount('reservas')
            ->get()
            ->map(fn($e) => [
                'nombre' => $e->nombre,
                'label'  => ucfirst(str_replace('_', ' ', $e->nombre)),
                'cnt'    => $e->reservas_count,
                'color'  => match($e->nombre) {
                    'confirmada'  => '#059669',
                    'mitad_pago'  => '#1d4ed8',
                    'pagado'      => '#15803d',
                    'cancelada'   => '#dc2626',
                    'pre_reserva' => '#d97706',
                    'finalizada'  => '#7c3aed',
                    default       => '#9ca3af',
                },
            ]);

        return view('dashboard', compact(
            'reservasHoy', 'pagadas', 'totalClientes',
            'ultimasReservas', 'estadosResumen'
        ));
    }
}