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
    Schema::create('residents', function (Blueprint $table) {
        $table->id();
        $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');
        $table->string('name');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
//Conectada al piso mediante floor_id para poder filtrarlos fácilmente.