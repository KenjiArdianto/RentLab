<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class LogUserFailedLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
        Log::channel('authlog')->info('Failed login attempt', [
            'email' => $event->credentials['email'] ?? 'unknown',
            'ip' => request()->ip(),
            'time' => now(),
        ]);
    }
}
