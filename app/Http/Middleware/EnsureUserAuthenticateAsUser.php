<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAuthenticateAsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check() || Auth::user()->role!=='user'){
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
                ->log('Access denied: non-user tried to access user-only route');
            abort(403,'bukan user ga usah maksa');
        }
        return $next($request);
    }
}
