<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDetail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Exists;

class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        // if(Auth::user()->role!=='admin'){
        //     abort(403,'Anjing lu bukan admin! jangan gw hack gua plis');
        // }
        return view('profile');
    }

    public function change(Request $request){

        $user = Auth::user();
        $details = $user->detail;

        // ✅ Validate basic fields and files (optional but recommended)
        $request->validate([
            'fname' => ['nullable', 'string', 'max:255'],
            'lname' => ['nullable', 'string', 'max:255'],
            'phoneNumber' => ['nullable', 'string', 'max:20'],
            'idcardNumber' => ['nullable', 'string', 'max:50'],
            'dateOfBirth' => ['nullable', 'date'],
            'idcardPicture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:100000'],
            'profilePicture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:100000'],
        ]);
        // return "hi";

        // ✅ Update basic info
        $user->name=$request->username;
        $user->save();
        
        $details->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phone_number' => $request->phoneNumber,
            'idCardNumber' => $request->idCardNumber,
            'dateOfBirth' => $request->dateOfBirth,
        ]);

        // ✅ Handle profile picture
        // return $request->has('profilePicture');
        // return $request;
        if($request->has('profilePicture')){
            if ($request->hasFile('profilePicture')) {
                $image = $request->file('profilePicture');
                $ext = $image->getClientOriginalExtension();

                // 🔁 Generate unique filename in "profile/" folder
                do {
                    $filename = Str::random(20) . '.' . $ext;
                } while (Storage::disk('public')->exists("profile/{$filename}"));

                // 🧹 Delete old file if exists in DB
                if (!empty($details->profilePicture) && Storage::disk('public')->exists($details->profilePicture)) {
                    Storage::disk('public')->delete($details->profilePicture);
                }

                // 💾 Store new file
                Storage::disk('public')->putFileAs('profile', $image, $filename);

                // 📝 Save path in DB (e.g. "idcard/abc123.jpg")
                $details->profilePicture = "profile/{$filename}";
            }
        }else if(!$request->has('profilePicture')){
            if (!empty($details->profilePicture) && Storage::disk('public')->exists($details->profilePicture)) {
                Storage::disk('public')->delete($details->profilePicture);
            }
            $details->profilePicture = null;
        }
        

        // ✅ Handle ID card picture
        if ($request->hasFile('idcardPicture')) {
            $image = $request->file('idcardPicture');
            $ext = $image->getClientOriginalExtension();

            // 🔁 Generate unique filename in "idcard/" folder
            do {
                $filename = Str::random(20) . '.' . $ext;
            } while (Storage::disk('public')->exists("idcard/{$filename}"));

            // 🧹 Delete old file if exists in DB
            if (!empty($details->idcardPicture) && Storage::disk('public')->exists($details->idcardPicture)) {
                Storage::disk('public')->delete($details->idcardPicture);
            }

            // 💾 Store new file
            Storage::disk('public')->putFileAs('idcard', $image, $filename);

            // 📝 Save path in DB (e.g. "idcard/abc123.jpg")
            $details->idcardPicture = "idcard/{$filename}";
        }

        $details->save();
        return back()->with('success', 'Profile updated successfully!');
    }

    public function delete(Request $request){
        Auth::user()->delete();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'))->with('status', 'Account deleted successfully.');
    }

    public function coba(){
        $user = User::where('email',"=","elsonrajinbelajar@gmail.com")->get();
        return $user!=null;
    }
}
