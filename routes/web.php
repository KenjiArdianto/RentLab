<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingHistoryController;
use App\Http\Controllers\UserReviewController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking-history', [BookingHistoryController::class, 'index'])
    //->middleware('auth') 
    ->name('booking.history');

Route::get('/receipt/{transaction}/download', [BookingHistoryController::class, 'downloadReceipt'])
    //->middleware('auth')
    ->name('receipt.download');

Route::get('/info', function () {
    phpinfo();
});

Route::post('/reviews/{transaction}', [UserReviewController::class, 'store'])
    //->middleware('auth')
    ->name('reviews.store');