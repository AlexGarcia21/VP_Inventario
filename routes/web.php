<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController; // warehouse controller para manejar la vista de almacén
use App\Http\Controllers\OrderController; // ordercontroller para manejar la aprobación de órdenes 
use App\Http\Controllers\DashboardController; // dashboard controller para manejar la vista del dashboard
Route::get('/', function () {
    return view('welcome'); 
});

// nueva ruta de almacen
Route::get('/almacen', [WarehouseController::class, 'index'])->name('warehouse.index');
// Ruta para autorizar la orden y restar el stock físico
Route::post('/almacen/ordenes/{id}/autorizar', [OrderController::class, 'approveOrder'])->name('orders.approve');
// Ruta del Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');