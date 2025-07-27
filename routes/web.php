<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\LanguageController; // Pastikan ini ditambahkan

// Rute untuk mengganti bahasa (diletakkan di luar grup)
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// ===================================================================
// FIX: Rute untuk halaman utama (root) dipindahkan ke atas.
// Ini akan menjadi satu-satunya rute yang menangani URL '/'.
// ===================================================================
Route::get('/', function () {
    // Redirect dari halaman utama (root) ke bahasa default (misal: 'id')
    return redirect(config('app.fallback_locale', 'id'));
});

// Grup rute dengan prefix bahasa dinamis dan middleware
Route::prefix('{locale}')
    ->where(['locale' => '[a-z]{2}']) // Memastikan locale hanya 2 huruf (id, en)
    ->middleware('setlocale')
    ->group(function () {

        // Redirect dari /id atau /en ke halaman home yang benar
        Route::get('/', function ($locale) {
            return redirect()->route('vehicle.display', ['locale' => $locale]);
        });

        Route::get('/home', [VehicleController::class, 'display'])->name('vehicle.display');
        Route::get('/catalog', [VehicleController::class, 'catalog'])->name('vehicle.catalog');
        Route::get('/detail/{vehicle}', [VehicleController::class, 'detail'])->name('vehicle.detail');
});

// ===================================================================
// Rute duplikat di bawah ini dihapus untuk mengatasi masalah URL.
// ===================================================================
// Route::get('/', function () {
//     return redirect(config('app.fallback_locale', 'id'));
// });
