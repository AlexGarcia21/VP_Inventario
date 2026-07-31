<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product; //modelo Product
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Muestra la pantalla principal del almacenista con métricas clave.
     */
    public function index()
    {
        // 1. Órdenes pendientes (Para la tabla principal de trabajo)
        // Usamos Eager Loading con 'resident' para evitar el problema de N+1 consultas
        $pendingOrders = Order::with('resident')
                              ->where('status', 'pendiente')
                              ->orderBy('created_at', 'asc')
                              ->get();

        // 2. Alerta de Desabasto (Insumos Críticos)
        // Buscamos productos que tengan 10 unidades o menos
        $lowStockProducts = Product::where('stock', '<=', 10)
                                   ->orderBy('stock', 'asc')
                                   ->get();

        // 3. Productividad del día (Órdenes surtidas hoy)
        $todayOrdersCount = Order::where('status', 'aprobada')
                                 ->whereDate('updated_at', today())
                                 ->count();

        // este paquete de variables va a la vista que haras
        return view('dashboard.index', compact(
            'pendingOrders', 
            'lowStockProducts', 
            'todayOrdersCount'
        ));
    }
}