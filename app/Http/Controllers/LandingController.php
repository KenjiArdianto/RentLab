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
        // Variabel diubah ke format snake_case
        $search_data = $request->all();

        // Mengirim data ke view dengan key snake_case juga
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
        // 1. Validasi dan simpan datanya ke variabel.
        $validated_data = $request->validate([
            'start_book_date' => 'required|date',
            'end_book_date'   => 'required|date|after_or_equal:start_book_date',
            'vehicle_type'    => 'required|string|in:motorcycle,car',
        ]);

        // 2. Redirect ke halaman hasil pencarian (atau halaman lain)
        // dengan membawa data yang sudah divalidasi.
        // Ganti 'search.results.page' dengan nama route halaman hasil pencarian Anda
        return redirect()->route('search.results.page', $validated_data); 
    }
}