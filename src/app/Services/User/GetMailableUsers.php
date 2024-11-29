<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\UserSettingType;
use Illuminate\Database\Eloquent\Collection;

class GetMailableUsers
{
    /**
     * Return a list of all users that can get email notifications, except the
     * ignore User provided.
     */
    public function getAllMailable(?User $ignore = null): Collection
    {
        return User::whereHas('UserSettings', function ($q) {
            $settingName = 'Receive Email Notifications';
            $settingId = UserSettingType::where('name', $settingName)
                ->first()
                ->setting_type_id;

            $q->where('setting_type_id', $settingId)
                ->where('value', true);
        })->when($ignore, function ($q) use ($ignore) {
            $q->whereNot('user_id', $ignore->user_id);
        })->get();
    }
}
