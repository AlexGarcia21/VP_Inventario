<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    // Un movimiento pertenece a un producto específico
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}