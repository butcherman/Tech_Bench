<?php

namespace App\Domains\User;

use App\Events\NewUserCreated;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\User;
use App\UserSettings;

class SetUserDetails
{
    //  Create a new user
    public function createUser($request)
    {
        $request = collect($request);
        $request->put('password', bcrypt(strtoLower(Str::random(15))));
        $request->put('password_expires', Carbon::now()->subDay());

        $user = User::create($request->toArray());
        UserSettings::create([
            'user_id' => $user->user_id,
        ]);

        //  Event will trigger the welcome email
        event(new NewUserCreated($user->makeVisible('user_id')));

        return $user->user_id;
    }

    //  Update a users primary details (name and email address)
    public function updateUser($request, $userID)
    {
        User::find($userID)->update($request->toArray());

        return true;
    }

    //  Update the notification settings for when the user gets an email or dashboard notification
    public function updateSettings($request, $userID)
    {
        UserSettings::where('user_id', $userID)->update($request->toArray());

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

        return true;
    }

    //  Re-enable a user
    public function reactivateUser($userID)
    {
        User::withTrashed()->where('user_id', $userID)->restore();
        return true;
    }

    //  Disable the user
    public function disableUser($userID)
    {
        User::findOrFail($userID)->delete();
        return true;
    }
}
