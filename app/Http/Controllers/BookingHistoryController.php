<?php
namespace App\Http\Controllers;

use App\Http\Requests\CancelBookingRequest;
use App\Http\Requests\ExpireBookingRequest;
use App\Http\Requests\FilterBookingHistoryRequest;
use App\Http\Requests\ViewBookingHistoryRequest;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BookingHistoryController extends Controller
{
    public function index(FilterBookingHistoryRequest $request): View
    {
        $validated = $request->validated();
        $userId = Auth::id();
        $perPage = 10;

        $relations = [
            'payment',
            'vehicle:id,vehicle_name_id,vehicle_type_id,vehicle_transmission_id,price,main_image,year',
            'vehicle.vehicleName:id,name',
            'vehicle.vehicleType:id,type',
            'vehicle.vehicleTransmission:id,transmission',
            'driver:id,name',
            'transactionStatus:id,status',
            'vehicleReview',
            'userReview',
        ];

        $baseQuery = Transaction::with($relations)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        $baseQuery->when($validated['history_search'] ?? null, function ($query, $keyword) {
            return $query->whereHas('vehicle.vehicleName', fn($q) => $q->where('name', 'like', "%{$keyword}%"));
        });

        $baseQuery->when(($validated['date_from'] ?? null) && ($validated['date_to'] ?? null), function ($query) use ($validated) {
            return $query->where(fn($q) => $q->where('start_book_date', '<=', $validated['date_to'])->where('end_book_date', '>=', $validated['date_from']));
        });

        $allTransactions = $baseQuery->get();

        $allTransactions->map(function ($transaction) {
            if ($transaction->vehicle_price == 0 && $transaction->vehicle) {
                $startDate = Carbon::parse($transaction->start_book_date);
                $endDate = Carbon::parse($transaction->end_book_date);
                $numberOfDays = $startDate->diffInDays($endDate) + 1;
                if ($numberOfDays < 1) { $numberOfDays = 1; }
                
                $totalPriceBeforeDiscount = $transaction->vehicle->price * $numberOfDays;
                $discountPercentage = min(0.05 * $numberOfDays, 0.30);
                $vehiclePriceAfterDiscount = $totalPriceBeforeDiscount * (1 - $discountPercentage);
                
                $transaction->vehicle_price = round($vehiclePriceAfterDiscount);
            }
            
            if ($transaction->driver_price == 0 && $transaction->driver_id) {
                $transaction->driver_price = 50000;
            }

            $transaction->price = $transaction->vehicle_price + $transaction->driver_price;
            
            return $transaction;
        });

       
        [$ongoingTransactions, $historyTransactions] = $allTransactions->partition(
            fn($t) => in_array($t->transaction_status_id, [1, 2, 3])
        );

        $onPaymentTransactions = $ongoingTransactions->where('transaction_status_id', 1);
        $individualOngoing = $ongoingTransactions->whereIn('transaction_status_id', [2, 3]);
        $groupedOnPayment = $onPaymentTransactions->groupBy('payment_id')->values();
        $ongoingItems = $individualOngoing->toBase()->merge($groupedOnPayment)->sortByDesc('created_at');

        $ongoingPage = LengthAwarePaginator::resolveCurrentPage('ongoingPage');
        $paginatedOngoing = new LengthAwarePaginator(
            $ongoingItems->forPage($ongoingPage, $perPage),
            $ongoingItems->count(), $perPage, $ongoingPage,
            ['path' => $request->url(), 'pageName' => 'ongoingPage']
        );

        $historyPage = LengthAwarePaginator::resolveCurrentPage('historyPage');
        $paginatedHistory = new LengthAwarePaginator(
            $historyTransactions->forPage($historyPage, $perPage),
            $historyTransactions->count(), $perPage, $historyPage,
            ['path' => $request->url(), 'pageName' => 'historyPage']
        );

        return view('booking-history', [
            'ongoingItems' => $paginatedOngoing,
            'historyTransactions' => $paginatedHistory,
        ]);
    }

    public function show(ViewBookingHistoryRequest $request, Transaction $transaction): View
    {
        $transaction->load(['user', 'vehicle', 'vehicle.vehicleName', 'vehicle.vehicleType', 'vehicle.vehicleTransmission', 'driver', 'payment']);
        return view('booking-detail', compact('transaction'));
    }

    public function downloadReceipt($payment_id)
    {
        $firstTransaction = Transaction::where('payment_id', $payment_id)->first();
        if (!$firstTransaction) { abort(404, 'Transaksi tidak ditemukan.'); }
        if ($firstTransaction->user_id !== Auth::id()) { abort(403, 'Unauthorized action.'); }
        
        $payment = Payment::findOrFail($payment_id);
        if ($payment->status !== 'PAID') {
            return back()->with('error', 'Struk hanya dapat diunduh untuk pembayaran yang telah lunas.');
        }

        $user = User::find($firstTransaction->user_id);

        $transactions = Transaction::with([
            'vehicle.vehicleName', 
            'vehicle.vehicleType', 
            'vehicle.vehicleTransmission'
        ])->where('payment_id', $payment_id)->get();

        $transactions->map(function ($transaction) {
            if ($transaction->vehicle_price == 0 && $transaction->vehicle) {
                $startDate = Carbon::parse($transaction->start_book_date);
                $endDate = Carbon::parse($transaction->end_book_date);
                $numberOfDays = $startDate->diffInDays($endDate) + 1;
                if ($numberOfDays < 1) { $numberOfDays = 1; }
                
                $totalPriceBeforeDiscount = $transaction->vehicle->price * $numberOfDays;
                $discountPercentage = min(0.05 * $numberOfDays, 0.30);
                $vehiclePriceAfterDiscount = $totalPriceBeforeDiscount * (1 - $discountPercentage);
                
                $transaction->vehicle_price = round($vehiclePriceAfterDiscount);
            }
            if ($transaction->driver_price == 0 && $transaction->driver_id) {
                $transaction->driver_price = 50000;
            }

            $transaction->price = $transaction->vehicle_price + $transaction->driver_price;
            
            return $transaction;
        });

        $pdf = Pdf::loadView('receipts.pdf', compact('payment', 'transactions', 'user'));
        $fileName = 'receipt-rentlab-' . $payment->external_id . '.pdf';
        return $pdf->download($fileName);
    }

    public function cancel(CancelBookingRequest $request, Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            $transaction->update(['transaction_status_id' => 7]);
            if ($transaction->payment && $transaction->payment->status === 'PENDING') {
                $transaction->payment->update(['status' => 'CANCELED']);
            }
            DB::commit();
            return redirect()->route('booking.history', ['active_tab' => 'history'])->with('success', __('booking-history.cancel_success_message'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error canceling transaction: ' . $e->getMessage());
            return back()->with('error', 'Gagal membatalkan transaksi.');
        }
    }
    
    public function expire(ExpireBookingRequest $request, Transaction $transaction): JsonResponse
    {
        DB::beginTransaction();
        try {
            $transaction->update(['transaction_status_id' => 7]);
            if ($transaction->payment && $transaction->payment->status === 'PENDING') {
                $transaction->payment->update(['status' => 'EXPIRED']);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaction has expired and been canceled.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error expiring transaction: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to expire transaction.'], 500);
        }
    }
}