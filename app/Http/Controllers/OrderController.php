<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    /**
     * Autoriza la salida de una orden y descuenta el stock de los insumos.
     */

    
    public function approveOrder($id)
    {
        try {
            // Iniciamos la transacción de base de datos
            DB::transaction(function () use ($id) {
                
                // 1. Buscamos la orden con sus insumos
                $order = Order::with('items.product')->findOrFail($id);

                // Validamos que no esté ya aprobada para evitar doble resta de stock
                if ($order->status === 'aprobada') {
                    throw new Exception('Esta orden ya fue autorizada previamente.');
                }

                // 2. Recorremos cada insumo solicitado para restar el stock
                foreach ($order->items as $item) {
                    $product = $item->product;

                    // Validamos que haya suficiente inventario físico
                    if ($product->stock < $item->requested_quantity) {
                        throw new Exception("Stock insuficiente para: {$product->name}. Quedan {$product->stock} unidades.");
                    }

                    // Restamos el stock y guardamos el producto
                    $product->stock -= $item->requested_quantity;
                    $product->save();
                }

                // 3. Cambiamos el estado de la orden a aprobada
                $order->status = 'aprobada';
                $order->save();
            });

            // Si todo sale bien, retornamos una respuesta exitosa a la vista de tu compañero
            return response()->json([
                'success' => true,
                'message' => 'Orden autorizada y stock actualizado correctamente.'
            ], 200);

        } catch (Exception $e) {
            // Si algo falla (ej. falta de stock), se cancela todo y regresamos el error
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}