<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroExternoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\InventarioController;

// Ruta principal (home) para usuarios autenticados
Route::get('/', [HomeController::class, 'home'])
    ->name('home')
    ->middleware('auth:externo,empleado,web');

// Rutas de autenticación (login, logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas para registro de usuarios externos
Route::get('/registro-externo', [RegistroExternoController::class, 'showRegistrationForm'])
    ->name('registro.externo');
Route::post('/registro-externo', [RegistroExternoController::class, 'register']);

// Rutas para eventos (solo superadmin)
Route::middleware(['auth:empleado'])->group(function () {
    // Crear evento (directo a tipo1)
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    
    // Editar/actualizar/eliminar eventos
    Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');
    
    // Administrar eventos
    Route::get('/eventos/admin', [EventoController::class, 'admin'])->name('eventos.admin');
});

// Rutas para catálogos
Route::middleware(['auth:externo,empleado,web'])->group(function () {
    Route::get('/catalogos', [CatalogoController::class, 'index'])->name('catalogos.index');
    Route::get('/catalogos/{evento}', [CatalogoController::class, 'show'])->name('catalogos.show');

    // Solo superadmin puede crear/editar catálogos
    Route::middleware(['auth:empleado'])->group(function () {
        Route::get('/catalogos/{evento}/create', [CatalogoController::class, 'create'])->name('catalogos.create');
        Route::post('/catalogos/{evento}', [CatalogoController::class, 'store'])->name('catalogos.store');
        Route::get('/catalogos/producto/{id}/edit', [CatalogoController::class, 'edit'])->name('catalogos.edit');
        Route::put('/catalogos/producto/{id}', [CatalogoController::class, 'update'])->name('catalogos.update');
        Route::delete('/catalogos/producto/{id}', [CatalogoController::class, 'destroy'])->name('catalogos.destroy');
    });
});

// Rutas para inscripciones (solo externos)
Route::middleware(['auth:externo'])->group(function() {
    Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');
    Route::post('/inscripciones/{eventoId}', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::get('/inscripciones/cancelar/{id}', [InscripcionController::class, 'cancelarForm'])->name('inscripciones.cancelForm');
    Route::post('/inscripciones/cancelar/{id}', [InscripcionController::class, 'cancelar'])->name('inscripciones.cancel');
    Route::get('/mis-inscripciones', [InscripcionController::class, 'misInscripciones'])->name('misInscripciones');
    Route::get('/inscripciones/mapa/{eventoId}', [InscripcionController::class, 'showMapa'])->name('inscripciones.showMapa');
    Route::post('/inscripciones/mapa/{eventoId}', [InscripcionController::class, 'storeUbicacion'])->name('inscripciones.storeUbicacion');
});

// Rutas para pedidos
Route::middleware(['auth:externo,empleado,web'])->group(function() {
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
});

Route::middleware(['auth:externo'])->group(function() {
    Route::get('/pedidos/create/{eventoId}', [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('/pedidos/{eventoId}', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos/{id}/confirmar-entrega', [PedidoController::class, 'confirmarEntrega'])->name('pedidos.confirmarEntrega');
});

Route::middleware(['auth:empleado'])->group(function() {
    Route::post('/pedidos/{id}/cambiar-estado', [PedidoController::class, 'changeStatus'])->name('pedidos.changeStatus');
    Route::get('/eventos/{evento}/pedidos', [PedidoController::class, 'porEvento'])->name('pedidos.evento');
    Route::put('/pedidos/{pedido}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');
    Route::post('/pedidos/{pedido}/evidence', [PedidoController::class, 'updateEvidence'])->name('pedidos.updateEvidence');
});

// Rutas para inventario
Route::middleware(['auth:externo,empleado'])->group(function () {
    Route::prefix('inventario')->group(function () {
        Route::get('/', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/descargar-plantilla', [InventarioController::class, 'downloadTemplate'])
             ->name('inventario.download-template');
        Route::post('/subir', [InventarioController::class, 'upload'])
             ->name('inventario.upload');
    });
});