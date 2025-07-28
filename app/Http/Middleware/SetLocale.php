<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil segmen 'locale' dari URL (misal: 'en' dari /en/catalog)
        $locale = $request->route('locale');

        // Pastikan locale yang diminta didukung oleh aplikasi Anda
        if (! in_array($locale, ['en', 'id'])) {
            abort(404);
        }

        // Atur bahasa aplikasi untuk permintaan saat ini
        App::setLocale($locale);

        // (PENTING) Atur locale default untuk semua URL yang akan dibuat
        URL::defaults(['locale' => $locale]);

        // Lanjutkan ke request berikutnya
        return $next($request);
    }
}
