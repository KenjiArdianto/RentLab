<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DetailPageController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

Route::get('/', [VehicleController::class,'index']);

Route::get('/vehicle/{id}', [VehicleController::class,'show'])->name("vehicle.detail"); //DON
// Route::get('/DetailPage/{id}', [VehicleController::class,'show'])->name("vehicle"); 

Route::get('/cart', [CartController::class,'index'])->name('cart'); //DON
// Route::get('/CartPage', [CartController::class,'index'])->name('cart');

Route::delete('/cart/{id}/destroy', [CartController::class,'destroy'])->name("cart.destroy"); //DON
// Route::delete('/CartPage/{id}', [CartController::class,'hapus']);

// input
Route::post('/cart/store', [CartController::class,'store'])->name('cart.store'); //DON
// Route::post('/cartInput', [CartController::class,'store']);


// Route::delete('/vehiDel/{id}', [CartController::class,'destroy']);  HAPUS








// Route::get('/cartInput', [CartController::class,'create']);//HPAUS
