<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingHistoryController;
use App\Http\Controllers\UserReviewController;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking-history', [BookingHistoryController::class, 'index'])
    //->middleware('auth') 
    ->name('booking.history');

Route::post('/booking/{transaction}/cancel', [BookingHistoryController::class, 'cancel'])->name('booking.cancel');

Route::get('/receipt/{transaction}/download', [BookingHistoryController::class, 'downloadReceipt'])
    //->middleware('auth')
    ->name('receipt.download');

Route::post('/reviews/{transaction}', [UserReviewController::class, 'store'])
    //->middleware('auth')
    ->name('reviews.store');

Route::post('/lang', LanguageController::class);