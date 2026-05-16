<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function index()
    {
        // ── Totales generales ──
        $totalReservas  = Reserva::count();
        $totalClientes  = Cliente::count();
        $totalIngresos  = Reserva::sum('precio_total');
        $totalCobrado   = Reserva::sum('monto_pagado');
        $totalPendiente = $totalIngresos - $totalCobrado;

        // ── Reservas por mes (últimos 12 meses) ──
        $reservasPorMes = Reserva::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as mes, COUNT(*) as total, SUM(precio_total) as ingresos")
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // ── Reservas por estado ──
        $reservasPorEstado = Reserva::select('estado_id', DB::raw('COUNT(*) as total'))
            ->with('estado')
            ->groupBy('estado_id')
            ->get()
            ->map(fn($r) => [
                'nombre' => $r->estado->nombre ?? 'Sin estado',
                'total'  => $r->total,
                'color'  => $r->estado->color_hex ?? '#6b7280',
            ]);

        // ── Top 10 tours más reservados ──
        $topTours = Reserva::select('nombre_tour', DB::raw('COUNT(*) as total, SUM(precio_total) as ingresos'))
            ->whereNotNull('nombre_tour')
            ->groupBy('nombre_tour')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Reservas por canal ──
        $reservasPorCanal = Reserva::select('canal_contacto', DB::raw('COUNT(*) as total'))
            ->whereNotNull('canal_contacto')
            ->groupBy('canal_contacto')
            ->orderByDesc('total')
            ->get();

        // ── Procedencia de clientes ──
        $clientesPorProcedencia = Reserva::select('ciudad_procedencia', DB::raw('COUNT(*) as total'))
            ->whereNotNull('ciudad_procedencia')
            ->groupBy('ciudad_procedencia')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Top destinos ──
        $topDestinos = Reserva::select('departamento_destino', DB::raw('COUNT(*) as total'))
            ->whereNotNull('departamento_destino')
            ->groupBy('departamento_destino')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Clientes VIP ──
        $clientesVip = Cliente::withCount('reservas')
            ->having('reservas_count', '>=', 3)
            ->orderByDesc('reservas_count')
            ->limit(5)
            ->get();

        // ── Reservas esta semana vs semana pasada ──
        $estaSemana   = Reserva::where('created_at', '>=', now()->startOfWeek())->count();
        $semanaPasada = Reserva::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        return view('estadisticas.index', compact(
            'totalReservas', 'totalClientes', 'totalIngresos',
            'totalCobrado', 'totalPendiente',
            'reservasPorMes', 'reservasPorEstado',
            'topTours', 'reservasPorCanal',
            'clientesPorProcedencia', 'topDestinos',
            'clientesVip', 'estaSemana', 'semanaPasada'
        ));
    }
}