<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('reservas', ReservaController::class);
    Route::post('reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado'])
         ->name('reservas.cambiarEstado');

    Route::resource('clientes', ClienteController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

// API AJAX — SUNAT / RENIEC (fuera de auth para que funcione el formulario)
Route::get('/api/buscar-dni/{dni}', [ClienteController::class, 'buscarDni']);
Route::get('/api/buscar-ruc/{ruc}', [ClienteController::class, 'buscarRuc']);

require __DIR__.'/auth.php';