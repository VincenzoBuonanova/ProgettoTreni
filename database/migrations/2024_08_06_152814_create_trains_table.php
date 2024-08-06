<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('train_number');
            $table->string('departure_station');
            $table->string('arrival_station');
            $table->timestamp('departure_time');
            $table->timestamp('arrival_time');
            $table->integer('disruption')->default(0); // minuti di ritardo
            // $table->integer('delay')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};
