<?php

namespace App\Http\Controllers;

// 1. Import kelas Request yang baru
use App\Http\Requests\CreateCheckoutInvoiceRequest;
use App\Http\Requests\ShowCheckoutRequest;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest as XenditInvoiceRequest; // Ganti nama alias agar tidak konflik

class PembayaranController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function show(ShowCheckoutRequest $request) // <-- 2. Gunakan ShowCheckoutRequest
    {
        // Validasi sudah otomatis berjalan, ambil data yang bersih
        $validated = $request->validated();

        $userId = 1;

        $selectedCartIds = $validated['cart_ids'] ?? [];

        $cartItemsQuery = Cart::with('vehicle.vehicleName')
            ->where('user_id', $userId);

        if (!empty($selectedCartIds)) {
            $cartItemsQuery->whereIn('id', $selectedCartIds);
        }

        $cartItems = $cartItemsQuery->get();

        if ($cartItems->isEmpty()) {
            return view('checkout', [
                'cartItems' => collect(),
                'totalAmount' => 0,
                'selectedCartIds' => [],
            ]);
        }

        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $startDate = new \DateTime($item->start_date);
            $endDate = new \DateTime($item->end_date);
            $duration = $startDate->diff($endDate)->days + 1;
            $item->duration = $duration;
            $item->subtotal = $item->vehicle->price * $duration;
            $totalAmount += $item->subtotal;
        }

        return view('checkout', [
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
            'selectedCartIds' => $selectedCartIds ?: $cartItems->pluck('id')->all(),
        ]);
    }

    /**
     * Memproses checkout, membuat transaksi, dan membuat invoice Xendit.
     */
    public function createCheckoutInvoice(CreateCheckoutInvoiceRequest $request) // <-- 3. Gunakan CreateCheckoutInvoiceRequest
    {
        // Validasi sudah otomatis berjalan, ambil data yang bersih
        $validated = $request->validated();

        $selectedCartIds = $validated['cart_ids'];

        $user = User::find(1);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan pembayaran.');
        }

        $cartItems = Cart::with('vehicle')
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedCartIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Item keranjang tidak valid atau bukan milik Anda.');
        }

        $totalAmount = 0;
        $transactionsToCreate = [];
        $commonExternalId = 'RENTAL-' . time() . '-' . $user->id;

        Log::info("MEMBUAT TRANSAKSI & INVOICE dengan external_id: [{$commonExternalId}]");

        foreach ($cartItems as $cartItem) {
            $startDate = new \DateTime($cartItem->start_date);
            $endDate = new \DateTime($cartItem->end_date);
            $duration = $startDate->diff($endDate)->days + 1;
            $subtotal = $cartItem->vehicle->price * $duration;
            $totalAmount += $subtotal;

            $transactionsToCreate[] = [
                'vehicle_id' => $cartItem->vehicle_id,
                'user_id' => $user->id,
                'driver_id' => $cartItem->driver_id,
                'start_book_date' => $cartItem->start_date,
                'end_book_date' => $cartItem->end_date,
                'return_date' => $cartItem->end_date,
                'transaction_status_id' => 1,
                'external_id' => $commonExternalId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($totalAmount <= 0) {
            return back()->with('error', 'Total pembayaran tidak valid.');
        }

        try {
            Transaction::insert($transactionsToCreate);

            $apiInstance = new InvoiceApi();
            // Gunakan alias XenditInvoiceRequest
            $createInvoiceRequest = new XenditInvoiceRequest([
                'external_id' => $commonExternalId,
                'amount' => $totalAmount,
                'currency' => 'IDR',
                'description' => 'Pembayaran Sewa Kendaraan (Order: ' . $commonExternalId . ')',
                'success_redirect_url' => route('payment.success', ['locale' => app()->getLocale()]),
                'failure_redirect_url' => route('payment.failed', ['locale' => app()->getLocale()]),
                'payer_email' => $user->email,
                'invoice_duration' => 60,
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            Cart::whereIn('id', $selectedCartIds)->where('user_id', $user->id)->delete();

            return redirect()->away($result->getInvoiceUrl());

        } catch (\Xendit\XenditSdkException $e) { // Tangkap exception spesifik dari Xendit
            Log::error('XENDIT SDK ERROR:', [
                'message' => $e->getMessage(),
                'full_error' => $e->getFullError() // Log ini akan memberikan detail lengkap dari Xendit
            ]);
            return back()->with('error', 'Terjadi kesalahan spesifik saat membuat invoice Xendit.');
        } catch (\Exception $e) { // Tangkap error umum lainnya
            Log::error('GENERAL ERROR saat membuat invoice:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan umum saat memproses pembayaran.');
        }
    }

    // ... (Sisa method handleWebhook, success, dan failed tetap sama persis) ...
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Webhook Xendit Diterima:', $payload);

        // 1. Validasi payload untuk memastikan data yang dibutuhkan ada
        if (!isset($payload['external_id'], $payload['status'])) {
            Log::warning('Webhook Ditolak: Payload tidak lengkap.', $payload);
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        $externalId = $payload['external_id'];
        $paymentStatus = $payload['status'];
        Log::info("$paymentStatus");

        // 2. Gunakan 'match' expression (PHP 8+) untuk menentukan status baru
        // Ini lebih ringkas dan mudah dibaca daripada if/elseif
        $newStatusId = match ($paymentStatus) {
            'PAID' => 2,                // Lunas
            'EXPIRED', 'FAILED' => 7,   // Kadaluarsa atau Gagal
            default => null,            // Abaikan status lain yang tidak kita kenali
        };

        // 3. Lakukan update hanya jika statusnya relevan
        if ($newStatusId !== null) {
            // 4. Gunakan satu query UPDATE untuk semua transaksi yang cocok.
            // Ini jauh lebih efisien daripada mengambil data lalu melakukan loop.
            $updatedRows = Transaction::where('external_id', $externalId)
                                    ->update(['transaction_status_id' => $newStatusId]);

            if ($updatedRows > 0) {
                Log::info("Webhook: {$updatedRows} transaksi untuk external_id [{$externalId}] berhasil diupdate menjadi status {$newStatusId}.");
            } else {
                Log::warning("Webhook: Tidak ada transaksi yang diupdate untuk external_id [{$externalId}]. Mungkin sudah diupdate atau tidak ditemukan.");
            }
        } else {
            Log::info("Webhook: Status '{$paymentStatus}' untuk external_id [{$externalId}] diabaikan.");
        }

        return response()->json(['status' => 'success']);
    }


    public function success()
    {
        $title = __('payment.title.success');
        $message = __('payment.status.success_message');
        $homeUrl = route('checkout', ['locale' => app()->getLocale()]);
        $buttonText = __('payment.buttons.back_to_home');

        return "
            <div style='font-family: sans-serif; text-align: center; padding: 40px;'>
                <h1>{$title}</h1>
                <p>{$message}</p>
                <a href='{$homeUrl}' style='display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>{$buttonText}</a>
            </div>
        ";
    }

    public function failed()
    {
        $title = __('payment.title.failed');
        $message = __('payment.status.failed_message');
        $homeUrl = route('checkout', ['locale' => app()->getLocale()]);
        $buttonText = __('payment.buttons.try_again');

        return "
            <div style='font-family: sans-serif; text-align: center; padding: 40px;'>
                <h1>{$title}</h1>
                <p>{$message}</p>
                <a href='{$homeUrl}' style='display: inline-block; padding: 10px 20px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 5px;'>{$buttonText}</a>
            </div>
        ";
    }
}
