<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOTP;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    public function register(RegisterUserRequest $request)
    {    
        // Check if a user with this email already exists (for edge cases)
        if (User::where('email', $request->email)->exists()) {
            activity('registration')
            ->causedBy(Auth::user()) // will be null here, it's fine
            ->withProperties([
                'email' => $request->email,
                'name' => $request->name,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Failed registration ! email is already registered');
            return back()->withErrors(['email' => 'Email is already registered.']);
        }
        activity('registration')
        ->causedBy(Auth::user()) // will be null here, it's fine
        ->withProperties([
            'email' => $request->email,
            'name' => $request->name,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('OTP sent for registration');


        $otp = rand(100000, 999999);

        // Store data in session temporarily
        session([
            'temp_user' => [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),

                'otp' => $otp,
            ],
            'otp_expires_at' => now()->addMinutes(5),
        ]);
        
        // Send OTP
        Mail::to($request->email)->send(new SendOTP($otp));
        
        return redirect()->route('otp.verify.form')->with('success', 'OTP has been sent to your email.');
    }



    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
