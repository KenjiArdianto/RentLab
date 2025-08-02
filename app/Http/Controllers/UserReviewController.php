<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleReviewRequest; // [EDITH]: Import request yang baru
use App\Models\Transaction;
use App\Models\VehicleReview;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    public function store(StoreVehicleReviewRequest $request, Transaction $transaction)
    {
        if ($transaction->vehicleReview) {
            return back()->with('error', __('navigation.error.already_reviewed'));
        }
        
        $validatedData = $request->validated();

        VehicleReview::create([
            'transaction_id' => $transaction->id,
            'user_id' => $transaction->user_id,
            'vehicle_id' => $transaction->vehicle_id, 
            'rate' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
        ]);
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Thank you for your review!']);
        }

        $transaction->refresh();
        if ($transaction->userReview->isNotEmpty() && $transaction->vehicleReview) {
            $transaction->transaction_status_id = 6;
            $transaction->save();
        }
        
        return redirect()->route('booking.history')->with('success', 'Thank you for your review!');
    }
}