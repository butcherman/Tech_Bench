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

        Settings::firstOrCreate(
            ['key'   => 'app.logo'],
            ['key'   => 'app.logo', 'value' => '/storage/img/' . $fileName]
        )->update(['value' => '/storage/img/' . $fileName]);

        Log::debug('Route ' . Route::currentRouteName() . ' visited by User ID-' . Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('A new company logo has been uploaded by User ID-' . Auth::user()->user_id);

        return response()->json(['url' => '/storage/img/' . $fileName]);
    }

    public function configuration()
    {
        $settings = collect([
            'url'      => config('app.url'),
            'timezone' => config('app.timezone'),
            'filesize' => config('filesystems.paths.max_size'),
        ]);

        return view('installer.configuration', [
            'timzone_list' => \Timezonelist::toArray(),
            'settings'     => $settings,
        ]);
    }

    public function submitConfiguration(Request $request)
    {
        $request->validate([
            'url'      => 'required',
            'timezone' => 'required',
            'filesize' => 'required',
            //  TODO - add additinal validation to make sure proper information is passed in
        ]);

        //  Update the site URL
        if(config('app.url') !== $request->url)
        {
            Settings::firstOrCreate(
                ['key'   => 'app.url'],
                ['key'   => 'app.url', 'value' => $request->url]
            )->update(['value' => $request->url]);
        }
        //  Update the site timezone
        if (config('app.timezone') !== $request->timezone) {
            Settings::firstOrCreate(
                ['key'   => 'app.timezone'],
                ['key'   => 'app.timezone', 'value' => $request->timezone]
            )->update(['value' => $request->timezone]);
        }
        //  Update the maximum file upload size
        if (config('filesystems.paths.max_size') !== $request->filesize) {
            Settings::firstOrCreate(
                ['key'   => 'filesystems.paths.max_size'],
                ['key'   => 'filesystems.paths.max_size', 'value' => $request->filesize]
            )->update(['value' => $request->filesize]);
        }
        $request->session()->flash('success', 'Configuration Updated');

        return response()->json(['success' => true]);
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
