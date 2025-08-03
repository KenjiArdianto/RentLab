<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
class GoogleController extends Controller
{
    //
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' =>Hash::make(Str::random(16)), // just a random password
                    'email_verified_at'=>now(),
                ]);
                activity('google_auth')
                ->causedBy($user)
                ->withProperties([
                    'email' => $user->email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('New user registered via Google');
                Auth::login($user);
                return redirect('/home');
            }

            // 🚫 Suspended check
            if ($user->suspended_at !== null) {
                activity('google_auth')
                    ->causedBy($user)
                    ->withProperties([
                        'email' => $user->email,
                        'ip' => request()->ip(),
                        'suspended_at' => $user->suspended_at,
                        'user_agent' => request()->userAgent(),
                    ])
                    ->log('Google login blocked: user is suspended');

                return redirect('/login')->withErrors(['email' => 'Your account has been suspended.']);
            }

            Auth::login($user);
            activity('google_auth')
            ->causedBy($user)
            ->withProperties([
                'email' => $user->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User logged in via Google');

            return redirect('/home')  ;
        }catch(\Exception $e){
            activity('google_auth')
            ->withProperties([
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Google login failed');
            return redirect('/login')->withErrors(['google' => 'Google login failed. Please try again.']);
        }
    }
}
