<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Middleware\Cors;


// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', [TrainController::class, 'home'])->name('home');


Route::middleware(Cors::class)->group(function () {
    Route::get('/trains', [TrainController::class, 'index'])->name('trains.index');
    Route::post('/trains/save', [TrainController::class, 'store'])->name('trains.store');
    Route::get('/trains/saved', [TrainController::class, 'savedTrains'])->name('trains.saved');
    Route::get('/refresh-trains-data', [TrainController::class, 'refreshTrainsData'])->name('trains.refreshTrainsData');
});
