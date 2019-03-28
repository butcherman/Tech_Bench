<?php

namespace App\Http\Controllers\Installer;

use App\User;
use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Bring up the user security settings form
    public function userSecurity()
    {
        $passExpire = config('users.passExpires') != null ? config('users.passExpires') : 0;
        
        return view('installer.userSecurity', [
            'passExpire' => $passExpire
        ]);
    }
    
    //  Submit the user security settings form
    public function submitUserSecurity(Request $request)
    {
        $request->validate([
            'passExpire' => 'required|numeric'
        ]);
        
        //  Determine if the password expires field is updated
        $oldExpire = config('users.passExpires');
        if($request->passExpire != $oldExpire)
        {
            //  Update the setting in the database
            Settings::where('key', 'users.passExpires')->update([
                'value' => $request->passExpire
            ]);
            //  If the setting is changing from never to xx days, update all users
            if($request->passExpire == 0)
            {
                User::whereNotNull('password_expires')->update([
                    'password_expires' => null
                ]);
            }
            else
            {
                $newExpire = Carbon::now()->addDays($request->passExpire);
                User::whereNull('password_expires')->update([
                    'password_expires' => $newExpire
                ]);
            }
        }
        
        Log::info('User Settings have been changed by User ID-'.Auth::user()->user_id);
        return redirect()->back()->with('success', 'User Security Updated');
    }
}
