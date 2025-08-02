<?php

namespace App\Http\Controllers;

use App\Http\Requests\OTPUserRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Mail\SendOTP;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    //
    
    public function showForm()
    {
        // return (session()->get('temp_user')==NULL);
        if(!session('temp_user')){
            return redirect(route('register'))->withErrors(['otp' => 'Session expired. Please register again.'])->with('time',0);
        }
        if(now()->gt(session('otp_expires_at'))){
            return view('auth.verify-otp')->withErrors(['otp' => 'OTP expired. Please resend OTP or register again.'])->with('time',0);
        };
        $time=now()->addMinutes(4)->diffInSeconds(session('otp_expires_at'));
        return view('auth.verify-otp')->with('time',$time);
    }

    public function resentOTP()
    {
        if (!session()->has('temp_user')) {
            return redirect()->back()->withErrors(['otp' => 'Session expired. Please register again'])->with('time',0);
        }
        if (now()->addMinutes(4)->lt(session('otp_expires_at'))) {
            \activity('otp_resend')
            ->withProperties([
                'email' => session('temp_user')['email'],
                'ip' => request()->ip(),
                'cooldown_remaining_seconds' => now()->addMinutes(4)->diffInSeconds(session('otp_expires_at')),
                'time' => now(),
            ])
            ->log('OTP resend blocked due to cooldown');
            
            return redirect()->back()->withErrors(['otp' => 'Please wait before resending more OTP !'])->with('time',now()->addMinutes(4)->diffInSeconds(session('otp_expires_at')));
        }
        $temp = session('temp_user');
        $otp = rand(100000, 999999);
        $temp['otp'] = $otp;
        session(['temp_user' => $temp]);
        session(['otp_expires_at' =>now()->addMinutes(5)]);
        Mail::to($temp['email'])->send(new SendOTP($otp));
        $time = now()->addMinutes(4)->diffInSeconds(session('otp_expires_at'));
        \activity('otp_resend')
        ->withProperties([
            'email' => $temp['email'],
            'ip' => request()->ip(),
            'time' => now(),
        ])
        ->log('OTP resent successfully');
        return redirect()->back()->with(['success'=> 'OTP has been resent','time'=>$time]);
    }
    public function verify(OTPUserRequest $request)
    {
        
        $temp = session()->get('temp_user');
        $expires = session()->get('otp_expires_at');
        if (!$temp || now()->gt($expires)) {
            return back()->withErrors(['otp' => 'OTP expired. Please resend OTP or register again.'])->with('time',0);
        }

        if ($request->otp != $temp['otp']) {
            \activity('otp_verification')
            ->withProperties([
                'status' => 'failed',
                'email' => $temp['email'],
                'ip' => request()->ip(),
                'submitted_otp' => $request->otp,
                'expected_otp' => $temp['otp'],
                'time' => now(),
            ])
            ->log('User submitted incorrect OTP');
            $time = now()->addMinutes(4)->diffInSeconds(session('otp_expires_at'));
            return back()->withErrors(['otp' => 'Invalid OTP.'])->with('time',$time);
        }
        

        // ✅ OTP is correct, now create the user
        $user = User::create([
            'name' => $temp['name'],
            'email' => $temp['email'],
            'password' => $temp['password'],
            'email_verified_at'=>now(),
        ]);

        \activity('otp_verification')
        ->performedOn($user)
        ->causedBy($user)
        ->withProperties([
            'status' => 'success',
            'email' => $temp['email'],
            'ip' => request()->ip(),
            'time' => now(),
        ])
        ->log('User successfully verified OTP and account created');

        // ✅ Now clear session
        session()->forget(['temp_user', 'otp_expires_at']);

        Auth::login($user);

        return redirect('/home')->with('success', 'Account created and logged in!');
    }

}
