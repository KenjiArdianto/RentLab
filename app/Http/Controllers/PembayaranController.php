<?php

namespace App\Http\Controllers;

// Import kelas yang diperlukan
use App\Http\Requests\CreateCheckoutInvoiceRequest;
use App\Http\Requests\ShowCheckoutRequest;
use App\Models\Cart;
use App\Models\Driver;
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
use Carbon\Carbon; // Pastikan Carbon di-import untuk kalkulasi tanggal

class PembayaranController extends Controller
{
    /**
     * Constructor untuk mengatur API Key Xendit.
     */
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    /**
     * Menampilkan halaman checkout atau memproses data dari keranjang.
     * Metode ini sekarang menangani GET dan POST dengan benar menggunakan session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Request $request)
    {
        // Skenario 1: Jika ini adalah request POST dari halaman keranjang
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'cart_ids' => 'required|array|min:1',
                'cart_ids.*' => 'exists:carts,id',
            ]);
            // Simpan ID ke session untuk digunakan nanti
            session(['selected_cart_ids' => $validated['cart_ids']]);
            // Redirect ke metode GET dari route ini sendiri (Pola PRG)
            return redirect()->route('checkout.show');
        }

        // Skenario 2: Jika ini adalah request GET (untuk menampilkan halaman)
        // Ambil ID dari session yang disimpan oleh skenario 1 atau dari redirect error.
        $selectedCartIds = session('selected_cart_ids', []);

        if (empty($selectedCartIds)) {
            return redirect()->route('cart')->with('info', 'Silakan pilih item dari keranjang Anda terlebih dahulu.');
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan.');
        }

        $cartItems = Cart::with(['vehicle.vehicleName', 'vehicle.vehicleType', 'vehicle.vehicleTransmission'])
            ->where('user_id', $user->id)
            ->whereIn('id', $selectedCartIds)
            ->get();

        // Jika item di session tidak valid (misalnya, sudah dihapus), bersihkan dan redirect
        if ($cartItems->count() !== count($selectedCartIds)) {
            session()->forget('selected_cart_ids');
            return redirect()->route('cart')->with('error', 'Beberapa item di keranjang Anda tidak lagi valid.');
        }

        $totalAmount = $cartItems->sum('subtotal');

        return view('checkout', [
            'user' => $user,
            'cartItems' => $cartItems,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Memproses checkout, menugaskan sopir, dan menghasilkan invoice Xendit.
     */
    public function createCheckoutInvoice(CreateCheckoutInvoiceRequest $request)
    {
        $validated = $request->validated();
        // Ambil ID dari form yang disubmit, bukan dari session
        $selectedCartIds = $validated['cart_ids'];
        $withDriverData = $validated['with_driver'] ?? [];

        /** @var \App\Models\User $user */
        $user = Auth::user();
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

        // Validasi ketersediaan kendaraan sebelum checkout
        $unavailableCartIds = [];
        foreach ($cartItems as $cartItem) {
            $startDate = Carbon::parse($cartItem->start_date);
            $endDate = Carbon::parse($cartItem->end_date);

            $isVehicleBooked = Transaction::where('vehicle_id', $cartItem->vehicle_id)
                ->whereIn('transaction_status_id', [1, 2, 3, 4, 5]) // Pending, Lunas, Diambil, Berlangsung, Selesai
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_book_date', '<=', $endDate)
                          ->where('end_book_date', '>=', $startDate);
                })
                ->exists();

            if ($isVehicleBooked) {
                $unavailableCartIds[] = $cartItem->id;
            }
        }

        if (!empty($unavailableCartIds)) {
            Log::info('cart is conflict', ['unavailable_cart_ids' => $unavailableCartIds]);
            // Redirect kembali ke halaman checkout dengan membawa data error
            return redirect()->route('checkout.show')->withInput()
                ->with('error', 'Maaf, satu atau lebih kendaraan pilihan Anda baru saja dipesan. Silakan periksa kembali pesanan Anda.')
                ->with('unavailable_cart_ids', $unavailableCartIds);
        }


        // Mulai transaksi database di awal untuk mengunci resource
        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $transactionsData = [];
            $driverFeeConstant = 50000;
            $assignedDriversInThisRequest = [];

            // Iterasi untuk setiap item di dalam transaksi database
            foreach ($cartItems as $cartItem) {
                $basePrice = $cartItem->subtotal;
                $driverFee = 0;
                $assignedDriverId = null;

                if (isset($withDriverData[$cartItem->id]) && $withDriverData[$cartItem->id]) {
                    $startDate = Carbon::parse($cartItem->start_date);
                    $endDate = Carbon::parse($cartItem->end_date);
                    $days = $startDate->diffInDays($endDate) + 1;
                    $driverFee = $days * $driverFeeConstant;

                    $assignedDriverId = $this->findAndLockAvailableDriver($startDate, $endDate, $assignedDriversInThisRequest);

                    if (!$assignedDriverId) {
                        throw new \Exception('Maaf, jumlah sopir yang tersedia tidak mencukupi untuk pesanan Anda pada tanggal tersebut.');
                    }

                    $assignedDriversInThisRequest[] = $assignedDriverId;
                }

                $totalAmount += $basePrice + $driverFee;

                $transactionsData[] = [
                    'vehicle_id' => $cartItem->vehicle_id,
                    'user_id' => $user->id,
                    'driver_id' => $assignedDriverId,
                    'start_book_date' => $cartItem->start_date,
                    'end_book_date' => $cartItem->end_date,
                    'return_date' => $cartItem->end_date,
                    'transaction_status_id' => 1,
                    'price' => $basePrice,
                    'driver_fee' => $driverFee,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if ($totalAmount <= 0) {
                throw new \Exception('Total pembayaran tidak valid.');
            }

            $payment = Payment::create([
                'external_id' => 'RENTAL-' . time() . '-' . $user->id,
                'user_id' => $user->id,
                'amount' => $totalAmount,
                'status' => 'PENDING',
            ]);

            $finalTransactions = array_map(fn($tx) => array_merge($tx, ['payment_id' => $payment->id]), $transactionsData);
            Transaction::insert($finalTransactions);

            $apiInstance = new InvoiceApi();
            $createInvoiceRequest = new XenditInvoiceRequest([
                'external_id' => $payment->external_id,
                'amount' => $payment->amount,
                'currency' => 'IDR',
                'description' => 'Pembayaran Sewa Kendaraan (Order: ' . $payment->external_id . ')',
                'success_redirect_url' => route('payment.success'),
                'failure_redirect_url' => route('payment.failed'),
                'payer_email' => $user->email,
                'invoice_duration' => 5,
            ]);

            $result = $apiInstance->createInvoice($createInvoiceRequest);
            $payment->url = $result->getInvoiceUrl();
            $payment->save();

            // Hapus item dari keranjang dan session
            Cart::whereIn('id', $selectedCartIds)->where('user_id', $user->id)->delete();
            session()->forget('selected_cart_ids');

            DB::commit();

            activity('admin_payment_create')->causedBy($user)->performedOn($payment)->log("Created new payment for external_id '{$payment->external_id}'");
            return redirect()->away($payment->url);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PAYMENT_CREATION_FAILED:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            activity('admin_payment_failed')->causedBy($user)->log("Payment creation failed: " . $e->getMessage());

            if ($e instanceof \Xendit\XenditSdkException) {
                return back()->with('error', 'Terjadi kesalahan saat membuat invoice Xendit. Silakan coba lagi.');
            }

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cari dan KUNCI sopir yang tersedia pada rentang tanggal tertentu.
     */
    private function findAndLockAvailableDriver(Carbon $startDate, Carbon $endDate, array $excludeDriverIds = [])
    {
        $busyDriverIds = Transaction::whereNotNull('driver_id')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_book_date', '<=', $endDate)
                      ->where('end_book_date', '>=', $startDate);
                });
            })
            ->whereIn('transaction_status_id', [1, 2, 3, 4, 5])
            ->pluck('driver_id')
            ->unique();

        $allExcludedIds = $busyDriverIds->merge($excludeDriverIds)->unique();

        $availableDriver = Driver::whereNotIn('id', $allExcludedIds)
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->first();

        return $availableDriver ? $availableDriver->id : null;
    }


    /**
     * Menangani webhook yang masuk dari Xendit.
     */
    public function handleWebhook(Request $request)
    {
        // ... (method ini tidak berubah)
        $payload = $request->all();
        $externalId = $payload['external_id'] ?? null;
        $paymentStatus = $payload['status'] ?? null;

        if (!$externalId || !$paymentStatus) {
            Log::warning('Webhook Rejected: Incomplete payload.', $payload);
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        $payment = Payment::where('external_id', $externalId)->first();

        if (!$payment) {
            Log::warning("Webhook Rejected: Payment with external_id [{$externalId}] not found.");
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        if ($payment->status === 'PAID') {
            Log::info("Webhook Skipped: Payment [{$externalId}] is already marked as PAID.");
            return response()->json(['status' => 'success', 'message' => 'Already processed']);
        }

        if ($paymentStatus === 'PAID') {
            DB::beginTransaction();
            try {
                $transactions = Transaction::where('payment_id', $payment->id)->lockForUpdate()->get();
                $reassignedDriversInThisWebhook = [];

                foreach ($transactions as $transaction) {
                    if ($transaction->driver_id) {
                        $startDate = Carbon::parse($transaction->start_book_date);
                        $endDate = Carbon::parse($transaction->end_book_date);

                        $isOriginalDriverBusy = Transaction::where('driver_id', $transaction->driver_id)
                            ->where('id', '!=', $transaction->id)
                            ->whereIn('transaction_status_id', [2, 3, 4, 5])
                            ->where(function ($q) use ($startDate, $endDate) {
                                $q->where('start_book_date', '<=', $endDate)
                                  ->where('end_book_date', '>=', $startDate);
                            })->exists();

                        if ($isOriginalDriverBusy) {
                            Log::warning("Driver Reassignment: Driver {$transaction->driver_id} is busy for transaction {$transaction->id}. Finding a new one.");
                            $newDriverId = $this->findAndLockAvailableDriver($startDate, $endDate, $reassignedDriversInThisWebhook);

                            if ($newDriverId) {
                                $transaction->driver_id = $newDriverId;
                                $transaction->save();
                                $reassignedDriversInThisWebhook[] = $newDriverId;
                                Log::info("Driver Reassignment: Successfully reassigned driver {$newDriverId} to transaction {$transaction->id}.");
                            } else {
                                Log::critical("CRITICAL: No replacement driver found for transaction {$transaction->id} which has been PAID. Manual intervention required!");
                            }
                        }
                    }
                }

                $payment->status = 'PAID';
                $payment->paid_at = now();
                $payment->payment_method = $payload['payment_method'] ?? null;
                $payment->payment_channel = $payload['payment_channel'] ?? null;
                $payment->save();

                Transaction::where('payment_id', $payment->id)->update(['transaction_status_id' => 2]);

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to process PAID webhook for external_id {$externalId}: " . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Failed to process payment confirmation.'], 500);
            }

        } elseif (in_array($paymentStatus, ['EXPIRED', 'FAILED'])) {
            $payment->status = $paymentStatus;
            $payment->save();
            Transaction::where('payment_id', $payment->id)->update(['transaction_status_id' => 7]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Menampilkan halaman sukses pembayaran.
     */
    public function success()
    {
        // ... (method ini tidak berubah)
        $title = __('payment.title.success');
        $message = __('payment.status.success_message');
        $homeUrl = route('vehicle.display');
        $buttonText = __('payment.buttons.back_to_home');

        return view('webview.payment-status', compact('title', 'message', 'homeUrl', 'buttonText'))->with('status_class', 'success');
    }

    /**
     * Menampilkan halaman gagal/kadaluarsa pembayaran.
     */
    public function failed()
    {
        // ... (method ini tidak berubah)
        $title = __('payment.title.failed');
        $message = __('payment.status.failed_message');
        $homeUrl = route('cart');
        $buttonText = __('payment.buttons.try_again');

        return view('webview.payment-status', compact('title', 'message', 'homeUrl', 'buttonText'))->with('status_class', 'failed');
    }
}
