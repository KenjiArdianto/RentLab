<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DetailPageController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/', [VehicleController::class,'index']);

Route::get('/vehicle/{id}', [VehicleController::class,'show'])->name("vehicle.detail"); 


Route::get('/cart', [CartController::class,'index'])->name('cart'); 


Route::delete('/cart/{id}/destroy', [CartController::class,'destroy'])->name("cart.destroy"); 


// input
Route::post('/cart/store', [CartController::class,'store'])->name('cart.store'); 











