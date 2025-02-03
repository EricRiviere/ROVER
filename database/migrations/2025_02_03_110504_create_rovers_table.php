<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rovers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('rover_x');
            $table->integer('x')->default(0);
            $table->integer('y')->default(0);
            $table->enum('direction', ['N', 'S', 'E', 'W'])->default('N');
            $table->enum('status', ['inactive', 'deployed'])->default('inactive');
            // $table->foreignId('map_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rovers');
    }
};

