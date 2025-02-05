<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rover_id')->constrained()->onDelete('cascade'); // Relación con el rover
            $table->foreignId('map_id')->constrained()->onDelete('cascade'); // Relación con el mapa
            $table->integer('x');
            $table->integer('y');
            $table->json('movements')->nullable(); // Movimientos de la misión
            $table->string('mission_status')->default('active'); // Estado de la misión
            //$table->foreignId('exploration_map_id')->nullable()->constrained('exploration_maps')->onDelete('set null'); // Relación con exploration_map
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
