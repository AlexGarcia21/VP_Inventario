<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class WarehouseController extends Controller
{
    public function index()
    {
        // Traemos todas las órdenes pendientes.
        // Usamos 'with' para traer los datos del residente y de los items (incluyendo el producto) en una sola consulta.
        $pendingOrders = Order::with(['resident', 'items.product'])
                              ->where('status', 'pending')
                              ->orderBy('created_at', 'asc') // Las más antiguas primero (Primeras entradas, primeras salidas)
                              ->get();

        return view('warehouse.index', compact('pendingOrders'));
    }
}