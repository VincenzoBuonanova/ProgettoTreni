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
            $table->string('TrainNumber');
            $table->string('DepartureStationDescription');
            $table->time('DepartureDate');
            $table->string('ArrivalStationDescription');
            $table->time('ArrivalDate');
            $table->integer('DelayAmount')->nullable();
            $table->date('saved_at_date')->default(DB::raw('CURRENT_DATE'));
            $table->time('saved_at_time')->default(DB::raw('CURRENT_TIME'));
            $table->timestamps();

            $table->unique(['TrainNumber', 'saved_at_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trains');
    }
}
