<?php

namespace App\Http\Controllers\Installer;

use App\User;
use App\Settings;
use Carbon\Carbon;
use App\Mail\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

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
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
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
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('User Settings have been changed by User ID-'.Auth::user()->user_id);
        return redirect()->back()->with('success', 'User Security Updated');
    }
    
    //  Timezone and Logo forms
    public function customizeSystem()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('installer.customize');
    }
    
    //  Submit the timezone  form
    public function submitCustomizeSystem(Request $request)
    {
        $request->validate([
            'timezone' => 'required'
        ]);
        
        Settings::where('key', 'app.timezone')->update([
            'value' => $request->timezone
        ]);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('Tech Bench Settings Updated', ['user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'Timezone Successfully Updated');
    }
    
    //  Submit the new company logo
    public function submitLogo(Request $request)
    {
        $file     = $request->file;
        $fileName = $file->getClientOriginalName();
        $file->storeAs('img', $fileName, 'public');
        
        Settings::where('key', 'app.logo')->update([
            'value' => '/storage/img/'.$fileName
        ]);
        
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('A new company logo has been uploaded by User ID-'.Auth::user()->user_id);
        
        return response()->json(['url' => '/storage/img/'.$fileName]);
    }
    
    //  Email Settings form
    public function emailSettings()
    {
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('installer.emailSettings');
    }
    
    //  Send a test email
    public function sendTestEmail(Request $request)
    {
        //  to be added
    }
    
    //  Submit the test email form
    public function submitEmailSettings(Request $request)
    {
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required'
        ]);
        
        //  Update each setting
        Settings::where('key', 'mail.host')->update(['value' => $request->host]);
        Settings::where('key', 'mail.port')->update(['value' => $request->port]);
        Settings::where('key', 'mail.encryption')->update(['value' => $request->encryption]);
        Settings::where('key', 'mail.username')->update(['value' => $request->username]);
        if(!empty($request->password))
        {
            Settings::where('key', 'mail.password')->update(['value' => $request->password]);
        }
         
        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('Email Settings have been changed by User ID-'.Auth::user()->user_id);
        return redirect()->back()->with('success', 'Tech Bench Successfully Updated');
    }
}
