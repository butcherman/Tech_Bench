<?php

namespace App\Domains\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\User;
use App\UserSettings;

class SetUserDetails
{
    //  Update a users primary details (name and email address)
    public function updateUser($request, $userID)
    {
        User::find($userID)->update($request->toArray());
        Log::notice('User ID '.$userID.' has been updated by '.Auth::user()->full_name.'.  Details - ', $request->toArray());

        return true;
    }

    //  Update the notification settings for when the user gets an email or dashboard notification
    public function updateSettings($request, $userID)
    {
        UserSettings::where('user_id', $userID)->update($request->toArray());
        Log::notice('Settings for User ID '.$userID.' have been updated by '.Auth::user()->full_name.'.  Details - ', $request->toArray());

        return true;
    }

    //  Update the users password
    public function updatePassword($password, $userID, $forceChange = false)
    {
        //  Determine if there is a new password expire's date (only triggered by an administrator)
        if(!$forceChange)
        {
            $newExpire = config('auth.passwords.settings.expire') != null ?
            Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;
        }
        else
        {
            $newExpire = Carbon::now()->subDay();
        }

        //  Update the users password
        User::find($userID)->update([
            'password'         => bcrypt($password),
            'password_expires' => $newExpire,
        ]);

        Log::notice('Password has been changed for User ID '.$userID.' by '.Auth::user()->full_name);
        return true;
    }
}
