<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Permisos para el detalle del carrito
    protected $fillable = ['order_id', 'product_id', 'requested_quantity'];
    
    // Este detalle pertenece a una orden específica
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Este detalle pertenece a un insumo específico
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}