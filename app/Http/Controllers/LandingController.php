<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search_data = $request->all();

        return view('landing', [
            'search_data' => $search_data
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        // Langkah 1: Validasi data yang masuk dari form landing
        // Nama field di sini harus cocok dengan 'name' pada input di landing.blade.php
        $validated_data = $request->validate([
            'vehicle_type'    => 'required|string|in:motorcycle,car',
            'start_book_date' => 'required|date|after_or_equal:today',
            'end_book_date'   => 'required|date|after_or_equal:start_book_date',
        ]);

        // Langkah 2: Buat array baru untuk "menerjemahkan" nama parameter
        // agar sesuai dengan yang diharapkan oleh halaman /home
        $redirect_parameters = [
            'Tipe_Kendaraan' => $validated_data['vehicle_type'] === 'car' ? 'Car' : 'Motor',
            'start_date'     => $validated_data['start_book_date'],
            'end_date'       => $validated_data['end_book_date'],
            'min_price'      => '', // Tambahkan parameter kosong agar cocok dengan URL tujuan
            'max_price'      => '',
        ];

        // Langkah 3: Redirect ke halaman hasil (vehicle.display) dengan parameter yang sudah diterjemahkan
        return redirect()->route('vehicle.display', $redirect_parameters);
    }
}
