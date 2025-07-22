<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DetailPageController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;

route::get('/', [VehicleController::class,'index']);

route::get('/DetailPage/{id}', [VehicleController::class,'show']);

route::get('/CartPage', [CartController::class,'index']);

route::delete('/CartPage/{id}', [CartController::class,'hapus']);

// route::get('/DetailPage/{id}', [CartController::class,'viewVehicleById']);

// input
route::get('/cartInput', [CartController::class,'create']);
route::post('/cartInput', [CartController::class,'store']);


route::delete('/vehiDel/{id}', [CartController::class,'destroy']);


