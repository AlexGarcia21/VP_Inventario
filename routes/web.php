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

/* NOTA: la aprobación/rechazo de órdenes ahora vive únicamente en el componente
Livewire OrderDetailModal (ver app/Livewire/OrderDetailModal.php). Se eliminó
la ruta y el OrderController duplicados para no tener dos copias de la misma
lógica de negocio con riesgo de quedar desincronizadas.
Ruta del Dashboard principal*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Ruta para la vista de entradas de inventario
Route::get('/almacen/entradas', function () {
    return view('warehouse.entries');});

//Ruta para la vista del crud de insumos
Route::get('/admin/productos', function () {
    return view('admin.products');});

//Ruta para la vista del crud de residentes
    Route::get('/admin/residentes', function () {
    return view('admin.residents');});