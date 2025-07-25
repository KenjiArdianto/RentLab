<?php

use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('payment');
});

// Route untuk menerima notifikasi dari Xendit
Route::post('/bayar', [PembayaranController::class, 'createCheckoutInvoice']);
Route::post('/xendit/webhook', [PembayaranController::class, 'handleWebhook']);
// Tambahkan route ini di routes/web.php

Route::get('/payment/success', function () {
    return "Pembayaran Berahasil! Terima kasih.";
})->name('payment.success');
