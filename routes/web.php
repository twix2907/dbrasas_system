<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TicketController;

// Rutas de autenticación
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas para cada rol
Route::middleware(['auth'])->group(function () {
    // Rutas para administrador (gestión completa)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Gestión completa de mesas para administrador
    Route::get('/mesas', [TableController::class, 'index'])->name('admin.tables');
    Route::get('/mesas/crear', [TableController::class, 'create'])->name('admin.tables.create');
    Route::post('/mesas', [TableController::class, 'store'])->name('admin.tables.store');
    Route::get('/mesas/{table}/editar', [TableController::class, 'edit'])->name('admin.tables.edit');
    Route::put('/mesas/{table}', [TableController::class, 'update'])->name('admin.tables.update');
    Route::delete('/mesas/{table}', [TableController::class, 'destroy'])->name('admin.tables.destroy');
    Route::patch('/mesas/{table}/estado/{status}', [TableController::class, 'changeStatus'])->name('admin.tables.status');
});
    
    
    // Rutas para cajero
    Route::middleware(['auth', 'role:cajero'])->prefix('cajero')->group(function () {
        Route::get('/dashboard', function () {
            return view('cajero.dashboard');
        })->name('cajero.dashboard');
    });
    
    // Rutas para mesero (solo visualizar y cambiar estado)
    Route::middleware(['auth', 'role:mesero'])->prefix('mesero')->group(function () {
    Route::get('/mesas', [TableController::class, 'index'])->name('mesero.tables');
    Route::patch('/mesas/{table}/estado/{status}', [TableController::class, 'changeStatus'])->name('mesero.tables.status');
    
    // Rutas de órdenes
    Route::get('/ordenes', [OrderController::class, 'index'])->name('mesero.orders.index');
    Route::get('/mesas/{table}/orden/crear', [OrderController::class, 'create'])->name('mesero.orders.create');
    Route::post('/ordenes', [OrderController::class, 'store'])->name('mesero.orders.store');
    Route::get('/ordenes/{order}', [OrderController::class, 'show'])->name('mesero.orders.show');
    Route::get('/ordenes/{order}/editar', [OrderController::class, 'edit'])->name('mesero.orders.edit');
    Route::put('/ordenes/{order}', [OrderController::class, 'update'])->name('mesero.orders.update');
    Route::get('/ordenes/{order}/precuenta', [OrderController::class, 'generatePrebill'])->name('mesero.orders.prebill');
    Route::delete('/orden-items/{item}', [OrderController::class, 'removeItem'])->name('mesero.order-items.remove');


    // Rutas para tickets
Route::get('/tickets/{order}/cocina', [TicketController::class, 'previewKitchenTicket'])->name('mesero.tickets.preview');
Route::get('/tickets/{order}/cocina/generar', [TicketController::class, 'generateKitchenTicket'])->name('mesero.tickets.generate');
Route::get('/tickets/{order}/cocina/descargar', [TicketController::class, 'downloadKitchenTicket'])->name('mesero.tickets.download');
Route::get('/tickets/{order}/cocina/ultimos', [TicketController::class, 'printLastItems'])->name('mesero.tickets.last');
}

);


    
});