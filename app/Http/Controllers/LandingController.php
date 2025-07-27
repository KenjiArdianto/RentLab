<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Menampilkan halaman utama (landing page).
     * Method ini juga akan menerima data pencarian dari URL (jika ada)
     * dan meneruskannya ke view agar form bisa diisi kembali.
     *
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
     * Memproses data dari form pencarian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search(Request $request)
    {
        $validated_data = $request->validate([
            'start_book_date' => 'required|date',
            'end_book_date'   => 'required|date|after_or_equal:start_book_date',
            'vehicle_type'    => 'required|string|in:motorcycle,car',
        ]);

        return redirect()->route('search.results.page', $validated_data); 
    }
}