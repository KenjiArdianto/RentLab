<?php

namespace App\Http\Controllers;

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
            return redirect()->route('home')->with('info', 'You have already completed your profile.');
        }
        return view('auth.complete-user-detail');
    }
    public function store(Request $request){
        // return "hi";
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in.');
        }

        // 2. Check if user already has user details
        if ($user->detail) {
            return redirect()->route('home')->with('info', 'You have already completed your profile.');
        }
        $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'max:20'],
            'idcardNumber' => ['required', 'string', 'max:50'],
            'dateOfBirth' => ['required', 'date'],
            'idcardPicture' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:100000'],
        ]);
        // return "hi all";

        if (UserDetail::where('idcardNumber', $request->idcardNumber)->exists()) {
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
        $user->detail()->create([
            'fname'=>$request->fname,
            'lname'=>$request->lname,
            'phoneNumber'=>$request->phoneNumber,
            'idcardNumber'=>$request->idcardNumber,
            'dateOfBirth'=>$request->dateOfBirth,
            'idcardPicture'=>"idcard/{$filename}",
        ]);
        // return $user->detail();
    

    return redirect('/home')->with('success', 'Account created and logged in!');
    }
}
