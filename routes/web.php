<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController; 

Route::get('/', function () {
    return view('welcome'); 
});

// Nuestra nueva ruta
Route::get('/almacen', [WarehouseController::class, 'index'])->name('warehouse.index');