<?php

namespace App\Http\Controllers;
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
            return view('auth.verify-otp')->withErrors(['otp' => 'Session expired. Please register again.'])->with('time',0);
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
            Log::channel('authlog')->warning('OTP resend throttled: too frequent', [
                'email' => session('temp_user')['email'],
                'ip' => request()->ip(),
                'time' => now(),
            ]);
            return redirect()->back()->withErrors(['otp' => 'Please wait before resending more OTP !'])->with('time',now()->addMinutes(4)->diffInSeconds(session('otp_expires_at')));
        }
        $temp = session('temp_user');
        $otp = rand(100000, 999999);
        $temp['otp'] = $otp;
        session(['temp_user' => $temp]);
        session(['otp_expires_at' =>now()->addMinutes(5)]);
        Mail::to($temp['email'])->send(new SendOTP($otp));
        $time = now()->addMinutes(4)->diffInSeconds(session('otp_expires_at'));
        Log::channel('authlog')->info('OTP resent successfully', [
            'email' => $temp['email'],
            'ip' => request()->ip(),
            'time' => now(),
        ]);
        return redirect()->back()->with(['success'=> 'OTP has been resent','time'=>$time]);
    }
    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required']);
        //logs for fuzzing inputs
        function isFuzzingAttempt($input)
        {
            return strlen($input) > 100 ||        // overly long input
                preg_match('/[<>{}\[\];]/', $input) || // suspicious chars
                preg_match('/(union|select|insert|<script|alert|drop|--)/i', $input); // known attack patterns
        }
        if (isFuzzingAttempt($request->otp)) {
            Log::channel('authlog')->alert('Possible fuzzing attempt detected', [
                'field' => 'otp',
                'value' => $request->otp,
                'ip' => $request->ip(),
                'time' => now(),
            ]);

            return back()->withErrors(['otp' => 'Invalid input.']);
        }

        // ✅ Safely fetch from session (don't remove it yet)
        
        $temp = session()->get('temp_user');
        $expires = session()->get('otp_expires_at');
        if (!$temp || now()->gt($expires)) {
            // if (!empty($imagePath) && Storage::disk('public')->exists("tempidcard/{$imagePath}")) {
            //     Storage::disk('public')->delete("tempidcard/{$imagePath}");
            // }
            return back()->withErrors(['otp' => 'OTP expired. Please resend OTP or register again.'])->with('time',0);
        }

        if ($request->otp != $temp['otp']) {
            // if (!empty($imagePath) && Storage::disk('public')->exists("tempidcard/{$imagePath}")) {
            //     Storage::disk('public')->delete("tempidcard/{$imagePath}");
            // }
            Log::channel('authlog')->warning('Failed OTP attempts', [
                'email' => session('temp_user')['email'],
                'ip' => request()->ip(),
                'time' => now(),
            ]);
            $time = now()->addMinutes(4)->diffInSeconds(session('otp_expires_at'));
            return back()->withErrors(['otp' => 'Invalid OTP.'])->with('time',$time);
        }
        // return $temp;
        // ✅ OTP is correct, now create the user
        $user = User::create([
            'name' => $temp['name'],
            'email' => $temp['email'],
            'password' => $temp['password'],
            'email_verified_at'=>now(),
        ]);
        

        // if (Storage::disk('public')->exists("tempidcard/{$imagePath}")) {
        //     Storage::disk('public')->move("tempidcard/{$imagePath}", "idcard/{$imagePath}");
        // }

        // #isilah detailnya disini bambang
        // $user->detail()->create([
        //     'fname'=>$temp["fname"],
        //     'lname'=>$temp['lname'],
        //     'phoneNumber'=>$temp['phoneNumber'],
        //     'idcardNumber'=>$temp['idcardNumber'],
        //     'dateOfBirth'=>$temp['dateOfBirth'],
        //     'idcardPicture' => $temp['idcardPicture'],
        // ]);

        // ✅ Now clear session
        session()->forget(['temp_user', 'otp_expires_at']);

        Auth::login($user);

        return redirect('/home')->with('success', 'Account created and logged in!');
    }

}
