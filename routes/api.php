<?php

use App\Http\Controllers\PembayaranController; // <-- Jangan lupa import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Daftarkan route webhook Anda di sini
// Route::post('/payment/callback', [PembayaranController::class, 'handleWebhook']);
