<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DetailPageController;
use App\Http\Controllers\VehicleController;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\UserDetailController;
use App\Http\Middleware\EnsureUserHasDetails;
use App\Http\Controllers\AdminIndexController;
use App\Http\Controllers\AdminDriverController;
use App\Http\Controllers\AdminTransactionController;
use App\Http\Controllers\AdminVehicleController;
use App\Http\Controllers\AdminVehicleReviewController;
use App\Http\Controllers\AdminVehicleTypeController;
use App\Http\Controllers\AdminVehicleNameController;
use App\Http\Controllers\AdminVehicleCategoryController;
use App\Http\Controllers\AdminVehicleTransmissionController;
use App\Http\Controllers\AdminLocationController;

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

Route::get('/vehicle/{id}', [VehicleController::class,'show'])->name("vehicle.detail"); 


Route::get('/cart', [CartController::class,'index'])->name('cart'); 


Route::delete('/cart/{id}/destroy', [CartController::class,'destroy'])->name("cart.destroy"); 


// input
Route::post('/cart/store', [CartController::class,'store'])->name('cart.store'); 




// ADMIN
Route::get('/admin',[AdminIndexController::class, 'index'])->name('admin.index');


Route::get('/admin/logs', function () {
    return view('admin.logs');
})->name('admin.logs');


Route::get('/admin/users', function () {
    return view('admin.users');
})->name('admin.users');


Route::get('/admin/drivers',[AdminDriverController::class, 'index'])->name('admin.drivers');
Route::post('/admin/drivers/store', [AdminDriverController::class, 'store'])->name('admin.drivers.store');
Route::post('/admin/drivers/edit-selected', [AdminDriverController::class, 'editSelected'])->name('admin.drivers.edit');
Route::delete('/admin/drivers/delete-selected', [AdminDriverController::class, 'deleteSelected'])->name('admin.drivers.delete');


Route::get('/admin/transactions',[AdminTransactionController::class, 'index'])->name('admin.transactions');
Route::post('/admin/transactions/{transaction}', [AdminTransactionController::class, 'update'])->name('admin.transactions.update');


Route::get('/admin/vehicles',[AdminVehicleController::class, 'index'])->name('admin.vehicles');
Route::post('/admin/vehicles/store', [AdminVehicleController::class, 'store'])->name('admin.vehicles.store');
Route::post('/admin/vehicles/{vehicle}/update', [AdminVehicleController::class, 'update'])->name('admin.vehicles.update');
Route::post('/admin/vehicles/{vehicle}/update-category', [AdminVehicleController::class, 'updateCategory'])->name('admin.vehicles.updateCategory');
Route::post('/admin/vehicles/{vehicle}/delete-category', [AdminVehicleController::class, 'deleteCategory'])->name('admin.vehicles.deleteCategory');
Route::get('/admin/vehicles/{vehicle}/reviews', [AdminVehicleReviewController::class, 'index'])->name('admin.vehicles.reviews');
Route::post('/admin/vehicles/{vehicle}/reviews/{vehicleReview}/update', [AdminVehicleReviewController::class, 'update'])->name('admin.vehicles.reviews.update');
Route::post('/admin/vehicles/{vehicle}/reviews/{vehicleReview}/destroy', [AdminVehicleReviewController::class, 'destroy'])->name('admin.vehicles.reviews.destroy');


Route::get('/admin/vehicle-types', [AdminVehicleTypeController::class, 'index'])->name('admin.vehicle-types');
Route::post('/admin/vehicle-types/store', [AdminVehicleTypeController::class, 'store'])->name('admin.vehicle-types.store');
Route::post('/admin/vehicle-types/{vehicleType}/update', [AdminVehicleTypeController::class, 'update'])->name('admin.vehicle-types.update');
Route::post('/admin/vehicle-types/{vehicleType}/destroy', [AdminVehicleTypeController::class, 'destroy'])->name('admin.vehicle-types.destroy');


Route::get('/admin/vehicle-names', [AdminVehicleNameController::class, 'index'])->name('admin.vehicle-names');
Route::post('/admin/vehicle-names/store', [AdminVehicleNameController::class, 'store'])->name('admin.vehicle-names.store');
Route::post('/admin/vehicle-names/{vehicleName}/update', [AdminVehicleNameController::class, 'update'])->name('admin.vehicle-names.update');
Route::post('/admin/vehicle-names/{vehicleName}/destroy', [AdminVehicleNameController::class, 'destroy'])->name('admin.vehicle-names.destroy');


Route::get('/admin/vehicle-transmissions', [AdminVehicleTransmissionController::class, 'index'])->name('admin.vehicle-transmissions');
Route::post('/admin/vehicle-transmissions/store', [AdminVehicleTransmissionController::class, 'store'])->name('admin.vehicle-transmissions.store');
Route::post('/admin/vehicle-transmissions/{vehicleTransmission}/update', [AdminVehicleTransmissionController::class, 'update'])->name('admin.vehicle-transmissions.update');
Route::post('/admin/vehicle-transmissions/{vehicleTransmission}/destroy', [AdminVehicleTransmissionController::class, 'destroy'])->name('admin.vehicle-transmissions.destroy');


Route::get('/admin/vehicle-categories', [AdminVehicleCategoryController::class, 'index'])->name('admin.vehicle-categories');
Route::post('/admin/vehicle-categories/store', [AdminVehicleCategoryController::class, 'store'])->name('admin.vehicle-categories.store');
Route::post('/admin/vehicle-categories/{vehicleCategory}/update', [AdminVehicleCategoryController::class, 'update'])->name('admin.vehicle-categories.update');
Route::post('/admin/vehicle-categories/{vehicleCategory}/destroy', [AdminVehicleCategoryController::class, 'destroy'])->name('admin.vehicle-categories.destroy');


Route::get('/admin/locations', [AdminLocationController::class, 'index'])->name('admin.locations');
Route::post('/admin/locations/store', [AdminLocationController::class, 'store'])->name('admin.locations.store');
Route::post('/admin/locations/{location}/update', [AdminLocationController::class, 'update'])->name('admin.locations.update');
Route::post('/admin/locations/{location}/destroy', [AdminLocationController::class, 'destroy'])->name('admin.locations.destroy');

