<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/ciudades/buscar', function (Request $request) {
    $q = trim($request->get('q', ''));
    if (strlen($q) < 2) return response()->json([]);

    $resultados = \Illuminate\Support\Facades\DB::table('ubigeo_peru')
        ->where('distrito', 'LIKE', '%' . $q . '%')
        ->orWhere('provincia', 'LIKE', '%' . $q . '%')
        ->select('distrito as nombre', 'provincia', 'departamento')
        ->orderByRaw("CASE WHEN distrito LIKE ? THEN 0 ELSE 1 END", [$q . '%'])
        ->limit(8)
        ->get()
        ->map(fn($r) => [
            'nombre'       => $r->nombre,
            'provincia'    => $r->provincia,
            'departamento' => $r->departamento,
        ]);

    return response()->json($resultados);
});
Route::get('/tours/sugerencias', function (Request $request) {
    $q = trim($request->get('q', ''));
    if (strlen($q) < 2) return response()->json([]);

    return \App\Models\Tour::buscar($q)
        ->limit(10)
        ->get(['nombre', 'categoria', 'veces_usado']);
});

Route::get('/buscar-dni/{dni}', function ($dni) {
    return \App\Services\Integrations\ReniecService::buscar($dni);
});

Route::get('/buscar-ruc/{ruc}', function ($ruc) {
    return \App\Services\Integrations\ReniecService::buscarRuc($ruc);
});