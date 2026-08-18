<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
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

        // alerta (Stock actual menor o igual al mínimo)
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')
            ->get();

        return view('warehouse.index', compact('pendingOrders', 'lowStockProducts'));
    }
}