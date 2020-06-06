<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use App\Domains\Admin\SettingsDomain;

use App\Http\Requests\Settings\NewLogoRequest;

class SettingsController extends Controller
{
    public function submitLogo(NewLogoRequest $request)
    {
        $newPath = (new SettingsDomain)->saveNewLogo($request->file);
        Log::notice('New Logo uploaded by '.Auth::user()->full_name.'.  Details - ', ['Logo Path' => $newPath]);

        return response()->json(['url' => $newPath]);
    }
}
