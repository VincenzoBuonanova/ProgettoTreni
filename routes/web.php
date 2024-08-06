<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Middleware\Cors;


Route::get('/', function () {
    return view('home');
});



Route::middleware(Cors::class)->group(function () {
    Route::get('/trains', [TrainController::class, 'index'])->name('trains.index');
    Route::post('/trains/save', [TrainController::class, 'store'])->name('trains.store');
    Route::get('/trains/saved', [TrainController::class, 'savedTrains'])->name('trains.saved');
});
