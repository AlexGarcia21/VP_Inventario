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
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('brand')->nullable(); // Para guardar la marca elegida
        $table->integer('current_stock')->default(0);
        $table->integer('min_stock')->default(5); // El umbral para la alerta
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
//registrar los pañales, jabones, etc., manteniendo su stock mínimo para las alertas.