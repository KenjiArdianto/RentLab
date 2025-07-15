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
        // Dapatkan ID pengguna yang sedang login untuk keamanan data
        # $userId = Auth::id();
        $userId = 1;

        // Definisikan status transaksi berdasarkan seeder

        // On Payment(1), On Booking(2), Car Taken(3)
        $ongoingStatus = [1, 2, 3];

        // Review By Admin(4), Review By User(5), Closed(6), Canceled(7)
        $historyStatus = [4, 5, 6, 7];

        // Definisikan relasi yang akan dimuat (Eager Loading) untuk efisiensi
        $relations = [
            'vehicle:id,vehicle_name_id,vehicle_type_id,vehicle_transmission_id,price,main_image',
            'vehicle.vehicleName:id,name',
            'vehicle.vehicleType:id,type',
            'vehicle.vehicleImages:id,vehicle_id,image',
            'vehicle.vehicleTransmission:id,transmission',
            'driver:id,name',
            'transactionStatus:id,status',
            'vehicleReview'
        ];

        // Ambil input pencarian dari form
        $searchKeyword = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // Buat query dasar yang akan digunakan untuk kedua tab
        $baseQuery = Transaction::with($relations)->where('user_id', $userId)->latest();

        // Terapkan filter PENCARIAN NAMA KENDARAAN (jika ada)
        $baseQuery->when($searchKeyword, function ($query, $keyword) {
            return $query->whereHas('vehicle.vehicleName', function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%");
            });
        });

        // Terapkan filter PENCARIAN TANGGAL (jika ada)
        $baseQuery->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
            // Cari transaksi yang periode booking-nya BERSINGGUNGAN dengan rentang yang dipilih. Artinya, transaksi yang mulai SEBELUM rentang berakhir DAN berakhir SETELAH rentang dimulai.
            return $query->where(function ($q) use ($dateFrom, $dateTo) {
                $q->where('start_book_date', '<=', $dateTo)
                ->where('end_book_date', '>=', $dateFrom);
            });
        });

        // Clone query dasar untuk masing-masing tab dan ambil datanya
        $ongoingTransactions = (clone $baseQuery)
            ->whereIn('status', $ongoingStatus)
            ->latest()
            ->paginate(10, ['*'], 'ongoingPage') // Gunakan nama halaman custom
            ->appends($request->query());

        $historyTransactions = (clone $baseQuery)
            ->whereIn('status', $historyStatus)
            ->latest()
            ->paginate(10, ['*'], 'historyPage') // Gunakan nama halaman custom
            ->appends($request->query());

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