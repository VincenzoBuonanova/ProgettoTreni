<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrainController;
use App\Http\Middleware\Cors;

Route::get('/', [TrainController::class, 'home'])->name('home');

Route::middleware(Cors::class)->group(function () {
    Route::get('/trains', [TrainController::class, 'index'])->name('trains.index');
    Route::get('/trains/refresh', [TrainController::class, 'refreshTrainsData'])->name('trains.refresh'); //funzione per il refresh
    Route::get('/trains/save', [TrainController::class, 'save'])->name('trains.save');
    Route::get('/trains/saved', [TrainController::class, 'trainsSaved'])->name('trains.saved');
});
