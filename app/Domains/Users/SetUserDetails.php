<?php

namespace App\Domains\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\UserBasicAccountRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserNotificationSettingsRequest;

use Carbon\Carbon;

use App\User;
use App\UserSettings;

class SetUserDetails
{
    protected $userID;

    //  Update the user's basic settings
    public function updateUserDetails(UserBasicAccountRequest $request, $userID = null)
    {
        if(!$userID)
        {
            $userID = Auth::user()->user_id;
        }

        if(isset($request->username))
        {
            $details = User::find($userID)->update(
            [
                'username'   => $request->username,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email
            ]);
        }
        else
        {
            $details = User::find($userID)->update(
            [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email
            ]);
        }

        Log::notice('User ID '.$userID.' has been updated by '.Auth::user()->full_name.'.  Details - ', array($details));
        return true;
    }

    //  Update the user's email notification settings
    public function updateUserNotifications(UserNotificationSettingsRequest $request, $userID = null)
    {
        if(!$userID)
        {
            $userID = Auth::user()->user_id;
        }

        $details = UserSettings::where('user_id', Auth::user()->user_id)->update(
        [
            'em_tech_tip'     => $request->em_tech_tip,
            'em_file_link'    => $request->em_file_link,
            'em_notification' => $request->em_notification,
            'auto_del_link'   => $request->auto_del_link,
        ]);

        Log::notice('User ID '.$userID.' notification settings have been updated by '.Auth::user()->full_name.'.  Details - ', array($details));
        return true;
    }

    //  Update the user's password
    public function updateUserPassword(Request $request, $userID = null)
    {
        if(!$userID)
        {
            $userID = Auth::user()->user_id;
        }

        //  Determine if there is a new password expire's date
        $newExpire = config('auth.passwords.settings.expire') != null ?
            Carbon::now()->addDays(config('auth.passwords.settings.expire')) :
            null;

        User::find($userID)->update([
            'password' => bcrypt($request->newPass),
            'password_expires' => $newExpire,
        ]);

        Log::notice('Password has been changed for User ID '.$userID.' by '.Auth::user()->full_name);
        return true;
    }
}
