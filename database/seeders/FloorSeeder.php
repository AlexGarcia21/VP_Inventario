<?php //se registra los 5 pisos en la base de datos

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;

class FloorSeeder extends Seeder
{
    public function run()
    {
        $floors = ['Piso 1', 'Piso 2', 'Piso 3', 'Piso 4', 'Piso 5'];

        foreach ($floors as $floor) {
            Floor::create(['name' => $floor]);
        }
    }
}