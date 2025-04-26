<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ElementosRtoController;
use App\Http\Controllers\Observaciones;
use App\Http\Controllers\Reclamos;
use App\Http\Controllers\RtoController;
use App\Http\Controllers\Usuarios;
use App\Http\Controllers\Proveedores;
use App\Http\Controllers\Informes;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\RtoDetalleController;

// Crear usuario admin (Solo usar una vez)
// Route::get('/crear-admin',[AuthController::class, 'crearAdmin'])->name('crear-admin');

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/loguear', [AuthController::class, 'loguear'])->name('loguear');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [Dashboard::class, 'index'])->name('home');
});



Route::prefix('remitos')->middleware('auth')->group(function () {
    Route::get('/', [RtoController::class, 'index'])->name('remitos');
    Route::post('/store', [RtoController::class, 'store'])->name('remitos.store');
    Route::get('/edit/{id}', [RtoController::class, 'edit'])->name('remitos.edit');
    Route::post('/storeElementoRto', [ElementosRtoController::class, 'storeElementoRto'])->name('storeElementoRto');
    Route::post('/storeRtoDetalle', [RtoDetalleController::class, 'store'])->name('storeRtoDetalle.store');
    Route::post('actualizarCampo', [RtoDetalleController::class, 'actualizarCampo'])->name('actualizarCampo');
    Route::get('obtenerValor/{id}/{field}', [RtoDetalleController::class, 'obtenerValor'])->name('obtenerValor');
    Route::post('/deleteRtoDetalle/{id}', [RtoDetalleController::class, 'delete'])->name('deleteRtoDetalle');
    Route::get('pendientes', [RtoController::class, 'pendientes'])->name('remitos.pendientes');
    Route::post('/actualizarEstado/{id}' , [RtoController::class, 'actualizarEstado'])->name('actualizarEstado');
    Route::post('/remitos/actualizar/{id}', [RtoController::class, 'actualizar'])->name('actualizarRemito');
});

route::prefix('reclamos')->middleware('auth')->group(function () {
    Route::get('/', [Reclamos::class, 'index'])->name('reclamos');
    Route::post('/reclamos', [Reclamos::class, 'store'])->name('reclamos.store');
    Route::put('/reclamos/{id}', [Reclamos::class, 'update'])->name('reclamos.update');
    Route::get('/show/{rtoId?}', [Reclamos::class, 'show'])->name('reclamos.show');
    Route::delete('/destroy/{id}', [Reclamos::class, 'destroy'])->name('reclamos.destroy');
});

route::prefix('observaciones')->middleware('auth')->group(function () {
    Route::get('/', [Observaciones::class, 'index'])->name('observaciones');
    Route::post('/store', [Observaciones::class, 'store'])->name('observaciones.store');
    Route::get('/show/{remito}', [Observaciones::class, 'show'])->name('observaciones.show');
    Route::put('/update/{id}', [Observaciones::class, 'update'])->name('observaciones.update');
    Route::post('/destroy/{id}', [Observaciones::class, 'destroy'])->name('observaciones.destroy');
});

Route::prefix('usuarios')->middleware(['auth', 'Checkrol:admin'])->group(function () {
    Route::get('/', [Usuarios::class, 'index'])->name('usuarios');
    Route::get('/create', [Usuarios::class, 'create'])->name('usuarios.create');
    Route::post('/store', [Usuarios::class, 'store'])->name('usuarios.store');
    Route::get('/edit/{id}', [Usuarios::class, 'edit'])->name('usuarios.edit');
    Route::put('/update/{id}', [Usuarios::class, 'update'])->name('usuarios.update');
    Route::post('/estado/{id}', [Usuarios::class, 'estado'])->name('usuarios.estado');
    Route::delete('/destroy/{id}', [Usuarios::class, 'destroy'])->name('usuarios.destroy');
});

Route::prefix('proveedores')->middleware(['auth', 'Checkrol:admin'])->group(function () {
    Route::get('/', [Proveedores::class, 'index'])->name('proveedores');
    Route::get('/create', [Proveedores::class, 'create'])->name('proveedores.create');
    Route::post('/store', [Proveedores::class, 'store'])->name('proveedores.store');
    Route::get('/edit/{id}', [Proveedores::class, 'edit'])->name('proveedores.edit');
    Route::put('/update/{id}', [Proveedores::class, 'update'])->name('proveedores.update');
    Route::post('/estado/{id}', [Proveedores::class, 'estado'])->name('proveedores.estado');   

    Route::get('/camiones', [Proveedores::class, 'indexCamiones'])->name('proveedores.camiones');
    Route::get('/camiones/create', [Proveedores::class, 'createCamiones'])->name('proveedores.camiones.create');
    Route::post('/camiones/store', [Proveedores::class, 'storeCamiones'])->name('proveedores.camiones.store');
    Route::get('/camiones/edit/{id}', [Proveedores::class, 'editCamiones'])->name('proveedores.camiones.edit');
    Route::put('/camiones/update/{id}', [Proveedores::class, 'updateCamiones'])->name('proveedores.camiones.update');

    Route::get('/{id}/camiones', [Proveedores::class, 'getCamiones']);

});

Route::prefix('informes')->middleware(['auth', 'Checkrol:admin'])->group(function () {
    Route::get('/', [Informes::class, 'index'])->name('informes');
});

Route::prefix('productos')->middleware(['auth', 'Checkrol:admin'])->group(function () {
    Route::get('/', [ProductosController::class, 'index'])->name('productos');
});