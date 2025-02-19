<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

// Ruta principal - Vista de login
// Route::view('/', 'auth.login')->middleware('guest')->name('login');
Route::view('/', 'pages.auth.login')
    ->middleware('guest')
    ->name('login');

// Dashboard y rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard principal
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    // Perfil de usuario
    Route::view('profile', 'profile')
        ->name('profile');

    // Rutas para Proveedores
    Route::middleware(['permission:gestionar-proveedores'])->group(function () {
        Route::view('proveedores', 'proveedores.index')->name('proveedores.index');
    });

    // Rutas para Camiones
    Route::middleware(['permission:gestionar-camiones'])->group(function () {
        Route::view('camiones', 'camiones.index')->name('camiones.index');
    });

    // Rutas para RTO
    Route::middleware(['permission:gestionar-rto'])->group(function () {
        Route::view('rto', 'rto.index')->name('rto.index');
    });

    // Rutas para Usuarios (solo admin)
    Route::middleware(['role:admin'])->group(function () {
        Route::view('usuarios', 'usuarios.index')->name('usuarios.index');
    });

    // Rutas para Reportes
    Route::middleware(['permission:ver-reportes'])->group(function () {
        Route::view('reportes', 'reportes.index')->name('reportes.index');
    });
});

// Mantener las rutas de autenticación
require __DIR__.'/auth.php';
