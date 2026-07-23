<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        // Residentes del Piso 1 (floor_id = 1)
        Resident::create(['name' => 'Don Roberto Carlos', 'floor_id' => 1]);
        Resident::create(['name' => 'Doña María Félix', 'floor_id' => 1]);
        Resident::create(['name' => 'Doña Silvia Pinal', 'floor_id' => 1]);

        // Residentes del Piso 2 (floor_id = 2)
        Resident::create(['name' => 'Don Vicente Fernández', 'floor_id' => 2]);
        Resident::create(['name' => 'Doña Irma Serrano', 'floor_id' => 2]);

        // Residentes del Piso 3 (floor_id = 3)
        Resident::create(['name' => 'Don Chabelo', 'floor_id' => 3]);
        Resident::create(['name' => 'Doña Carmen Salinas', 'floor_id' => 3]);
    }
}