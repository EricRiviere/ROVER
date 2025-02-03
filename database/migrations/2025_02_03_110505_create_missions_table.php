<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rover_id')->constrained()->onDelete('cascade');
            $table->foreignId('map_id')->constrained()->onDelete('cascade');
            $table->json('position'); // {"x": 10, "y": 15}
            $table->enum('status', ['in_progress', 'completed']);
            $table->json('commands')->nullable(); // [{"command": "F", "position": {"x": 10, "y": 16}}]
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('missions');
    }
};


