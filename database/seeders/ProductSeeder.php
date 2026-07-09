<?php //agrega productos de ejemplo a la base de datos

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Pañales de calzón',
            'brand' => 'Marca A',
            'current_stock' => 50,
            'min_stock' => 10
        ]);

        Product::create([
            'name' => 'Jabón neutro',
            'brand' => 'Marca B',
            'current_stock' => 30,
            'min_stock' => 5
        ]);
    }
}