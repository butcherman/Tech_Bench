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
    
    //  Timezone and Logo forms
    public function customizeSystem()
    {
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
        
        Log::info('Tech Bench Settings Updated', ['user_id' => Auth::user()->user_id]);
        
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
        
        Log::info('A new company logo has been uploaded by User ID-'.Auth::user()->user_id);
        
        return response()->json(['url' => '/storage/img/'.$fileName]);
    }
    
    //  Email Settings form
    public function emailSettings()
    {
        return view('installer.emailSettings');
    }
    
    //  Send a test email
    public function sendTestEmail(Request $request)
    {
        
//        echo $request->host;
//        echo ' ';
//        die();
        Log::info(config('mail.host'));
        
        //  Make sure that all of the information properly validates
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required'
        ]);
        
        //  Temporarily set the email settings
//        config([
//            'mail.host'       => $request->host,
//            'mail.port'       => $request->port,
//            'mail.encryption' => $request->encryption,
//            'mail.username'   => $request->username,
//        ]);
        
        Config::set('mail.host', $request->host);
        
        
//        
//        echo config('mail.host');
//        die();
        
        if(!empty($request->password))
        {
            config(['mail.password' => $request->password]);
        }
        
        //  Try and send the test email
        try
        {
//            Log::info('Test Email Successfully Sent to '.Auth::user()->email);
            Log::info(config('mail.host'));
            Mail::to(Auth::user()->email)->send(new TestEmail());
//            return 'success';
            return response()->json([
                'success' => true,
                'sentTo'  => Auth::user()->email
            ]);
        }
        catch(Exception $e)
        {
            Log::notice('Test Email Failed.  Message: '.$e);
            $msg = '['.$e->getCode().'] "'.$e->getMessage().'" on line '.$e->getTrace()[0]['line'].' of file '.$e->getTrace()[0]['file'];
//            return $msg;
            return response()->json(['message' => $msg]);
        }
    }
    
    //  Submit the test email form
    public function submitEmailSettings(Request $request)
    {
        return response('submitted');
    }
}
