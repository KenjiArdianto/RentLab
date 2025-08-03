<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDetailsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class UserDetailController extends Controller
{
    //
    public function show(){
        $user = Auth::user();
        // return "hi";
        // return redirect()->route('login');

        // 1. Must be logged in
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        // 2. Check if user already has user details
        if ($user->detail) {
            return redirect()->route('vehicle.display')->with('info', 'You have already completed your profile.');
        }
        return view('auth.complete-user-detail');
    }
    public function store(UserDetailsRequest $request){
        // return "hi";
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        // 2. Check if user already has user details
        if ($user->detail) {
            return redirect()->route('vehicle.display')->with('info', 'You have already completed your profile.');
        }

        if (UserDetail::where('idcardNumber', $request->idcardNumber)->exists()) {
            \activity('user_detail')
            ->causedBy(Auth::user())
            ->withProperties([
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
                'submitted' => $request->only(['fname', 'lname', 'phoneNumber', 'idcardNumber', 'dateOfBirth']),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User inputted already used NIK in database');
            return back()->withErrors(['idcard' => 'NIK is already registered.']);
        }

        // return $request;
        $image = $request->file('idcardPicture');
        $ext = $image->getClientOriginalExtension();
        do {
            $filename = Str::random(20) . '.' . $ext;
        } while (
            Storage::disk('public')->exists("idcard/{$filename}")
        );
        Storage::disk('public')->putFileAs('idcard', $image, $filename);
        // return $user;
        // Store data in session temporarily
        $userDetail=$user->detail->create([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'phoneNumber'=>$request->phoneNumber,
            'idcardNumber'=>$request->idcardNumber,
            'dateOfBirth'=>$request->dateOfBirth,
            'idcardPicture'=>"idcard/{$filename}",
        ]);
        \activity('user_detail')
        ->performedOn($userDetail)
        ->causedBy(Auth::user())
        ->withProperties([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => request()->ip(),
            'submitted' => $request->only(['fname', 'lname', 'phoneNumber', 'idcardNumber', 'dateOfBirth']),
            'picture_name' => $filename,
            'user_agent' => request()->userAgent(),
        ])
        ->log('User completed their profile details');
    

    return redirect('/home')->with('success', 'Account created and logged in!');
    }
}
