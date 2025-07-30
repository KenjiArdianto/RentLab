<?php

namespace App\Http\Controllers;

// 1. Import kelas Request yang baru
use App\Http\Requests\CreateCheckoutInvoiceRequest;
use App\Http\Requests\ShowCheckoutRequest;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest as XenditInvoiceRequest;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    /**
     * Menampilkan halaman checkout.
     */
    public function show(ShowCheckoutRequest $request)
    {
        $validated = $request->validated();

        // dd($validated);

        // Gunakan '?? []' sebagai fallback jika cart_ids tidak ada (saat di-refresh)
        $selectedCartIds = $validated['cart_ids'] ?? [];

        // dd($selectedCartIds);

        // Jika tidak ada ID yang dipilih, kembalikan ke keranjang
        if (empty($selectedCartIds)) {
            return redirect()->route('cart')->with('info', 'Silakan pilih item dari keranjang Anda.');
        }

        // $user = Auth::user();
        $user = User::find(1); // Tetap gunakan ini untuk testing

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan.');
        }

        // Ambil item keranjang
        $cartItems = Cart::with(['vehicle.vehicleName', 'vehicle.vehicleType', 'vehicle.vehicleTransmission'])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedCartIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Item yang dipilih tidak valid atau sudah dihapus.');
        }

        $totalAmount = $cartItems->sum('subtotal');

        return view('checkout', [
            'user' => $user,
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Memproses checkout, membuat transaksi, dan membuat invoice Xendit.
     */
    public function createCheckoutInvoice(CreateCheckoutInvoiceRequest $request) // <-- 3. Gunakan CreateCheckoutInvoiceRequest
    {
        // Validasi sudah otomatis berjalan, ambil data yang bersih
        // dd($request);
        $validated = $request->validated();

        $selectedCartIds = $validated['cart_ids'];

        // $user = Auth::user();
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

        // KODE BARU (YANG MENJUMLAHKAN SUBTOTAL)

        // Langsung jumlahkan kolom 'subtotal' dari semua item keranjang yang dipilih
        $totalAmount = $cartItems->sum('subtotal');

        $transactionsToCreate = [];
        $commonExternalId = 'RENTAL-' . time() . '-' . $user->id;

        Log::info("MEMBUAT TRANSAKSI & INVOICE dengan external_id: [{$commonExternalId}]");

        // Loop ini tetap dibutuhkan untuk mempersiapkan data transaksi
        foreach ($cartItems as $cartItem) {
            $transactionsToCreate[] = [
                'vehicle_id' => $cartItem->vehicle_id,
                'user_id' => $user->id,
                'driver_id' => $cartItem->driver_id,
                'start_book_date' => $cartItem->start_date,
                'end_book_date' => $cartItem->end_date,
                'return_date' => $cartItem->end_date,
                'transaction_status_id' => 1,
                'external_id' => $commonExternalId,
                'price' => $cartItem->subtotal,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($totalAmount <= 0) {
            return back()->with('error', 'Total pembayaran tidak valid.');
        }

        DB::beginTransaction();

        try {
            // ==================================================================
            // LANGKAH 1: BUAT SATU RECORD DI TABEL `payments`
            // ==================================================================
            $payment = Payment::create([
                'external_id' => 'RENTAL-' . time() . '-' . $user->id,
                'user_id' => $user->id, // Pastikan ada kolom user_id di tabel payments
                'amount' => $totalAmount,
                'status' => 'PENDING',
            ]);

            // ==================================================================
            // LANGKAH 2: SIAPKAN DATA UNTUK TABEL `transactions`
            // ==================================================================
            $transactionsToCreate = [];
            foreach ($cartItems as $cartItem) {
                $transactionsToCreate[] = [
                    'payment_id' => $payment->id, // Gunakan ID dari payment yang baru dibuat
                    'vehicle_id' => $cartItem->vehicle_id,
                    'user_id' => $user->id,
                    'driver_id' => $cartItem->driver_id,
                    'start_book_date' => $cartItem->start_date,
                    'end_book_date' => $cartItem->end_date,
                    'return_date' => $cartItem->end_date,
                    'transaction_status_id' => 1, // Status "Pending"
                    'price' => $cartItem->subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Masukkan semua transaksi sekaligus
            Transaction::insert($transactionsToCreate);

            // ==================================================================
            // LANGKAH 3: BUAT INVOICE XENDIT
            // ==================================================================
            $apiInstance = new InvoiceApi();
            $createInvoiceRequest = new XenditInvoiceRequest([
                'external_id' => $payment->external_id, // Gunakan external_id dari record payment
                'amount' => $payment->amount,
                'currency' => 'IDR',
                'description' => 'Pembayaran Sewa Kendaraan (Order: ' . $payment->external_id . ')',
                'success_redirect_url' => route('payment.success'),
                'failure_redirect_url' => route('payment.failed'),
                'payer_email' => $user->email,
                'invoice_duration' => 120, // Durasi invoice 2 menit
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);

            // Hapus item dari keranjang setelah berhasil
            Cart::whereIn('id', $selectedCartIds)->where('user_id', $user->id)->delete();

            // Jika semua berhasil, commit perubahan ke database
            DB::commit();

            // Arahkan user ke halaman pembayaran Xendit
            return redirect()->away($result->getInvoiceUrl());

        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua query (rollback)
            DB::rollBack();

            Log::error('GENERAL ERROR saat membuat invoice:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Tangani error spesifik dari Xendit
            if ($e instanceof \Xendit\XenditSdkException) {
                Log::error('XENDIT SDK ERROR:', [
                    'message' => $e->getMessage(),
                    'full_error' => $e->getFullError()
                ]);
                return back()->with('error', 'Terjadi kesalahan saat membuat invoice Xendit. Silakan coba lagi.');
            }

            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    }

    // ... (Sisa method handleWebhook, success, dan failed tetap sama persis) ...
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();
        $externalId = $payload['external_id'] ?? null;
        $paymentStatus = $payload['status'] ?? null;

        Log::info('Webhook Xendit Diterima:', $payload);

        // 1. Validasi payload untuk memastikan data yang dibutuhkan ada
        if (!isset($payload['external_id'], $payload['status'])) {
            Log::warning('Webhook Ditolak: Payload tidak lengkap.', $payload);
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();

        if (!$payment) {
            Log::warning("Webhook Ditolak: Payment dengan external_id [{$externalId}] tidak ditemukan.");
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Hindari memproses webhook yang sama berulang kali
        if ($payment->status === 'PAID' && $paymentStatus === 'PAID') {
            Log::info("Webhook Dilewati: Payment [{$externalId}] sudah lunas.");
            return response()->json(['status' => 'success', 'message' => 'Already processed']);
        }

        // ==================================================================
        // LANGKAH 2: UPDATE TABEL `payments`
        // ==================================================================
        $payment->status = $paymentStatus;
        if ($paymentStatus === 'PAID') {
            $payment->paid_at = now();
            $payment->payment_method = $payload['payment_method'] ?? null;
            $payment->payment_channel = $payload['payment_channel'] ?? null;
        }
        $payment->save();

        // ==================================================================
        // LANGKAH 3: UPDATE SEMUA `transactions` YANG TERKAIT
        // ==================================================================
        $newStatusId = match ($paymentStatus) {
            'PAID' => 2,                // Lunas
            'EXPIRED', 'FAILED' => 7,   // Kadaluarsa atau Gagal
            default => null,
        };

        if ($newStatusId !== null) {
            $updatedRows = Transaction::where('payment_id', $payment->id)
                                    ->update(['transaction_status_id' => $newStatusId]);

            if ($updatedRows > 0) {
                Log::info("Webhook: {$updatedRows} transaksi untuk payment_id [{$payment->id}] berhasil diupdate menjadi status {$newStatusId}.");
            }
        } else {
            Log::info("Webhook: Status '{$paymentStatus}' untuk external_id [{$externalId}] diabaikan untuk update transaksi.");
        }

        return response()->json(['status' => 'success']);
    }


    public function success()
    {
        $title = __('payment.title.success');
        $message = __('payment.status.success_message');
        $homeUrl = route('vehicle.display', ['locale' => app()->getLocale()]);
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
        $homeUrl = route('vehicle.display', ['locale' => app()->getLocale()]);
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
