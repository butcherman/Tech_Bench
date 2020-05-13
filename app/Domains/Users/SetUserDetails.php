<?php

namespace App\Domains\Users;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserBasicAccountRequest;
use App\Http\Requests\UserNotificationSettingsRequest;

use App\Notifications\NewUserEmail;

use Carbon\Carbon;

use App\User;
use App\UserSettings;
use App\UserInitialize;

class SetUserDetails
{
    protected $userID;

    //  Create a new user
    public function createNewUser(UserCreateRequest $request)
    {
        //  Create the user
        $newUser = User::create([
            'role_id'          => $request->role_id,
            'username'         => $request->username,
            'first_name'       => $request->first_name,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'password'         => bcrypt(strtolower(Str::random(15))),
            'password_expires' => Carbon::now()->subDay(),
        ]);
        $userID = $newUser->user_id;
        Log::notice('New User created by '.Auth::user()->full_name.'.  Data - ', $newUser->toArray());

        //  Create the user settings table
        UserSettings::create([
            'user_id' => $userID,
        ]);

        //  Create the setup user link
        $hash = strtolower(Str::random(30));
        UserInitialize::create([
            'username' => $request->username,
            'token'    => $hash
        ]);
        Log::info('User Initialize link created for User ID '.$userID.'. New Link Hash - '.$hash);

        //  Email the new user
        Notification::send($newUser, new NewUserEmail($newUser, $hash));

        return true;
    }

    //  Disable a user
    public function disableUser($userID)
    {
        $userData = User::find($userID);
        if($userData->role_id < Auth::user()->role_id)
        {
            abort(403, 'You cannot disable a user with higher permissions than you');
        }

        $user = User::find($userID);
        Log::alert('User '.$user->full_name.' has been deactivated by '.Auth::user()->full_name.'.  User Details - ', array($user));
        $user->delete();

        return true;
    }

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
                'role_id'    => $request->role_id,
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
    public function updateUserPassword($password, $userID = null, $forceChange = false)
    {
        if(!$userID)
        {
            $userID = Auth::user()->user_id;
        }
        else
        {
            //  Verify that the user trying to change the password does not have less permissions than the user being reset
            $userData = User::find($userID);
            if($userData->role_id < Auth::user()->role_id)
            {
                abort(403, 'Unable to Reset Password for user with more permissions than you');
            }
        }

        //  Determine if there is a new password expire's date
        if(!$forceChange)
        {
            $newExpire = config('auth.passwords.settings.expire') != null ?
               Carbon::now()->addDays(config('auth.passwords.settings.expire')) : null;
        }
        else
        {
            $newExpire = Carbon::now()->subDay();
        }

        User::find($userID)->update([
            'password'         => bcrypt($password),
            'password_expires' => $newExpire,
        ]);

        Log::notice('Password has been changed for User ID '.$userID.' by '.Auth::user()->full_name);
        return true;
    }
}
