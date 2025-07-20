<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminVehicleController;

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
Route::post('/admin/drivers/stored', [AdminDriverController::class, 'store'])->name('admin.drivers.store');
Route::post('/admin/drivers/edit-selected', [AdminDriverController::class, 'editSelected'])->name('admin.drivers.edit');
Route::delete('/admin/drivers/delete-selected', [AdminDriverController::class, 'deleteSelected'])->name('admin.drivers.delete');
// Route::get('/admin/drivers/{page_number}',[AdminDriverController::class, 'index'])->name('admin.drivers');


Route::get('/admin/transactions',[AdminTransactionController::class, 'index'])->name('admin.transactions');
Route::post('/admin/transactions/{transaction}', [AdminTransactionController::class, 'update'])->name('admin.transactions.update');
Route::get('/admin/transactions-',[AdminTransactionController::class, 'search'])->name('admin.transactions.search');


Route::get('/admin/vehicles',[AdminVehicleController::class, 'index'])->name('admin.vehicles');
Route::post('/admin/vehicles/{vehicle}', [AdminVehicleController::class, 'update'])->name('admin.vehicles.update');

Route::get('/admin/reviews', function () {
    return view('admin.reviews');
})->name('admin.reviews');


