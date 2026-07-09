<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    // Un residente pertenece a un piso
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    // Un residente tiene muchas solicitudes de insumos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
