<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserReview; // Pastikan model UserReview Anda ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\support\Facades\Validator;

class UserReviewController extends Controller
{
    /**
     * Menyimpan review baru dari pengguna.
     */
    public function store(Request $request, Transaction $transaction)
    {
        // 1. Keamanan: Pastikan yang mereview adalah pemilik transaksi
        // if ($transaction->user_id !== Auth::id()) {
        //     return response()->json(['message' => 'Unauthorized action.'], 403);
        // }

        // 2. Keamanan: Pastikan transaksi hanya bisa direview sekali
        if ($transaction->userReview()->exists()) {
            return response()->json(['message' => 'This transaction has already been reviewed.'], 422);
        }

        // 3. Validasi Input Form
        // Kode Baru di dalam method store()
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            // TAMBAHKAN ->with('open_modal', $transaction->id)
            // Ini akan mengirim ID transaksi yang gagal divalidasi ke session
            return back()->withErrors($validator)->withInput()->with('open_modal', $transaction->id);
        }

        $validatedData = $validator->validated();

        // 4. Simpan review ke database
        UserReview::create([
            'transaction_id' => $transaction->id,
            //'user_id' => Auth::id(),
            'user_id' => 1, // Asumsi user default ID 5, sesuaikan jika perlu
            'admin_id' => 1, // Asumsi admin default ID 1, sesuaikan jika perlu
            'rate' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
        ]);

        // 5. Update status transaksi menjadi "Closed" (misalnya status ID 5)
        $transaction->status = 5;
        $transaction->save();

        // 6. Kembalikan ke halaman history dengan pesan sukses
        return redirect()->route('booking.history')->with('success', 'Thank you for your review!');
    }
}