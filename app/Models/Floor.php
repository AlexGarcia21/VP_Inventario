<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    
    // ¡Esta es la línea mágica que faltaba!
    protected $fillable = ['name'];
    // Un piso tiene muchos residentes
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }
}
//piso que agrupa a varios residentes.