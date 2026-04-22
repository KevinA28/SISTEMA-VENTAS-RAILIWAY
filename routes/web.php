<?php
 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClienteController;   // ← faltaba este use
use Illuminate\Support\Facades\Route;
 
Route::get('/', function () {
    return view('welcome');
});
 
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('reservas', ReservaController::class);
    Route::post('reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado'])
         ->name('reservas.cambiarEstado');
    
    // ── Pagos ──
    Route::post('pagos', [\App\Http\Controllers\PagoController::class, 'store'])
         ->name('pagos.store');
    Route::patch('pagos/{pago}/verificar', [\App\Http\Controllers\PagoController::class, 'verificar'])
         ->name('pagos.verificar');

    Route::resource('clientes', ClienteController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
 