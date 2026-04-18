<?php
namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\EstadoReserva;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPIs
        $reservasHoy    = Reserva::whereDate('created_at', today())->count();
        $confirmadas    = Reserva::whereHas('estado', fn($q) => $q->where('nombre', 'confirmada'))
                            ->whereMonth('created_at', now()->month)->count();
        $totalClientes  = Cliente::count();

        // Últimas 8 reservas
        $ultimasReservas = Reserva::with(['cliente', 'estado', 'fechaTour.tour'])
                            ->latest()->limit(8)->get();

        // Resumen por estado
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
            'reservasHoy', 'confirmadas', 'totalClientes',
            'ultimasReservas', 'estadosResumen'
        ));
    }
}