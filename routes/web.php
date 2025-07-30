<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UserDetailController;
use App\Http\Middleware\EnsureUserHasDetails;
use App\Http\Middleware\EnsureUserAuthenticateAsUser;
use App\Http\Middleware\EnsureUserAuthenticateAsAdmin;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/landing', [LandingController::class, 'index'])->name('landing.index');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/verify-otp', [OtpController::class, 'showForm'])->name('otp.verify.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
Route::get('/resent-verify-otp',[OtpController::class,'resentOTP'])->name('resend.otp');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);  


Route::get('/complete-user-detail',[UserDetailController::class,'show'])->name('complete.user.detail');
Route::post('/complete-user-detail',[UserDetailController::class,'store'])->name('post.user.detail');
// Route::get('/complete-user-detail', [UserDetailController::class, 'show'])->name('complete.user.detail');
// Route::post('/complete-user-detail', [UserDetailController::class, 'store']);


// Route::get('/profile',[ProfileController::class,'index'])->name('view.profile');
Route::post('/profile',[ProfileController::class,'change'])->name('change.profile');
Route::post('/profile/delete',[ProfileController::class,'delete'])->name('delete.profile');
Route::get('/coba',[ProfileController::class,'coba']);

Route::middleware(['auth', EnsureUserHasDetails::class])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::middleware([EnsureUserAuthenticateAsUser::class])->group(function(){
    Route::get('/profile',[ProfileController::class,'index'])->name('view.profile');
});

Route::middleware([EnsureUserAuthenticateAsAdmin::class])->group(function(){
    Route::get('/coba',[ProfileController::class,'coba']);
});
