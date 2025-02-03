<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rover_id')->constrained();
            $table->foreignId('map_id')->constrained();
            $table->integer('x')->check('x BETWEEN 100 AND 200');
            $table->integer('y')->check('y BETWEEN 100 AND 200');
            $table->json('movements')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('missions');
    }
};



