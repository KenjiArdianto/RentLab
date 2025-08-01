<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserReview;
use Illuminate\Http\Request;
use App\Http\Requests\AdminTransactionUpdateRequest;
use Illuminate\Support\Facades\Auth;

class AdminTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Transaction::query();

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));

                // handle transaction_id
                if ($key === 'transaction_id' || $key === 'id_transaksi') {
                    $query->where('id', $value);
                }
                // handle user_id
                else if ($key === 'user_id' || $key === 'id_pengguna') {
                    $query->where('user_id', $value);
                }
                // handle driver_id
                else if ($key === 'driver_id' || $key === 'id_pengemudi') {
                    $query->where('driver_id', $value);
                }
                // handle vehicle_id
                else if ($key === 'vehicle_id' || $key === 'id_kendaraan') {
                    $query->where('vehicle_id', $value);
                }
                // handle start
                else if ($key === 'start' || $key === 'mulai') {
                    $dateParts = explode('-', $value);

                    if (count($dateParts) === 3) {
                        // yyyy-mm-dd
                        $query->where('start_book_date', $value);
                    } elseif (count($dateParts) === 2) {
                        // yyyy-mm
                        [$year, $month] = explode('-', $value);
                        $query->whereYear('start_book_date',$year)->whereMonth('start_book_date', $month);
                    } elseif (count($dateParts) === 1) {
                        // yyyy
                        $query->whereYear('start_book_date', $value);
                    }
                }
                else if ($key === 'end' || $key === 'selesai') {
                    $dateParts = explode('-', $value);

                    if (count($dateParts) === 3) {
                        // yyyy-mm-dd
                        $query->where('end_book_date', $value);
                    } elseif (count($dateParts) === 2) {
                        // yyyy-mm
                        [$year, $month] = explode('-', $value);
                        $query->whereYear('end_book_date',$year)->whereMonth('end_book_date', $month);
                    } elseif (count($dateParts) === 1) {
                        // yyyy
                        $query->whereYear('end_book_date', $value);
                    }
                }
                else if ($key === 'return' || $key === 'kembali') {
                    $dateParts = explode('-', $value);

                    if (count($dateParts) === 3) {
                        // yyyy-mm-dd
                        $query->where('return_date', $value);
                    } elseif (count($dateParts) === 2) {
                        // yyyy-mm
                        [$year, $month] = explode('-', $value);
                        $query->whereYear('return_date',$year)->whereMonth('return_date', $month);
                    } elseif (count($dateParts) === 1) {
                        // yyyy
                        $query->whereYear('return_date', $value);
                    }
                }
                else if ($key == 'status') {
                    $statusMap = [
                        'on_payment'       => 1,
                        'on_booking'       => 2,
                        'car_taken'        => 3,
                        'review_by_admin'  => 4,
                        'review_by_user'   => 5,
                        'closed'           => 6,
                        'canceled'         => 7,
                    ];
                    $query->where('transaction_status_id', $statusMap[$value]);
                }
            }
        }


        
        // dd($request->all());
        

        $transactions = $query->paginate(100)->appends(['search' => $search]);;
        \activity('admin_transaction_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'filters' => $request->query('search'),
            'result_count' => $transactions->total(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin viewed transaction list' . ($request->has('search') ? ' with filters' : ''));
        
        return view('admin.transactions', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminTransactionUpdateRequest $request, Transaction $transaction)
    {
        //
        
        // dd($transaction);

        if ($request->status === $transaction->transaction_status_id) {
            \activity('admin_transaction_update_skipped')
            ->causedBy(Auth::user())
            ->performedOn($transaction)
            ->withProperties([
                'ip' => $request->ip(),
                'submitted_status' => $request->status,
                'current_status' => $transaction->transaction_status_id,
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin tried to update transaction #$transaction->id but status remained unchanged");
                return back()->with('error', "Status Not Changed");
        }
        else if ($request->comment) {
            // dd($request->all());
            
            $transaction->transaction_status_id = $request->status;
            $transaction->save();

            UserReview::create([
                'admin_id' => 1,
                'user_id' => $transaction->user_id,
                'transaction_id' => $transaction->id,
                'comment' => $request->comment,
                'rate' => $request->rating
            ]);
            \activity('admin_transaction_reviewed')
            ->causedBy(Auth::user())
            ->performedOn($transaction)
            ->withProperties([
                'ip' => $request->ip(),
                'new_status' => $request->status,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin submitted review for transaction #$transaction->id");
            return back()->with('success', "Transaction #$transaction->id review submitted successfully.");
        }
        else {
            $transaction->transaction_status_id = $request->status;
            $transaction->save();

            \activity('admin_transaction_status_updated')
            ->causedBy(Auth::user())
            ->performedOn($transaction)
            ->withProperties([
                'ip' => $request->ip(),
                'new_status' => $request->status,
                'previous_status' => $transaction->getOriginal('transaction_status_id'),
                'user_agent' => $request->userAgent(),
            ])
            ->log("Admin updated transaction #$transaction->id status");

            return back()->with('success', "Transaction #$transaction->id status updated successfully.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
