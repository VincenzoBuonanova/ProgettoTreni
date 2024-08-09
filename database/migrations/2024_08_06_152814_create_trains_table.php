<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainsTable extends Migration
{
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('train_number');
            $table->string('departure_station_description');
            $table->time('departure_date');
            $table->string('arrival_station_description');
            $table->time('arrival_date');
            $table->integer('delay_amount')->nullable();
            $table->date('saved_at_date')->nullable();
            $table->time('saved_at_time')->nullable();
            $table->timestamps();

            $table->unique(['train_number', 'saved_at_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trains');
    }
}
