<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('inventory_transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id')->constrained('products');
        $table->foreignId('user_id')->constrained('users'); // Quién registró el movimiento
        $table->enum('type', ['in', 'out']); // 'in' = entrada proveedor, 'out' = consumo
        $table->integer('quantity');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
//registrar las compras a proveedores (entradas) y el consumo aprobado (salidas).