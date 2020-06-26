<?php

namespace App\Domains\Admin;

use App\User;

use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class PasswordPolicy extends SettingsDomain
{
    //  Update the "user password expires" timer for all users
    public function setPolicy($request)
    {
        //  Update the main policy
        $this->updateSettings('auth.passwords.settings.expire', $request->expire);
        Log::notice('Password Policy upated.  Passwords set to expire in '.$request->expire.' days.');

        //  Update all users to reflect the new policy
        if($request->expire == 0)
        {
            User::where('password_expires', '>', NOW())->update([
                'password_expires' => null,
            ]);
            Log::notice('All users password set to never expire ');
        }
        else
        {
            $newExpire = Carbon::now()->addDays($request->expire);
            User::whereNull('password_expires')->update([
                'password_expires' => $newExpire,
            ]);
            Log::notice('All users passwords set to expire in '.$request->expire.' days on '.$newExpire);
        }

        return true;
    }
}
