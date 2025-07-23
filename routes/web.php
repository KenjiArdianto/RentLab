<?php

use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UserDetailController;
use App\Http\Middleware\EnsureUserHasDetails;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/landing', [LandingController::class, 'index'])->name('landing.index');
Auth::routes();

Route::get('/verify-otp', [OtpController::class, 'showForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
Route::get('/resent-verify-otp',[OtpController::class,'resentOTP'])->name('resend.otp');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);  

Route::middleware(['auth', EnsureUserHasDetails::class])->group(function () {
    Route::get('/home', [VehicleController::class, 'display'])->name('vehicle.display');
});
Route::get('/complete-user-detail',[UserDetailController::class,'show'])->name('complete.user.detail');
Route::post('/complete-user-detail',[UserDetailController::class,'store'])->name('post.user.detail');
// Route::get('/complete-user-detail', [UserDetailController::class, 'show'])->name('complete.user.detail');
// Route::post('/complete-user-detail', [UserDetailController::class, 'store']);

Route::get('/home', [VehicleController::class, 'display'])->name('vehicle.display');
Route::get('/catalog', [VehicleController::class, 'catalog'])->name('vehicle.catalog');
Route::get('/detail/{vehicle}', [VehicleController::class, 'detail'])->name('vehicle.detail');