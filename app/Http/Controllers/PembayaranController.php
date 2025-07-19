<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Ganti use statement lama dengan yang baru untuk Xendit v7
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\ApiException;

class PembayaranController extends Controller
{
    /**
     * Membuat invoice pembayaran menggunakan Xendit SDK v7.
     */
    public function createInvoice(Request $request) // Tambahkan Request agar bisa dinamis nanti
    {
        try {
            // LANGKAH 1: Atur API key dari file config.
            Configuration::setXenditKey(config('services.xendit.secret_key'));

            // LANGKAH 2: Buat instance dari InvoiceApi.
            $apiInstance = new InvoiceApi();

            // LANGKAH 3: Siapkan parameter invoice.
            // Nanti, data ini akan diambil dari form booking atau database.
            // Contoh: 'amount' => $booking->total_harga
            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => 'RENTAL-' . uniqid(),
                'amount' => 150000, // Contoh jumlah
                'currency' => 'IDR',
                'description' => 'Pembayaran booking sewa mobil',
                'success_redirect_url' => route('payment.success'),
                // 'payer_email' => $request->user()->email, // Contoh ambil email user yang login
            ]);

            // LANGKAH 4: Buat invoice.
            $result = $apiInstance->createInvoice($createInvoiceRequest);

            // LANGKAH 5: Redirect ke halaman pembayaran.
            return redirect($result->getInvoiceUrl());

        } catch (\Exception $e) {
            // Tangani error umum lainnya
            return "Error Umum: " . $e->getMessage();
        }
    }

    /**
     * Menangani notifikasi webhook dari Xendit.
     * Logika ini sudah benar dan bisa dikembangkan.
     */
    public function handleWebhook(Request $request)
    {
        // Ambil data dari webhook
        $externalId = $request->input('external_id');
        $status = $request->input('status');

        Log::info("Webhook Diterima untuk external_id: {$externalId} dengan status: {$status}");

        // Cari booking di database Anda
        // $booking = \App\Models\Booking::where('external_id', $externalId)->first();

        // if ($booking && $status == 'PAID') {
        //     // Update status booking menjadi 'lunas'
        //     $booking->status = 'paid';
        //     $booking->save();
        //     Log::info("Booking {$externalId} berhasil diupdate menjadi lunas.");
        // }

        return response()->json(['status' => 'success']);
    }
}
