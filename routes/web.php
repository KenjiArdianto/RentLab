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
Route::post('/admin/drivers/store', [AdminDriverController::class, 'store'])->name('admin.drivers.store');
Route::post('/admin/drivers/edit-selected', [AdminDriverController::class, 'editSelected'])->name('admin.drivers.edit');
Route::delete('/admin/drivers/delete-selected', [AdminDriverController::class, 'deleteSelected'])->name('admin.drivers.delete');
// Route::get('/admin/drivers/{page_number}',[AdminDriverController::class, 'index'])->name('admin.drivers');


Route::get('/admin/transactions',[AdminTransactionController::class, 'index'])->name('admin.transactions');
Route::post('/admin/transactions/{transaction}', [AdminTransactionController::class, 'update'])->name('admin.transactions.update');


Route::get('/admin/vehicles',[AdminVehicleController::class, 'index'])->name('admin.vehicles');
Route::post('/admin/vehicles/store', [AdminVehicleController::class, 'store'])->name('admin.vehicles.store');
Route::post('/admin/vehicles/{vehicle}/update', [AdminVehicleController::class, 'update'])->name('admin.vehicles.update');
Route::post('/admin/vehicles/{vehicle}/update-category', [AdminVehicleController::class, 'updateCategory'])->name('admin.vehicles.updateCategory');
Route::post('/admin/vehicles/{vehicle}/delete-category', [AdminVehicleController::class, 'deleteCategory'])->name('admin.vehicles.deleteCategory');
Route::get('/admin/vehicles/{vehicle}/reviews', [AdminVehicleController::class, 'showReviews'])->name('admin.vehicles.reviews');
Route::get('/admin/vehicles/{vehicle}/transactions', [AdminVehicleController::class, 'showTransactions'])->name('admin.vehicles.transactions');


Route::get('/admin/reviews', function () {
    return view('admin.reviews');
})->name('admin.reviews');


