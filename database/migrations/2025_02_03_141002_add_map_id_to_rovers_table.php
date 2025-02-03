<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Añadir la columna map_id a la tabla rovers
        Schema::table('rovers', function (Blueprint $table) {
            $table->unsignedBigInteger('map_id')->nullable()->after('status');  // Ajusta el 'after' según la posición deseada
        });
    
        // Añadir la clave foránea
        Schema::table('rovers', function (Blueprint $table) {
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('set null');
        });
    }
    
    public function down()
    {
        // Eliminar la clave foránea
        Schema::table('rovers', function (Blueprint $table) {
            $table->dropForeign(['map_id']);
        });
    
        // Eliminar la columna map_id
        Schema::table('rovers', function (Blueprint $table) {
            $table->dropColumn('map_id');
        });
    }
    
};
