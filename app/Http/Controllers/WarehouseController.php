<?php

namespace App\Http\Controllers;

class WarehouseController extends Controller
{
    public function index()
    {
        // Las órdenes pendientes y el stock crítico ahora los consulta el componente
        // Livewire WarehousePanel, que se actualiza solo (ver app/Livewire/WarehousePanel.php).
        return view('warehouse.index');
    }
}