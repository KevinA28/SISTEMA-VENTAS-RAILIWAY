<?php
// =====================================================================
// ARCHIVO: CiudadController.php
// UBICACIÓN: app/Http/Controllers/CiudadController.php
// =====================================================================

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CiudadController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('ubigeo_peru');

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('distrito',      'like', '%' . $b . '%')
                  ->orWhere('provincia',   'like', '%' . $b . '%')
                  ->orWhere('departamento','like', '%' . $b . '%');
            });
        }
        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }

        $ciudades = $query
            ->orderBy('departamento')
            ->orderBy('provincia')
            ->orderBy('distrito')
            ->paginate(30)
            ->withQueryString();

        $departamentos = DB::table('ubigeo_peru')
            ->distinct()
            ->orderBy('departamento')
            ->pluck('departamento');

        return view('ciudades.index', compact('ciudades', 'departamentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'departamento'  => 'required|string|max:100',
            'provincia'     => 'required|string|max:100',
            'distrito'      => 'required|string|max:100',
            'codigo_ubigeo' => 'nullable|string|max:10',
        ]);

        // Evitar duplicados exactos
        $existe = DB::table('ubigeo_peru')
            ->where('departamento', $data['departamento'])
            ->where('provincia',    $data['provincia'])
            ->where('distrito',     $data['distrito'])
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe ese distrito en la provincia y departamento indicados.');
        }

        DB::table('ubigeo_peru')->insert([
            'departamento'  => ucwords(strtolower(trim($data['departamento']))),
            'provincia'     => ucwords(strtolower(trim($data['provincia']))),
            'distrito'      => ucwords(strtolower(trim($data['distrito']))),
            'codigo_ubigeo' => $data['codigo_ubigeo'] ?? null,
        ]);

        return back()->with('success', 'Ciudad/distrito "' . $data['distrito'] . '" agregado correctamente.');
    }

    public function destroy(int $id)
    {
        DB::table('ubigeo_peru')->where('id', $id)->delete();
        return back()->with('success', 'Registro eliminado.');
    }
}