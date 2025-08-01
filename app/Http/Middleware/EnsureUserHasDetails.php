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
        
        if (Auth::user()->role !== 'admin' && (!Auth::user()->detail)) {
            \activity('middleware_access_denied')
                ->causedBy(Auth::user())
                ->withProperties([
                    'user_id'     => optional(Auth::user())->id,
                    'user_email'  => optional(Auth::user())->email,
                    'role'        => optional(Auth::user())->role,
                    'requested_url' => $request->fullUrl(),
                    'method'      => $request->method(),
                    'ip'          => $request->ip(),
                    'user_agent'  => $request->userAgent(),
                ])
                ->log('Access denied: user needs user detail to access this page!');
            return redirect()->route('complete.user.detail');
        }

        return $next($request);
    }
}
