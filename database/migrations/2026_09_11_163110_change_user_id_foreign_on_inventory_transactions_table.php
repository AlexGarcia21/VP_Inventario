<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * La FK original de user_id tenía cascadeOnDelete(): borrar un usuario
     * borraba en cascada TODO su historial de movimientos de inventario,
     * perdiendo la bitácora de auditoría. La cambiamos a restrictOnDelete()
     * para que, mientras exista historial ligado a ese usuario, la base de
     * datos rechace el borrado en vez de destruir el registro contable.
     */
    public function up(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->cascadeOnDelete();
        });
    }
};
