<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Settings;
use App\User;
use App\Mail\TestEmail;

class InstallerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //  Home page for Installer Functions
    public function index()
    {
        //  Get the list of system categories
        $cats = SystemCategories::all();
        $sysArr = [];
        //  Populate that list with the matching systems
        foreach($cats as $cat)
        {
            $systems = SystemTypes::where('cat_id', $cat->cat_id)->get();
            if(!$systems->isEmpty())
            {
                foreach($systems as $sys)
                {
                    $sysArr[$cat->name][] = $sys->name;
                }
            }
            else
            {
                $sysArr[$cat->name] = null;
            }
        }
        
        return view('installer.index', [
            'sysArr' => $sysArr
        ]); 
    }
    
    //  Server customization form
    public function customizeSystem()
    {
        return view('installer.form.customize');
    }
    
    //  Submit the server customization form
    public function submitCustom(Request $request)
    {
        $request->validate([
            'timezone' => 'required'
        ]);
        
        Settings::where('key', 'app.timezone')->update([
            'value' => $request->timezone
        ]);
        
        Log::info('Tech Bench Settings Updated', ['user_id' => Auth::user()->user_id]);
        
        return redirect()->back()->with('success', 'Tech Bench Successfully Updated');//
    }
    
    //  Upload and submit a new site logo
    public function submitLogo(Request $request)
    {
        $file     = $request->file;
        $fileName = $file->getClientOriginalName();
        $file->storeAs('img', $fileName, 'public');
        
        Settings::where('key', 'app.logo')->update([
            'value' => '/storage/img/'.$fileName
        ]);
        
        Log::info('A new company logo has been uploaded by User ID-'.Auth::user()->user_id);
        
        return 'success';
    }
    
    //  Email settings form
    public function emailSettings()
    {
        return view('installer.form.email');
    }
    
    //  Send a test email
    public function sendTestEmail(Request $request)
    {
        //  Make sure that all of the information properly validates
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required'
        ]);
        
        //  Temporarily set the email settings
        config([
            'mail.host'       => $request->host,
            'mail.port'       => $request->port,
            'mail.encryption' => $request->encryption,
            'mail.username'   => $request->username,
        ]);
        
        if(!empty($request->password))
        {
            config(['mail.password' => $request->password]);
        }
        
        //  Try and send the test email
        try
        {
            Log::info('Test Email Successfully Sent to '.Auth::user()->email);
            Mail::to(Auth::user()->email)->send(new TestEmail());
            return 'success';
        }
        catch(Exception $e)
        {
            Log::notice('Test Email Failed.  Message: '.$e);
            $msg = '['.$e->getCode().'] "'.$e->getMessage().'" on line '.$e->getTrace()[0]['line'].' of file '.$e->getTrace()[0]['file'];
            return $msg;
        }
    }
    
    //  Submit the email settings form
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
         
        Log::info('Email Settings have been changed by User ID-'.Auth::user()->user_id);
        return redirect()->back()->with('success', 'Tech Bench Successfully Updated');//
    }
    
    //  User settings form
    public function userSettings()
    {
        $passExpire = config('users.passExpires') != null ? config('users.passExpires') : 0;
        
        return view('installer.form.users', [
            'passExpire' => $passExpire
        ]);
    }
    
    //  Submit the user settings form
    public function submitUserSettings(Request $request)
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
        return redirect()->back()->with('success', 'User Settings Updated');
    }
}
