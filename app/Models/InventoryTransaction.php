<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class InventoryTransaction extends Model
{
    // Habilitamos las columnas para asignación masiva
    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'notes',
    ];

    // Relación: Un movimiento pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación: Un movimiento fue registrado por un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}