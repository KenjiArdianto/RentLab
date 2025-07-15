<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/landing', [LandingController::class, 'index'])->name('landing.index');