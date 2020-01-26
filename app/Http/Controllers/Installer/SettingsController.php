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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

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

    //  System backups index page
    public function backupsIndex()
    {
        return view('installer.backupsIndex');
    }

    //  Retrieve the list of backups
    public function getBackups()
    {
        $backups = Storage::disk('backup')->files();
        $buArray = [];

        //  Filter list to include timestamp
        foreach($backups as $backup)
        {
            $parts = pathinfo($backup);
            if($parts['extension'] === 'zip')
            {
                $buArray[] = [
                    'name' => $backup,
                    'date' => Carbon::createFromTimestamp(Storage::disk('backup')->lastModified($backup))->format('M d, Y h:m a'),
                ];
            }
        }

        return $buArray;
    }

    //  Delete a backup
    public function delBackup($name)
    {
        Storage::disk('backup')->delete($name);

        return response()->json(['success' => true]);
    }

    //  Download a backup
    public function downloadBackup($name)
    {
        if(Storage::disk('backup')->exists($name))
        {
            return Storage::disk('backup')->download($name);
        }

        return view('err.badFile');

    }

    //  Create a new backup
    public function runBackup()
    {
        Artisan::call('tb-backup:run');

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
        //  TODO - fix this and make it work
        return response()->json(['success' => false]);
        // $request->validate([
        //     'host'       => 'required',
        //     'port'       => 'required|numeric',
        //     'encryption' => 'required',
        //     'username'   => 'required',
        //     'fromEmail'  => 'required',
        //     'fromName'   => 'required',
        // ]);

        // //  Update each setting
        // Settings::firstOrCreate(
        //     ['key'   => 'mail.host'],
        //     ['key'   => 'mail.host', 'value' => $request->host]
        // )->update(['value' => $request->host]);
        // Settings::firstOrCreate(
        //     ['key'   => 'mail.port'],
        //     ['key'   => 'mail.port', 'value' => $request->port]
        // )->update(['value' => $request->port]);
        // Settings::firstOrCreate(
        //     ['key'   => 'mail.encryption'],
        //     ['key'   => 'mail.encryption', 'value' => $request->encryption]
        // )->update(['value' => $request->encryption]);
        // Settings::firstOrCreate(
        //     ['key'   => 'mail.username'],
        //     ['key'   => 'mail.username', 'value' => $request->username]
        // )->update(['value' => $request->username]);
        // Settings::firstOrCreate(
        //     ['key'   => 'mail.from.address'],
        //     ['key'   => 'mail.from.address', 'value' => $request->fromEmail]
        // )->update(['value' => $request->fromEmail]);
        // Settings::firstOrCreate(
        //     ['key'   => 'mail.from.name'],
        //     ['key'   => 'mail.from.name', 'value' => $request->fromName]
        // )->update(['value' => $request->fromName]);
        // //  Only update the password if it has changed
        // if (!empty($request->password) && $request->password !== 'NULL') {
        //     // Settings::where('key', 'mail.password')->update(['value' => $request->password]);
        //     Settings::firstOrCreate(
        //         ['key'   => 'mail.password'],
        //         ['key'   => 'mail.password', 'value' => $request->password]
        //     )->update(['value' => $request->password]);
        // }

        // try
        // {
        //     Mail::to(Auth::user())->send(new TestEmail($request));
        // }
        // catch (\Exception $e)
        // {
        //     // return $e->getMessage();
        //     return response()->json(['success' => false, 'message' => $e->getMessage()]);
        // }

        // return response()->json(['success' => true, 'message' => 'Email successfully sent to '.Auth::user()->email]);
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

    // protected function updateEmailSettings($data)
    // {
    //     //  Update each setting
    //     Settings::firstOrCreate(
    //         ['key'   => 'mail.host'],
    //         ['key'   => 'mail.host', 'value' => $data->host]
    //     )->update(['value' => $data->host]);
    //     Settings::firstOrCreate(
    //         ['key'   => 'mail.port'],
    //         ['key'   => 'mail.port', 'value' => $data->port]
    //     )->update(['value' => $data->port]);
    //     Settings::firstOrCreate(
    //         ['key'   => 'mail.encryption'],
    //         ['key'   => 'mail.encryption', 'value' => $data->encryption]
    //     )->update(['value' => $data->encryption]);
    //     Settings::firstOrCreate(
    //         ['key'   => 'mail.username'],
    //         ['key'   => 'mail.username', 'value' => $data->username]
    //     )->update(['value' => $data->username]);
    //     Settings::firstOrCreate(
    //         ['key'   => 'mail.from.address'],
    //         ['key'   => 'mail.from.address', 'value' => $data->fromEmail]
    //     )->update(['value' => $data->fromEmail]);
    //     Settings::firstOrCreate(
    //         ['key'   => 'mail.from.name'],
    //         ['key'   => 'mail.from.name', 'value' => $data->fromName]
    //     )->update(['value' => $data->fromName]);
    //     //  Only update the password if it has changed
    //     if (!empty($data->password) && $data->password !== 'NULL') {
    //         // Settings::where('key', 'mail.password')->update(['value' => $request->password]);
    //         Settings::firstOrCreate(
    //             ['key'   => 'mail.password'],
    //             ['key'   => 'mail.password', 'value' => $data->password]
    //         )->update(['value' => $data->password]);
    //     }

    //     return true;
    // }


}
