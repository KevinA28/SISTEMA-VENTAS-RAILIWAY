<?php
// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');Route::get('reservas/exportar/excel', [ReservaController::class, 'exportar'])
         ->name('reservas.exportar');
    }
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Reservas ──────────────────────────────────────────────────────
    // IMPORTANTE: la ruta 'exportar' debe ir ANTES del resource
    // para que no colisione con {reserva} de show/edit
    Route::get('reservas/exportar/excel', [ReservaController::class, 'exportar'])
         ->name('reservas.exportar');
    Route::get('/reservas/reporte-salud', [ReservaController::class, 'reporteSalud']) ->name('reservas.reporteSalud');

    Route::resource('reservas', ReservaController::class);

    Route::post('reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado'])
         ->name('reservas.cambiarEstado');

    Route::post('reservas/{reserva}/registrar-pago', [ReservaController::class, 'registrarPago'])
         ->name('reservas.registrarPago');

    Route::post('reservas/{reserva}/reenviar-notificacion', [ReservaController::class, 'reenviarNotificacion'])
         ->name('reservas.reenviarNotificacion');

    // ── Clientes ──────────────────────────────────────────────────────
    Route::resource('clientes', ClienteController::class);

    // ── Perfil ────────────────────────────────────────────────────────
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::get('/api/buscar-dni/{dni}', [ClienteController::class, 'buscarDni']);
Route::get('/api/buscar-ruc/{ruc}', [ClienteController::class, 'buscarRuc']);

require __DIR__.'/auth.php';