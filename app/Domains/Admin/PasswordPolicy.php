<?php

namespace App\Domains\Admin;

use App\User;
use Carbon\Carbon;

class PasswordPolicy extends SettingsDomain
{
    public function setPolicy($request)
    {
        //  Update the main policy
        $this->updateSettings('auth.passwords.settings.expire', $request->expire);

        //  Update all users to reflect the new policy
        if($request->expire == 0)
        {
            User::where('password_expires', '>', NOW())->update([
                'password_expires' => null,
            ]);
        }
        else
        {
            $newExpire = Carbon::now()->addDays($request->expire);
            User::whereNull('password_expires')->update([
                'password_expires' => $newExpire,
            ]);
        }

        return true;
    }
}
