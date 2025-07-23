<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;
use App\Models\Vehicle;

// Gunakan namespace yang benar untuk Exception
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
// use Xendit\Exceptions\ApiException; // <-- Komentari jika kelas tidak tersedia

class PembayaranController extends Controller
{
    public function createInvoice(Request $request)
    {
        try {
            // LANGKAH 1: Ambil data transaksi yang sudah ada untuk dites
            $transaction = Transaction::find(2); // Ambil transaksi dengan ID 2

            // Pastikan transaksi ditemukan
            if (!$transaction) {
                return "Error: Transaksi dengan ID 2 tidak ditemukan.";
            }

            // Ambil data mobil yang berelasi dengan transaksi ini
            $vehicle = Vehicle::find($transaction->vehicle_id);

            // dd($vehicle->price);

            // LANGKAH 2: Langsung buat invoice Xendit dari data yang sudah ada
            Configuration::setXenditKey(config('services.xendit.secret_key'));
            $apiInstance = new InvoiceApi();

            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => $transaction->external_id, // Gunakan external_id yang sudah ada
                'amount'      => $vehicle->price,
                'currency'    => 'IDR',
                'description' => 'Pembayaran untuk Vehicle ID: ' . $vehicle->id,
                'success_redirect_url' => route('payment.success'),
                'payer_email' => 'test@example.com', // Gunakan email statis untuk tes
                // 'invoice_duration' => 2,
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            // dd($transaction->status);
            // LANGKAH 3: Redirect ke halaman pembayaran
            return redirect($result->getInvoiceUrl());

        } catch (\Exception $e) {
            return "Terjadi kesalahan: " . $e->getMessage();
        }
    }
    // public function createInvoice(Request $request)
    // {
    //     $vehicle = Vehicle::findOrFail($request->vehicle_id);

    //     try {
    //         $externalId = 'RENTAL-' . time() . '-' . $request->user()->id();

    //         // Panggil tanpa namespace lengkap karena sudah di-import
    //         $transaction = Transaction::create([
    //             'external_id'     => $externalId,
    //             'vehicle_id'      => $vehicle->id,
    //             'user_id'         => $request->user()->id(),
    //             'start_book_date' => $request->start_book_date,
    //             'end_book_date'   => $request->end_book_date,
    //             'status'          => 1,
    //         ]);

    //         Configuration::setXenditKey(config('services.xendit.secret_key'));

    //         // Panggil tanpa namespace lengkap
    //         $apiInstance = new InvoiceApi();
    //         $createInvoiceRequest = new CreateInvoiceRequest([
    //             'external_id' => $transaction->external_id,
    //             'amount'      => $vehicle->price,
    //             'currency'    => 'IDR',
    //             'description' => 'Pembayaran sewa untuk Vehicle ID: ' . $vehicle->id,
    //             'success_redirect_url' => route('payment.success'),
    //             'payer_email' => $request->user()->email,
    //         ]);

    //         $result = $apiInstance->createInvoice($createInvoiceRequest);

    //         return redirect($result->getInvoiceUrl());

    //     } catch (\Exception $e) {
    //         // Tangani error dari Xendit atau error umum lainnya
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    public function handleWebhook(Request $request)
    {
        // Log payload lengkap yang diterima
        Log::info('Webhook Payload Diterima:', $request->all());

        $externalId = $request->input('external_id');
        $statusPembayaran = $request->input('status'); // Xendit menggunakan 'status' untuk pembayaran

        Log::info("Webhook diproses. External ID: {$externalId}, Status Xendit: {$statusPembayaran}");

        $transaction = Transaction::where('external_id', $externalId)->first();

        if (!$transaction) {
            Log::warning("Transaksi dengan external_id: {$externalId} TIDAK DITEMUKAN di database.");
        } else {
            Log::info("Transaksi DITEMUKAN. ID Transaksi: {$transaction->id}, Status Saat Ini di DB: {$transaction->status}");

            if ($statusPembayaran === 'PAID') { // Gunakan perbandingan ketat untuk 'PAID'
                $transaction->status = 2;
                $transaction->save();
                Log::info("Transaksi {$externalId} BERHASIL DIUPDATE menjadi LUNAS (status 2).");
                // dd($transaction->status);
            } elseif ($statusPembayaran === 'CANCELLED' || $statusPembayaran === 'EXPIRED') {
                $transaction->status = 0; // Atau status lain untuk batal/kadaluarsa
                $transaction->save();
                Log::info("Transaksi {$externalId} DIBATALKAN/KADALUARSA (status 0). Status Xendit: {$statusPembayaran}");
            } else {
                Log::info("Status Xendit '{$statusPembayaran}' diterima untuk {$externalId}, tidak ada perubahan status transaksi.");
            }
        }

        // dd($transaction->status);

        return response()->json(['status' => 'success']);
    }
}
