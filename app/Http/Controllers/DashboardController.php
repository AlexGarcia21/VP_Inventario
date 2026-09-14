<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product; 
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
                              ->where('status', 'pending')
                              ->orderBy('created_at', 'asc')
                              ->get();

        /*2. Alerta de Desabasto (Insumos Críticos)
        Usamos el mismo criterio que el panel de Almacén (WarehouseController):
        productos cuyo stock actual llegó o cayó por debajo de SU PROPIO umbral
        mínimo configurado (min_stock), en vez de un número fijo. Antes este
        controlador usaba "<= 10" para todos los productos por igual, lo que
        hacía que Dashboard y Almacén mostraran listas distintas para los
        mismos datos.*/
        $lowStockProducts = Product::whereColumn('current_stock', '<=', 'min_stock')
                                   ->orderBy('current_stock', 'asc')
                                   ->get();

        // 3. Productividad del día (Órdenes surtidas hoy)
        $todayOrdersCount = Order::where('status', 'approved')
                                 ->whereDate('updated_at', today())
                                 ->count();

        return view('dashboard.index', compact(
            'pendingOrders', 
            'lowStockProducts', 
            'todayOrdersCount'
        ));
    }
}