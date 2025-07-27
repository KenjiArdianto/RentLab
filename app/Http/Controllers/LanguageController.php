<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class LanguageController extends Controller
{
    /**
     * Mengganti bahasa aplikasi dan mengarahkan pengguna kembali
     * ke halaman sebelumnya dengan bahasa yang baru.
     *
     * @param string $locale Kode bahasa yang baru (misal: 'en', 'id')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(string $locale)
    {
        // dd($locale);
        // 1. Pastikan locale yang diminta didukung
        if (! in_array($locale, ['en', 'id'])) {
            abort(400, 'Unsupported language.');
        }

        // 2. Simpan locale baru di session untuk referensi
        session()->put('locale', $locale);

        // ===================================================================
        // FIX FINAL: Logika redirect disederhanakan secara drastis.
        // Kita akan langsung mengarahkan pengguna ke root bahasa yang baru
        // (contoh: /id atau /en). Rute Anda akan menangani sisanya
        // dan mengarahkan mereka ke halaman home yang benar.
        // Ini adalah cara yang paling andal dan meniru apa yang Anda
        // lakukan saat mengetik URL secara manual.
        // ===================================================================
        return redirect('/' . $locale);
    }
}
