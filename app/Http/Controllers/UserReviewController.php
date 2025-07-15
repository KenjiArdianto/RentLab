<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\VehicleReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserReviewController extends Controller
{
    /**
     * Menyimpan review baru untuk kendaraan.
     */
    public function store(Request $request, Transaction $transaction)
    {
        // Keamanan: Pastikan hanya pemilik transaksi yang bisa mereview
        // if ($transaction->user_id !== Auth::id()) {
        //      // Jika request adalah AJAX, kirim respon error JSON
        //     if ($request->wantsJson()) {
        //         return response()->json(['message' => 'Unauthorized action.'], 403);
        //     }
        //     abort(403);
        // }

        // Keamanan: Pastikan transaksi hanya bisa direview sekali
        if ($transaction->vehicleReview()->exists()) {
            return back()->with('error', 'This transaction has already been reviewed.');
        }

        // Validasi Input Form
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput($request->except('comment')) // <-- KUNCI PERBAIKANNYA
                ->with('open_modal', $transaction->id);
        }

        $validatedData = $validator->validated();

        // Simpan review ke tabel `vehicle_reviews`
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

        if ($transaction->UserReview()->exists()) {
            // Jika ya, tutup transaksinya
            $transaction->status = 6; // Status: Closed
            $transaction->save();
        }
        
        return redirect()->route('booking.history')->with('success', 'Thank you for your review!');
    }
}