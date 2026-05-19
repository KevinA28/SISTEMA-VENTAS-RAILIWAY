<?php
// =====================================================================
// ARCHIVO: TourController.php
// UBICACIÓN: app/Http/Controllers/TourController.php
// =====================================================================

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::query();

        if ($request->buscar) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('activo')) {
            $query->where('activo', $request->activo);
        }

        $tours = $query->orderBy('nombre')->paginate(20)->withQueryString();

        $totalTours   = Tour::count();
        $totalActivos = Tour::where('activo', true)->count();
        $totalUsos    = Tour::sum('veces_usado');

        return view('tours.index', compact('tours', 'totalTours', 'totalActivos', 'totalUsos'));
    }

    public function create()
    {
        $categorias = Tour::CATEGORIAS;
        return view('tours.create', compact('categorias'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'nombre'         => 'required|string|max:200|unique:tours,nombre',
        'categoria'      => 'required|in:' . implode(',', array_keys(Tour::CATEGORIAS)),
        'descripcion'    => 'nullable|string|max:1000',
        'precio_adulto'  => 'nullable|numeric|min:0',
        'precio_nino'    => 'nullable|numeric|min:0',
        'duracion_horas' => 'nullable|integer|min:1|max:72',
        'activo'         => 'nullable|boolean',
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max'      => 'El nombre no puede superar 200 caracteres.',
        'nombre.unique'   => 'Ya existe un tour con ese nombre.',
        'categoria.required' => 'La categoría es obligatoria.',
        'categoria.in'       => 'La categoría seleccionada no es válida.',
        'precio_adulto.numeric' => 'El precio adulto debe ser un número.',
        'precio_nino.numeric'   => 'El precio niño debe ser un número.',
        'duracion_horas.integer' => 'La duración debe ser un número entero.',
        'duracion_horas.max'     => 'La duración máxima es 72 horas.',
    ]);

    $data['activo']      = $request->boolean('activo', true);
    $data['veces_usado'] = 0;

    Tour::create($data);

    return redirect()->route('admin.tours.index')
        ->with('success', 'Tour "' . $data['nombre'] . '" creado correctamente.');
}

    public function edit(Tour $tour)
    {
        $categorias = Tour::CATEGORIAS;
        return view('tours.edit', compact('tour', 'categorias'));
    }

    public function update(Request $request, Tour $tour)
{
    $data = $request->validate([
        'nombre'         => [
            'required', 'string', 'max:200',
            \Illuminate\Validation\Rule::unique('tours', 'nombre')->ignore($tour->id),
        ],
        'categoria'      => 'required|in:' . implode(',', array_keys(Tour::CATEGORIAS)),
        'descripcion'    => 'nullable|string|max:1000',
        'precio_adulto'  => 'nullable|numeric|min:0',
        'precio_nino'    => 'nullable|numeric|min:0',
        'duracion_horas' => 'nullable|integer|min:1|max:72',
        'activo'         => 'nullable|boolean',
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max'      => 'El nombre no puede superar 200 caracteres.',
        'nombre.unique'   => 'Ya existe un tour con ese nombre.',
        'categoria.required' => 'La categoría es obligatoria.',
        'categoria.in'       => 'La categoría seleccionada no es válida.',
        'precio_adulto.numeric' => 'El precio adulto debe ser un número.',
        'precio_nino.numeric'   => 'El precio niño debe ser un número.',
        'duracion_horas.integer' => 'La duración debe ser un número entero.',
        'duracion_horas.max'     => 'La duración máxima es 72 horas.',
    ]);

    $data['activo'] = $request->boolean('activo', true);
    $tour->update($data);

    return redirect()->route('admin.tours.index')
        ->with('success', 'Tour actualizado correctamente.');
}

    public function destroy(Tour $tour)
    {
        if ($tour->fechas()->count() > 0) {
            return back()->with('error', 'No se puede eliminar: el tour tiene fechas programadas. Desactívalo en su lugar.');
        }

        $nombre = $tour->nombre;
        $tour->delete();

        return redirect()->route('admin.tours.index')
            ->with('success', 'Tour "' . $nombre . '" eliminado.');
    }

    public function toggleActivo(Tour $tour)
    {
        $tour->update(['activo' => !$tour->activo]);
        $estado = $tour->fresh()->activo ? 'activado' : 'desactivado';
        return back()->with('success', 'Tour ' . $estado . ' correctamente.');
    }
}