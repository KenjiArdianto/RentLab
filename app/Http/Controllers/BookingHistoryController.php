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
        # $userId = Auth::id();
        $userId = 1;
        $ongoingStatus = [1, 2, 3];
        $historyStatus = [4, 5, 6, 7];
        $relations = [
            'vehicle:id,vehicle_name_id,vehicle_type_id,vehicle_transmission_id,price,main_image,year',
            'vehicle.vehicleName:id,name',
            'vehicle.vehicleType:id,type',
            'vehicle.vehicleImages:id,vehicle_id,image',
            'vehicle.vehicleTransmission:id,transmission',
            'driver:id,name',
            'transactionStatus:id,status',
            'vehicleReview',
        ];
        $searchKeyword = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $baseQuery = Transaction::with($relations)->where('user_id', $userId)->orderBy('start_book_date', 'desc');

        $baseQuery->when($searchKeyword, function ($query, $keyword) {
            return $query->whereHas('vehicle.vehicleName', function ($subQuery) use ($keyword) {
                $subQuery->where('name', 'like', "%{$keyword}%");
            });
        });
        $baseQuery->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
            return $query->where(function ($q) use ($dateFrom, $dateTo) {
                $q->where('start_book_date', '<=', $dateTo)
                ->where('end_book_date', '>=', $dateFrom);
            });
        });

        $ongoingTransactions = (clone $baseQuery)
            ->whereIn('transaction_status_id', $ongoingStatus)
            ->latest()
            ->paginate(10, ['*'], 'ongoingPage') 
            ->appends($request->query());

        $historyTransactions = (clone $baseQuery)
            ->whereIn('transaction_status_id', $historyStatus)
            ->latest()
            ->paginate(10, ['*'], 'historyPage') 
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
        // if (Auth::id() != $transaction->user_id) {
        //     abort(403, 'Unauthorized Action');
        // }

        $transaction->load(['user', 'vehicle', 'vehicle.vehicleName', 'vehicle.vehicleType']);
        $data = [
            'transaction' => $transaction
        ];
        $pdf = Pdf::loadView('receipts.pdf', $data);
        $fileName = 'receipt rentlab - ' . $transaction->id . ' - ' . Str::slug($transaction->user->name) . '.pdf';
        return $pdf->download($fileName);
    }

    public function cancel(Transaction $transaction)
    {
        
        // if (1 != $transaction->user_id) {
        // // if (Auth::id() != $transaction->user_id) { 
        //     abort(403, 'AKSI TIDAK DIIZINKAN');
        // }

        if (!in_array($transaction->transaction_status_id, [1, 2])) {
            return back()->with('error', __('booking-history.cancel_massage'));
        }

        $transaction->transaction_status_id = 7; 
        $transaction->save();

        return redirect()->route('booking.history')->with('success', __('booking-history.cancel_success_message'));
    }
}