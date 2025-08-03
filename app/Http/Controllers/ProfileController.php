<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
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
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(){
        // if(Auth::user()->role!=='admin'){
        //     abort(403,'lu bukan admin! jangan gw hack gua plis');
        // }
        return view('profile');
    }

    public function change(ProfileRequest $request)
    {
        $user = Auth::user();
        $details = $user->detail;

        $user->name=$request->name;
        $user->save();
        
        $details->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'phoneNumber' => $request->phoneNumber,
            'idcardNumber' => $request->idCardNumber,
            'dateOfBirth' => $request->dateOfBirth,
        ]);
        \activity('profile_update')
        ->causedBy(Auth::user())
        ->performedOn($user->detail)
        ->withProperties([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'changes' => $request->except(['_token', 'profilePicture', 'idcardPicture']), // user inputs
            'profilePicture_uploaded' => $request->hasFile('profilePicture'),
            'idcardPicture_uploaded' => $request->hasFile('idcardPicture'),
            'changed_fields' => $user->detail->getChanges(),
        ])
        ->log('User updated profile information.');


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
        \activity('account_deleted')
        ->causedBy(Auth::user())
        ->performedOn(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ])
    ->log('User deleted their account.');

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
