<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PembayaranController; // Pastikan controller ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk mengganti bahasa
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Rute untuk webhook Xendit (di luar grup)
Route::post('/payment/callback', [PembayaranController::class, 'handleWebhook'])->name('payment.callback');


// ===================================================================
// FIX: Logika redirect disederhanakan untuk memutus redirect loop.
// ===================================================================

// 1. Mengarahkan dari halaman utama (root) LANGSUNG ke halaman checkout dengan bahasa default.
Route::get('/', function () {
    $defaultLocale = config('app.fallback_locale', 'id');
    return redirect()->route('checkout', ['locale' => $defaultLocale]);
});

// 2. Grup rute sekarang hanya berisi rute yang sebenarnya.
Route::prefix('{locale}')
    ->where(['locale' => '[a-z]{2}'])
    ->middleware('setlocale')
    ->group(function () {

        // RUTE UNTUK FITUR PEMBAYARAN
        Route::match(['get', 'post'], '/checkout', [PembayaranController::class, 'show'])->name('checkout');
        Route::post('/process-payment', [PembayaranController::class, 'createCheckoutInvoice'])->name('payment.process');
        Route::get('/payment-success', [PembayaranController::class, 'success'])->name('payment.success');
        Route::get('/payment-failed', [PembayaranController::class, 'failed'])->name('payment.failed');

        // Rute redirect dari root bahasa (misal: /id) DIHAPUS karena menyebabkan loop.
    });
