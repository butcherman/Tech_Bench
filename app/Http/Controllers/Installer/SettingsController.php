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
        $settings = collect([
            'host'       => config('mail.host'),
            'port'       => config('mail.port'),
            'encryption' => config('mail.encryption'),
            'username'   => config('mail.username'),
            // 'password'   => config('mail.password'),
            'fromEmail'  =>config('mail.from.address'),
            'fromName'   => config('mail.from.name'),
        ]);

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        return view('installer.emailSettings', [
            'settings' => $settings,
        ]);
    }

    //  Send a test email
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required',
            'fromEmail'  => 'required',
            'fromName'   => 'required',
        ]);

        $password = isset($request->password) && $request->password !== 'NULL' ? $request->password : config('mail.password');
        Config::set('mail.host',         $request->host);
        Config::set('mail.port',         $request->port);
        Config::set('mail.encryption',   $request->encryption);
        Config::set('mail.username',     $request->username);
        Config::set('mail.password',     $password);
        Config::set('mail.from.name',    $request->fromName);
        Config::set('mail.from.address', $request->fromEmail);

        try
        {
            Mail::to(Auth::user())->send(new TestEmail($request));
        }
        catch (\Exception $e)
        {
            // return $e->getMessage();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'Email successfully sent to '.Auth::user()->email]);
    }

    //  Submit the test email form
    public function submitEmailSettings(Request $request)
    {
        $request->validate([
            'host'       => 'required',
            'port'       => 'required|numeric',
            'encryption' => 'required',
            'username'   => 'required',
            'fromEmail'  => 'required',
            'fromName'   => 'required',
        ]);

        //  Update each setting
        Settings::firstOrCreate(
            ['key'   => 'mail.host'],
            ['key'   => 'mail.host', 'value' => $request->host]
        )->update(['value' => $request->host]);
        Settings::firstOrCreate(
            ['key'   => 'mail.port'],
            ['key'   => 'mail.port', 'value' => $request->port]
        )->update(['value' => $request->port]);
        Settings::firstOrCreate(
            ['key'   => 'mail.encryption'],
            ['key'   => 'mail.encryption', 'value' => $request->encryption]
        )->update(['value' => $request->encryption]);
        Settings::firstOrCreate(
            ['key'   => 'mail.username'],
            ['key'   => 'mail.username', 'value' => $request->username]
        )->update(['value' => $request->username]);
        Settings::firstOrCreate(
            ['key'   => 'mail.from.address'],
            ['key'   => 'mail.from.address', 'value' => $request->fromEmail]
        )->update(['value' => $request->fromEmail]);
        Settings::firstOrCreate(
            ['key'   => 'mail.from.name'],
            ['key'   => 'mail.from.name', 'value' => $request->fromName]
        )->update(['value' => $request->fromName]);
        //  Only update the password if it has changed
        if(!empty($request->password) && $request->password !== 'NULL')
        {
            // Settings::where('key', 'mail.password')->update(['value' => $request->password]);
            Settings::firstOrCreate(
                ['key'   => 'mail.password'],
                ['key'   => 'mail.password', 'value' => $request->password]
            )->update(['value' => $request->password]);
        }

        Log::debug('Route '.Route::currentRouteName().' visited by User ID-'.Auth::user()->user_id);
        Log::debug('Submitted Data - ', $request->toArray());
        Log::notice('Email Settings have been changed by User ID-'.Auth::user()->user_id);
        return response()->json(['success' => true]);
    }


}
