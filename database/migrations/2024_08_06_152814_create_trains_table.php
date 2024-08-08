<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainsTable extends Migration
{
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('TrainNumber');
            $table->string('DepartureStationDescription');
            $table->datetime('DepartureDate');  // Cambiato a datetime
            $table->string('ArrivalStationDescription');
            $table->datetime('ArrivalDate');  // Cambiato a datetime
            $table->integer('DelayAmount')->nullable();  // Cambiato il nome per evitare il punto
            $table->timestamp('saved_at')->useCurrent();
            $table->timestamps();

            // Constraint univoco su numero treno e data di partenza
            $table->unique(['TrainNumber', 'DepartureDate']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trains');
    }
}
