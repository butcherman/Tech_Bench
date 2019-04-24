<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Index page is the change user settings form
    public function index()
    {
        $userData = User::find(Auth::user()->user_id);
        $userSett = UserSettings::where('user_id', Auth::user()->user_id)->first();

        return view('account.index', [
            'userData'     => $userData,
            'userSettings' => $userSett,
            'userID'       => Auth::user()->user_id
        ]);
    }
    
    //  Submit the new user settings
    public function submit(Request $request)
    {
        $request->validate([
            'username'   => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required',
        ]);
        
        $userID = Auth::user()->user_id;

        User::find($userID)->update(
        [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email
        ]);
        
        UserSettings::where('user_id', $userID)->update(
        [
            'em_tech_tip'     => $request->em_tech_tip === 'on' ? true : false,
            'em_file_link'    => $request->em_file_link === 'on' ? true : false,
            'em_notification' => $request->em_notification === 'on' ? true : false,
            'auto_del_link'   => $request->auto_del_link === 'on' ? true : false,
        ]);
        
        Log::info('User Info Updated', ['user_id' => Auth::user()->user_id]);
        
        session()->flash('success', 'User Settings Updated');
        
        return redirect(route('account'));
    }
    
    //  Bring up the change password form
    public function changePassword()
    {
        return view('account.changePassword');
    }
    
    //  Submit the change password form
    public function submitPassword(Request $request)
    {
        //  Make sure that the old password is valid
        if(!(Hash::check($request->oldPass, Auth::user()->password)))
        {
            return redirect()->back()->with('error', 'Your Current Password is not valid.  Please try again.');
        }
        
        //  Make sure that the new password is not the same as the old password
        if(strcmp($request->newPass, $request->oldPass) == 0)
        {
            return redirect()->back()->with('error', 'New Password cannot be the same as the old password');
        }
        
        //  Validate remaining data
        $request->validate([
            'oldPass' => 'required',
            'newPass' => 'required|string|min:6|confirmed'
        ]);
        
        $newExpire = config('users.passExpires') != null ? Carbon::now()->addDays(config('users.passExpires')) : null;
        
        //  Change the password
        $user = Auth::user(); 
        $user->password = bcrypt($request->newPass);
        $user->password_expires = $newExpire;
        $user->save();
        
        Log::info('User Changed Password', ['user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'Password Changed Successfully');
    }
}
