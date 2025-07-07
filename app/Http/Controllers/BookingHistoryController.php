<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BookingHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat pemesanan milik pengguna yang sedang login.
     * Mengambil data transaksi yang sedang berjalan dan yang sudah selesai.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // 1. Dapatkan ID pengguna yang sedang login untuk keamanan data
        # $userId = Auth::id();
        $userId = 1;

        // 2. Definisikan status transaksi berdasarkan seeder
        // On Payment(1), On Booking(2), Car Taken(3)
        $ongoingStatus = [1, 2, 3];

        // Review By Admin(4), Review By User(5), Closed(6), Canceled(7)
        $historyStatus = [4, 5, 6, 7];

        // 3. Definisikan relasi yang akan dimuat (Eager Loading) untuk efisiensi
        // Ini untuk menghindari masalah N+1 Query
        $relations = [
            // Muat relasi vehicle dan pilih hanya kolom yang diperlukan
            'vehicle:id,vehicle_name_id,vehicle_type_id,vehicle_transmission_id,price,main_image',
            // Dari vehicle, muat relasi turunannya
            'vehicle.vehicleName:id,name',
            'vehicle.vehicleType:id,type',
            'vehicle.vehicleImages:id,vehicle_id,image',
            'vehicle.vehicleTransmission:id,transmission',
            // Muat relasi driver
            'driver:id,name',
            'transactionStatus:id,status',
            'userReview'
        ];
        // 4. Ambil data transaksi yang sedang berjalan (ongoing)
        $ongoingTransactions = Transaction::with($relations)
                                          ->where('user_id', $userId)
                                          ->whereIn('status', $ongoingStatus)
                                          ->latest() // Urutkan dari yang terbaru
                                          ->get();

        // 5. Ambil data riwayat transaksi (history)
        $historyTransactions = Transaction::with($relations)
                                          ->where('user_id', $userId)
                                          ->whereIn('status', $historyStatus)
                                          ->latest() // Urutkan dari yang terbaru
                                          ->get();

        // 6. Kirim kedua data ke view
        return view('booking-history', compact('ongoingTransactions', 'historyTransactions'));
    }

    public function show(Transaction $transaction)
    {
        // if (Auth::id() != $transaction->user_id) { abort(403); }
        
        $transaction->load(['user', 'vehicle', 'vehicle.vehicleName', 'vehicle.vehicleType', 'vehicle.vehicleTransmission', 'driver']);
        return view('booking-detail', compact('transaction'));
    }

    public function downloadReceipt(Transaction $transaction)
    {
        // Pastikan pengguna yang login adalah pemilik transaksi (keamanan tambahan)
        // if (Auth::id() != $transaction->user_id) {
        //     abort(403, 'Unauthorized Action');
        // }

        // Muat data relasi yang dibutuhkan untuk ditampilkan di PDF
        $transaction->load(['user', 'vehicle', 'vehicle.vehicleName', 'vehicle.vehicleType']);

        // Data yang akan dikirim ke view PDF
        $data = [
            'transaction' => $transaction
        ];

        // Buat PDF dari blade view 'receipt-pdf.blade.php'
        $pdf = Pdf::loadView('receipts.pdf', $data);

        // Beri nama file dan langsung download di browser
        $fileName = 'receipt rentlab - ' . $transaction->id . ' - ' . Str::slug($transaction->user->name) . '.pdf';

        // Download dengan nama file baru
        return $pdf->download($fileName);
    }
}