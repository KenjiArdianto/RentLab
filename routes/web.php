<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', [VehicleController::class, 'display'])->name('vehicle.display');
Route::get('/catalog', [VehicleController::class, 'catalog'])->name('vehicle.catalog');
Route::get('/detail/{vehicle}', [VehicleController::class, 'detail'])->name('vehicle.detail');