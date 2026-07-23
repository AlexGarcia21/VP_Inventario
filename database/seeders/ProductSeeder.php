<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Papel Higiénico',
            'current_stock' => 240,
            'min_stock' => 50
        ]);

        Product::create([
            'name' => 'Jabón Líquido',
            'current_stock' => 45,
            'min_stock' => 15
        ]);

        Product::create([
            'name' => 'Pañales para Adulto (Talla M)',
            'current_stock' => 120,
            'min_stock' => 30
        ]);

        Product::create([
            'name' => 'Toallas Húmedas',
            'current_stock' => 80,
            'min_stock' => 20
        ]);
    }
}