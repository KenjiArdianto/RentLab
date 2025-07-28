<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Mengganti bahasa aplikasi dan mengarahkan pengguna.
     *
     * @param string $locale Kode bahasa yang baru (misal: 'en', 'id')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(string $locale)
    {
        // 1. Pastikan locale yang diminta didukung oleh aplikasi Anda
        if (! in_array($locale, ['en', 'id'])) {
            // Jika tidak, batalkan permintaan untuk menghindari error
            abort(400, 'Unsupported language.');
        }

        // 2. Simpan locale yang baru dipilih ke dalam session pengguna
        // Ini berguna jika Anda perlu mengingat pilihan bahasa mereka
        session()->put('locale', $locale);

        // 3. Redirect pengguna ke root dari bahasa yang baru dipilih (misal: /id atau /en).
        // File rute Anda (routes/web.php) kemudian akan menangani sisanya
        // dan mengarahkan mereka ke halaman yang benar (misal: /id/welcome).
        return redirect('/' . $locale);
    }
}
