<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
// use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasDetails
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if (Auth::user()->role !== 'admin' && (Auth::check() && !Auth::user()->detail)) {
            return redirect()->route('complete.user.detail');
        }

        return $next($request);
    }
}
