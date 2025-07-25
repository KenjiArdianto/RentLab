<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
    public function createCheckoutInvoice(Request $request)
    {
        // Asumsi $request->cart_ids adalah array dari ID item keranjang yang dipilih
        // $request->cart_ids = [1, 2, 3];
        // $selectedCartIds = $request->input('cart_ids');
        $selectedCartIds = [1,2,3];
        $userIdForTesting = 1;

        if (empty($selectedCartIds)) {
            return back()->with('error', 'Tidak ada item keranjang yang dipilih.');
        }

        $totalAmount = 0;
        $transactionsToCreate = [];
        // $commonExternalId = 'RENTAL-' . time() . '-' . $request->user()->id; // ID unik untuk seluruh checkout ini

        $commonExternalId = 'RENTAL-' . time() . '-' . str()->random();

        try {
            // Loop melalui setiap ID keranjang yang dipilih
            foreach ($selectedCartIds as $cartId) {
                $cartItem = Cart::with('vehicle')->findOrFail($cartId); // Ambil item keranjang beserta detail mobil
                // dd($cartItem);
                if (!$cartItem->vehicle) {
                    Log::error("Vehicle not found for cart item ID: {$cartId}");
                    continue; // Lewati item jika kendaraan tidak ditemukan
                }

                $vehicle = $cartItem->vehicle;
                $startDate = new \DateTime($cartItem->start_date);
                $endDate = new \DateTime($cartItem->end_date);
                $duration = $startDate->diff($endDate)->days;
                if ($duration == 0) $duration = 1; // Asumsi minimal 1 hari sewa

                $subtotal = $vehicle->price * $duration; // Hitung subtotal untuk item ini
                $totalAmount += $subtotal; // Tambahkan ke total keseluruhan

                $transactionsToCreate[] = [
                    'vehicle_id'      => $vehicle->id,
                    // 'user_id'         => $request->user()->id,
                    'user_id'         => $userIdForTesting,
                    'driver_id'       => 1, // Sesuaikan jika ada driver yang dipilih di keranjang
                    'start_book_date' => $cartItem->start_date,
                    'end_book_date'   => $cartItem->end_date,
                    'return_date'     => $cartItem->end_date,
                    'status'          => 1, // Status pending
                    'external_id'     => $commonExternalId, // Semua item dalam checkout ini pakai external_id yang sama
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
                // $userIdForTesting += 1;
            }

            // Pastikan total amount tidak nol
            if ($totalAmount <= 0) {
                return back()->with('error', 'Total pembayaran tidak valid.');
            }

            // dd($transactionsToCreate);

            // Simpan semua transaksi ke database
            // Ini akan membuat banyak baris di tabel `transactions`
            Transaction::insert($transactionsToCreate);

            // Buat invoice Xendit
            Configuration::setXenditKey(config('services.xendit.secret_key'));
            $apiInstance = new InvoiceApi();

            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => $commonExternalId, // ID unik untuk seluruh checkout
                'amount'      => $totalAmount,      // Jumlah total pembayaran
                'currency'    => 'IDR',
                'description' => 'Pembayaran Sewa Banyak Mobil (Order: ' . $commonExternalId . ')',
                'success_redirect_url' => route('payment.success'),
                // 'payer_email' => $request->user()->email, // Asumsi user punya email
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            // Opsional: Hapus item dari keranjang setelah berhasil dibuat transaksinya
            Cart::whereIn('id', $selectedCartIds)->delete();

            return redirect($result->getInvoiceUrl());

        } catch (\Exception $e) {
            Log::error('Error creating multi-transaction invoice:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memproses checkout: ' . $e->getMessage());
        }
    }


    public function handleWebhook(Request $request)
    {
        // Log payload lengkap yang diterima
        Log::info('Webhook Payload Diterima:', $request->all());

        $externalId = $request->input('external_id');
        $statusPembayaran = $request->input('status');

        Log::info("Webhook diproses. External ID: {$externalId}, Status Xendit: {$statusPembayaran}");

        // --- PERUBAHAN PENTING DI SINI ---
        // Gunakan ->get() untuk mendapatkan semua transaksi dengan external_id yang sama
        $transactions = Transaction::where('external_id', $externalId)->get();
        // dd($transactions);
        // Cek apakah ada transaksi yang ditemukan
        if ($transactions->isEmpty()) {
            Log::warning("Transaksi dengan external_id: {$externalId} TIDAK DITEMUKAN di database.");
            return response()->json(['status' => 'error', 'message' => 'Transactions not found'], 404);
        } else{
            Log::info("Transaksi dengan external_id: {$externalId} DITEMUKAN di database");
        }

        // Lakukan perulangan untuk mengupdate setiap transaksi yang ditemukan
        foreach ($transactions as $transaction) { // <-- LOOPING DI SINI
            Log::info("Transaksi DITEMUKAN. ID Transaksi: {$transaction->id}, External ID: {$externalId}, Status Saat Ini di DB: {$transaction->status}");

            if ($statusPembayaran === 'PAID') {
                if ($transaction->status != 2) { // Hanya update jika status belum lunas
                    $transaction->status = 2; // Lunas
                    $transaction->save();
                    Log::info("Transaksi {$transaction->id} (External ID: {$externalId}) BERHASIL DIUPDATE menjadi LUNAS (status 2).");
                } else {
                    Log::info("Transaksi {$transaction->id} (External ID: {$externalId}) sudah lunas, tidak perlu update lagi.");
                }
            } elseif ($statusPembayaran === 'CANCELLED' || $statusPembayaran === 'EXPIRED') {
                if ($transaction->status != 0) { // Hanya update jika status belum batal/kadaluarsa
                    $transaction->status = 0; // Batal/Kadaluarsa
                    $transaction->save();
                    Log::info("Transaksi {$transaction->id} (External ID: {$externalId}) DIBATALKAN/KADALUARSA (status 0). Status Xendit: {$statusPembayaran}");
                } else {
                    Log::info("Transaksi {$transaction->id} (External ID: {$externalId}) sudah batal/kadaluarsa, tidak perlu update lagi.");
                }
            } else {
                Log::info("Status Xendit '{$statusPembayaran}' diterima untuk {$externalId} (Transaksi {$transaction->id}), tidak ada perubahan status.");
            }
        } // <-- AKHIR LOOPING

        return response()->json(['status' => 'success']);
    }
}
