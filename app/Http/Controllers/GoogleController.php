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

            // Suspended user check
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

            
            //logs user in with that account
            Auth::login($user);
            activity('google_auth')
            ->causedBy($user)
            ->withProperties([
                'email' => $user->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User logged in via Google');
            //if user's account role is admin, then redirect to admin page, if not then go to home
            if($user->role==='admin'){
                return redirect('/admin');
            }
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
