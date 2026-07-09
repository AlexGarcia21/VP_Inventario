<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Una solicitud pertenece a un residente
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    // Una solicitud tiene muchos "ítems" o detalles
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}