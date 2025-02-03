<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('maps', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('test_map');
            $table->integer('width')->default(200);
            $table->integer('height')->default(200);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maps');
    }
};

