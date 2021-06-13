<?php

namespace App\Http\Controllers\User;

use App\Models\UserSetting;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserSettingsRequest;

class UserSettingsController extends Controller
{
    /**
     *  Update the users settings
     */
    public function __invoke(UserSettingsRequest $request)
    {
        foreach($request->settingsData as $setting)
        {
            UserSetting::where('user_id', $request->user()->user_id)->where('setting_type_id', $setting['setting_type_id'])->update([
                'value' => $setting['value'],
            ]);
        }

        return back()->with(['message' => 'Settings Updated', 'type' => 'success']);
    }
}
