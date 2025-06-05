<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDriverController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/logs', function () {
    return view('admin.logs');
})->name('admin.logs');

Route::get('/admin/users', function () {
    return view('admin.users');
})->name('admin.users');

// Route::get('/admin/drivers', function () {
//     return view('admin.drivers');
// })->name('admin.drivers');

Route::get('/admin/drivers',[AdminDriverController::class, 'index'])->name('admin.drivers');
Route::get('/admin/drivers-',[AdminDriverController::class, 'search'])->name('admin.drivers.search');


// Route::get('/admin/drivers/{page_number}',[AdminDriverController::class, 'index'])->name('admin.drivers');


Route::get('/admin/transactions', function () {
    return view('admin.transactions');
})->name('admin.transactions');

Route::get('/admin/vehicles', function () {
    return view('admin.vehicles');
})->name('admin.vehicles');

Route::get('/admin/reviews', function () {
    return view('admin.reviews');
})->name('admin.reviews');
