<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Domains\Admin\SettingsDomain;
use App\Domains\Admin\SetEmailProperties;

use App\Http\Requests\Settings\NewLogoRequest;
use App\Http\Requests\Settings\EmailSettingsRequest;
use App\Http\Requests\Settings\GeneralSettingsRequest;

class SettingsController extends Controller
{
    public function settingsForm()
    {
        return view('settings.generalSettings', [
            'tz_list'  => \Timezonelist::toArray(),
            'settings' => [
                'timezone' => config('app.timezone'),
                'filesize' => config('filesystems.paths.max_size'),
            ],
        ]);
    }

    public function submitSettings(GeneralSettingsRequest $request)
    {
        (new SettingsDomain)->submitNewSettings($request);

        Log::notice('General Settings have been updated by '.Auth::user()->full_name.'.  Data - ', $request->toArray());
        return response()->json(['success' => true]);
    }

    public function submitLogo(NewLogoRequest $request)
    {
        $newPath = (new SettingsDomain)->saveNewLogo($request->file);

        Log::notice('New Logo uploaded by '.Auth::user()->full_name.'.  Details - ', ['Logo Path' => $newPath]);
        return response()->json(['url' => $newPath]);
    }

    public function emailForm()
    {
        return view('settings.emailSettings', [
            'current' => [
                'host'         => config('mail.host'),
                'port'         => config('mail.port'),
                'encryption'   => config('mail.encryption'),
                'username'     => config('mail.username'),
                'from_address' => config('mail.from.address'),
            ],
        ]);
    }

    public function sendTestEmail(EmailSettingsRequest $request)
    {
        $obj = new SetEmailProperties;
        $res = $obj->sendTestEmail($request, Auth::user()->email);

        return response()->json(['success' => $res]);
    }

    public function submitEmailSettings(EmailSettingsRequest $request)
    {
        (new SetEmailProperties)->saveEmailSettings($request);
        Log::notice('Email Settings have been modified by '.Auth::user()->full_name);
        return response()->json(['success' => true]);
    }
}
