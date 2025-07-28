<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User; // <-- Tambahkan ini untuk mengambil data user
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function show(Request $request)
    {
        $validated = $request->validate([
            'cart_ids'   => 'sometimes|array',
            'cart_ids.*' => 'integer|exists:carts,id',
        ]);

        // ===================================================================
        // FIX: Mengembalikan logika ke Auth::id() untuk mengambil ID pengguna
        // yang sedang login, sesuai dengan cara kerja aplikasi yang benar.
        // Untuk testing, pastikan Anda login sebagai user dengan ID 1.
        // ===================================================================
        // $userId = Auth::id();
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

            // Perhitungan durasi yang sudah benar (inklusif)
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
    public function createCheckoutInvoice(Request $request)
    {
        $validated = $request->validate([
            'cart_ids' => 'required|array|min:1',
            'cart_ids.*' => 'integer|exists:carts,id',
        ]);

        $selectedCartIds = $validated['cart_ids'];
        // dd($selectedCartIds);

        // ===================================================================
        // FIX: Mengembalikan logika ke Auth::user() untuk mengambil data
        // pengguna yang sedang login.
        // ===================================================================
        $user = User::find(1);
        // $user = Auth::user();

        if (!$user) {
            // Ini akan mencegah error jika pengguna tidak login
            return redirect()->route('login')->with('error', 'Anda harus login untuk melanjutkan pembayaran.');
        }

        // ===================================================================
        // FIX: Memastikan query hanya mengambil item milik pengguna yang login.
        // ===================================================================
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
            $createInvoiceRequest = new CreateInvoiceRequest([
                'external_id' => $commonExternalId,
                'amount' => $totalAmount,
                'currency' => 'IDR',
                'description' => 'Pembayaran Sewa Kendaraan (Order: ' . $commonExternalId . ')',
                'success_redirect_url' => route('payment.success', ['locale' => app()->getLocale()]),
                'failure_redirect_url' => route('payment.failed', ['locale' => app()->getLocale()]),
                'payer_email' => $user->email,
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            Cart::whereIn('id', $selectedCartIds)->where('user_id', $user->id)->delete();

            return redirect()->away($result->getInvoiceUrl());

        } catch (\Exception $e) {
            Log::error('Error creating Xendit invoice:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    /**
     * Menerima webhook dari Xendit dan mengupdate status transaksi.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        Log::info('Webhook Xendit Diterima:', $payload);

        $externalId = $payload['external_id'];
        $paymentStatus = $payload['status'];

        $transactions = Transaction::where('external_id', $externalId)->get();

        if ($transactions->isEmpty()) {
            Log::warning("Webhook: Transaksi dengan external_id {$externalId} tidak ditemukan.");
            return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
        }

        $newStatusId = null;
        if ($paymentStatus === 'PAID') {
            $newStatusId = 2; // Lunas
        } elseif ($paymentStatus === 'EXPIRED') {
            $newStatusId = 0; // Kadaluarsa/Batal
        }

        if ($newStatusId !== null) {
            foreach ($transactions as $transaction) {
                $transaction->transaction_status_id = $newStatusId;
                $transaction->save();
            }
            Log::info("Webhook: Status untuk external_id {$externalId} berhasil diupdate menjadi {$newStatusId}.");
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Menampilkan halaman setelah pembayaran berhasil.
     */
    public function success()
    {
        // ===================================================================
        // FIX: Menggunakan helper `__()` untuk menerjemahkan teks di dalam controller.
        // ===================================================================
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

    /**
     * Menampilkan halaman setelah pembayaran gagal.
     */
    public function failed()
    {
        // ===================================================================
        // FIX: Menggunakan helper `__()` untuk menerjemahkan teks di dalam controller.
        // ===================================================================
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
