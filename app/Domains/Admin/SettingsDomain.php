<?php

namespace App\Domains\Admin;

use App\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SettingsDomain
{
    public function updateSettings($key, $value)
    {
        Settings::firstOrCreate(
            ['key' => $key],
            ['key' => $key, 'value' => $value]
        )->update(['value' => $value]);
        return true;
    }
}
