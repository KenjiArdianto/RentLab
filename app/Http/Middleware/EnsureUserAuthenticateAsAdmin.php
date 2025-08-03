<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAuthenticateAsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check() || Auth::user()->role!=='admin'){
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
                ->log('Access denied: non-user tried to access admin-only route');
            abort(403,'admin doang cik, cabut lo!');
        }
        return $next($request);
    }
}
