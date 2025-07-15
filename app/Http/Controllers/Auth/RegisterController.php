<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function register(Request $request)
{
    // return "hi";
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],

        // 'fname' => ['nullable', 'string', 'max:255'],
        // 'lname' => ['nullable', 'string', 'max:255'],
        // 'phoneNumber' => ['nullable', 'string', 'max:20'],
        // 'idcardNumber' => ['nullable', 'string', 'max:50'],
        // 'dateOfBirth' => ['nullable', 'date'],
        // 'idcardPicture' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:100000'],
    ]);
    // return "hi";
    
    // return dd($request);
    // Check if a user with this email already exists (for edge cases)
    if (User::where('email', $request->email)->exists()) {
        return back()->withErrors(['email' => 'Email is already registered.']);
    }

    // if (UserDetail::where('idcardNumber', $request->idcardNumber)->exists()) {
    //     return back()->withErrors(['idcard' => 'NIK is already registered.']);
    // }

    // // return $request;
    // $image = $request->file('idcardPicture');
    // $ext = $image->getClientOriginalExtension();
    // do {
    //     $filename = Str::random(20) . '.' . $ext;
    // } while (
    //     Storage::disk('public')->exists("tempidcard/{$filename}") ||
    //     Storage::disk('public')->exists("idcard/{$filename}")
    // );
    // Storage::disk('public')->putFileAs('tempidcard', $image, $filename);
    // return "hi";

    $otp = rand(100000, 999999);

    // Store data in session temporarily
    session([
        'temp_user' => [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

            // 'fname' => $request->fname,
            // 'lname' => $request->lname,
            // 'phoneNumber' => $request->phoneNumber,
            // 'idcardNumber' => $request->idcardNumber,
            // 'dateOfBirth' => $request->dateOfBirth,
            // 'idcardPicture' => $filename,

            'otp' => $otp,
        ],
        'otp_expires_at' => now()->addMinutes(5),
    ]);
    // return "hi all ";
    // Send OTP
    Mail::to($request->email)->send(new SendOTP($otp));
    // return session('temp_user');
    // return "hi all";

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
