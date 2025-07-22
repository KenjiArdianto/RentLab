<?php

use App\Http\Controllers\CartPageController;
use App\Http\Controllers\DetailPageController;
use App\Http\Controllers\vehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

route::get('/', [vehicleController::class,'index']);

route::get('/DetailPage/{id}', [vehicleController::class,'show']);

route::get('/CartPage', [CartPageController::class,'index']);

route::delete('/CartPage/{id}', [CartPageController::class,'hapus']);

// route::get('/DetailPage/{id}', [CartPageController::class,'viewVehicleById']);

// input
route::get('/cartInput', [CartPageController::class,'create']);
route::post('/cartInput', [CartPageController::class,'store']);


route::delete('/vehiDel/{id}', [CartPageController::class,'destroy']);


