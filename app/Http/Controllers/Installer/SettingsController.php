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
        $this->middleware(['auth', 'can:is_installer']);
    }

    //  Bring up the change logo form
    public function logoSettings()
    {
        return view('installer.logoSettings');
    }
    //  Submit the new company logo
    public function submitLogo(Request $request)
    {
        $request->validate([
            'file' => 'mimes:jpeg,bmp,png,jpg,gif'
        ]);

        $file     = $request->file;
        $fileName = $file->getClientOriginalName();
        $file->storeAs('img', $fileName, 'public');

        Settings::where('key', 'app.logo')->update([
            'value' => '/storage/img/' . $fileName
        ]);

        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('A new company logo has been uploaded by User ID-' . Auth::user()->user_id);

        return response()->json(['url' => '/storage/img/' . $fileName]);
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
