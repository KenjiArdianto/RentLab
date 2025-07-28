<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ===================================================================
        // FIX: Tambahkan ini untuk mengecualikan webhook Xendit dari CSRF.
        // ===================================================================
        $middleware->validateCsrfTokens(except: [
            'payment/callback'
        ]);

        // Daftarkan juga alias untuk middleware SetLocale di sini
        $middleware->alias([
            'setlocale' => SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
