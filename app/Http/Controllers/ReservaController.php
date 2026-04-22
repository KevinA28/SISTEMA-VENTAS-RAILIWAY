<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\EstadoReserva;
use App\Models\FechaTour;
use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function __construct(private ReservaService $reservaService) {}

    public function index(Request $request)
    {
        $query = Reserva::with(['cliente', 'fechaTour.tour', 'estado'])->latest();

        if ($request->filled('estado')) {
            $query->where('estado_id', $request->estado);
        }
        if ($request->filled('canal')) {
            $query->where('canal_contacto', $request->canal);
        }
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('codigo_reserva', 'like', '%' . $request->buscar . '%')
                  ->orWhereHas('cliente', fn($c) => $c->where('nombre_completo', 'like', '%' . $request->buscar . '%'));
            });
        }

        $reservas = $query->paginate(20)->withQueryString();
        $estados  = EstadoReserva::all();

        return view('reservas.index', compact('reservas', 'estados'));
    }

    public function create()
    {
        $fechas = FechaTour::with('tour')
            ->where('estado', 'disponible')
            ->where('fecha', '>=', now())
            ->orderBy('fecha')
            ->get();
        $estados = EstadoReserva::all();

        return view('reservas.create', compact('fechas', 'estados'));
    }

    public function store(StoreReservaRequest $request)
    {
        try {
            $this->reservaService->crear($request->validated());
            return redirect()->route('reservas.index')
                ->with('success', 'Reserva creada correctamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al crear la reserva. Por favor intenta de nuevo.');
        }
    }

    public function show(Reserva $reserva)
    {
        $reserva = $this->reservaService->cargarDetalle($reserva);
        $estados = EstadoReserva::all(); // ya está importado arriba, no necesita el namespace completo

        return view('reservas.show', compact('reserva', 'estados')); // ← agregar 'estados'
    }

    public function edit(Reserva $reserva)
    {
        $reserva = $this->reservaService->cargarDetalle($reserva);

        $fechas = FechaTour::with('tour')
            ->where('estado', 'disponible')
            ->where('fecha', '>=', now())
            ->orderBy('fecha')
            ->get();
        $estados = EstadoReserva::all();

        return view('reservas.edit', compact('reserva', 'fechas', 'estados'));
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        try {
            $this->reservaService->actualizar($reserva, $request->validated());
            return redirect()->route('reservas.show', $reserva)
                ->with('success', 'Reserva actualizada correctamente.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar la reserva. Por favor intenta de nuevo.');
        }
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado_id' => 'required|exists:estados_reserva,id',
            'motivo'    => 'nullable|string|max:500',
        ]);

        $this->reservaService->cambiarEstado($reserva, $request->estado_id, $request->motivo);

        return back()->with('success', 'Estado actualizado.');
    }
}