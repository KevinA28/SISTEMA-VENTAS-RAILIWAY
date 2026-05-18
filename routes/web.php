<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EstadisticasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Reservas ──────────────────────────────────────────────────────
    Route::get('reservas/exportar/excel', [ReservaController::class, 'exportar'])
         ->name('reservas.exportar');
    Route::get('/reservas/reporte-salud', [ReservaController::class, 'reporteSalud'])
         ->name('reservas.reporteSalud');
    Route::resource('reservas', ReservaController::class);
    Route::post('reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado'])
         ->name('reservas.cambiarEstado');
    Route::post('reservas/{reserva}/registrar-pago', [ReservaController::class, 'registrarPago'])
         ->name('reservas.registrarPago');
    Route::post('reservas/{reserva}/reenviar-notificacion', [ReservaController::class, 'reenviarNotificacion'])
         ->name('reservas.reenviarNotificacion');

    Route::get('/estadisticas', [EstadisticasController::class, 'index'])
         ->name('estadisticas.index');

    // ── Clientes ──────────────────────────────────────────────────────
    Route::resource('clientes', ClienteController::class)->only(['index','show','edit','update']);

    // ── Perfil ────────────────────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── APIs internas ─────────────────────────────────────────────────
    Route::get('/api/buscar-dni/{dni}', [ClienteController::class, 'buscarDni']);
    Route::get('/api/buscar-ruc/{ruc}', [ClienteController::class, 'buscarRuc']);

    Route::get('/api/ciudades/buscar', function (\Illuminate\Http\Request $request) {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        return \Illuminate\Support\Facades\DB::table('ubigeo_peru')
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
    });

    Route::get('/api/tours/sugerencias', function (\Illuminate\Http\Request $request) {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) return response()->json([]);

        return \App\Models\Tour::buscar($q)
            ->limit(10)
            ->get(['nombre', 'categoria', 'veces_usado']);
    });

});

// ── Panel administración ──────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/usuarios', [\App\Http\Controllers\Admin\UsuarioAdminController::class, 'index'])
         ->name('usuarios.index');
    Route::post('/usuarios/invitar', [\App\Http\Controllers\Admin\UsuarioAdminController::class, 'invitar'])
         ->name('usuarios.invitar');
    Route::patch('/usuarios/{usuario}/toggle-activo', [\App\Http\Controllers\Admin\UsuarioAdminController::class, 'toggleActivo'])
         ->name('usuarios.toggleActivo');
    Route::delete('/usuarios/{usuario}', [\App\Http\Controllers\Admin\UsuarioAdminController::class, 'destroy'])
         ->name('usuarios.destroy');
    Route::delete('/invitaciones/{invitacion}', [\App\Http\Controllers\Admin\UsuarioAdminController::class, 'cancelarInvitacion'])
         ->name('invitaciones.cancelar');
});

require __DIR__.'/auth.php';