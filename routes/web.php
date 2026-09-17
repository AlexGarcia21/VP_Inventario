<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;

/*
 Rutas Públicas (Autenticación)
*/
Route::get('/login', Login::class)->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
 Rutas Protegidas por Rol
*/

// 1. Solicitante (Enfermería), Supervisor y Administrador
Route::middleware(['auth', 'role:solicitante,supervisor,admin'])->group(function () {
    Route::get('/', function () { 
        return view('welcome'); 
    })->name('orders.create');
});

// 2. Supervisor (Almacén) y Administrador
Route::middleware(['auth', 'role:supervisor,admin'])->prefix('almacen')->group(function () {
    // Al entrar a /almacen, se ejecuta WarehouseController@index que envía $lowStockProducts y $pendingOrders
    Route::get('/', [WarehouseController::class, 'index'])->name('warehouse.index');
    
    // Al entrar a /almacen/entradas
    Route::get('/entradas', function () { 
        return view('warehouse.entries'); 
    })->name('warehouse.entries');
});

// 3. Administrador Exclusivo
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/productos', function () { 
        return view('admin.products'); 
    })->name('admin.products');

    Route::get('/residentes', function () { 
        return view('admin.residents'); 
    })->name('admin.residents');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});