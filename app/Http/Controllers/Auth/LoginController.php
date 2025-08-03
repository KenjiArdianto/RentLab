<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;   
use App\Http\Requests\LoginUserRequest; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(LoginUserRequest $request)
    {
        // This will automatically validate before this line
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Check if user is suspended
            if (Auth::user()->suspended_at !== null) {
                activity('login')
                ->causedBy(Auth::user())
                ->withProperties([
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'suspended_at' => Auth::user()->suspended_at,
                    'user_agent' => $request->userAgent(),
                ])
                ->log('Login blocked: user is suspended');
                Auth::logout(); // Prevent login

                return back()->withErrors([
                    'email' => 'Your account has been suspended.',
                ])->withInput();
            }


            activity('login')
            ->causedBy(Auth::user())
            ->withProperties([
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('User logged in successfully');
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath());
        }


        activity('login')
        ->withProperties([
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Login failed: invalid credentials');

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
            'password' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->back(); // or redirect()->intended('/home')
    }
}
