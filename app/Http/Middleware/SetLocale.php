<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL; // Jangan lupa tambahkan ini

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Ambil segmen 'locale' dari URL (misal: 'en' dari /en/catalog)
        $locale = $request->route('locale');

        // 2. Pastikan locale yang diminta didukung oleh aplikasi Anda
        if (! in_array($locale, ['en', 'id'])) {
            // Jika tidak, tampilkan halaman 404 Not Found
            abort(404);
        }

        // 3. Atur bahasa aplikasi untuk permintaan saat ini
        App::setLocale($locale);

        // 4. (PENTING) Atur locale default untuk semua URL yang akan dibuat
        // Ini memastikan helper route() akan otomatis membuat URL seperti /en/home
        URL::defaults(['locale' => $locale]);

        // Lanjutkan ke request berikutnya
        return $next($request);
    }
}
