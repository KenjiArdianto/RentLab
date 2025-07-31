<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\VehicleReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserReviewController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        // if ($transaction->user_id !== Auth::id()) {
        //     if ($request->wantsJson()) {
        //         return response()->json(['message' => 'Unauthorized action.'], 403);
        //     }
        //     abort(403);
        // }

        if ($transaction->vehicleReview) {
            return back()->with('error', __('navigation.error.already_reviewed'));
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
            return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('open_modal', $transaction->id);
        }

        $validatedData = $validator->validated();

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