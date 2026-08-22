<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'current_stock',
        'min_stock',
    ];
    // Un insumo puede estar en muchos detalles de pedidos
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Un insumo tiene muchos movimientos de inventario (entradas y salidas)
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}