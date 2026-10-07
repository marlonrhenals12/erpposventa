<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function (Request $request) {
    if ($request->user()) {
        if ($request->user()->hasRole('superadmin')) {
            return redirect()->route('dashboard');
        }
        if ($request->user()->hasRole('vendedor')) {
            return redirect()->route('ventas.index');
        }
    }
    return redirect()->route('login');
});

// Panel Principal: exclusivo para superadmin
Route::get('/dashboard', function (Request $request) {
    if ($request->user()->hasRole('vendedor') && ! $request->user()->hasRole('superadmin')) {
        return redirect()->route('ventas.index');
    }
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Módulo de Ventas: accesible para vendedor y superadmin
Route::get('/ventas', function () {
    return Inertia::render('Ventas/Index');
})->middleware(['auth', 'verified', 'role:vendedor,superadmin'])->name('ventas.index');

// Módulo de Configuración: Exclusivo para superadmin (CRUD de Usuarios y Roles)
Route::middleware(['auth', 'verified', 'role:superadmin'])->prefix('configuracion')->name('configuracion.')->group(function () {
    Route::get('/roles-usuarios', [UserController::class, 'index'])->name('roles.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
