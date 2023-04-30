<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PasswordPolicyRequest;
use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserPasswordPolicyController extends Controller
{
    use AppSettingsTrait;

    /**
     * Show the password policy form
     */
    public function index()
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/PasswordPolicy', [
            'policy' => [
                'expire' => config('auth.passwords.settings.expire'),
                'min_length' => config('auth.passwords.settings.min_length'),
                'contains_uppercase' => (bool) config('auth.passwords.settings.contains_uppercase'),
                'contains_lowercase' => (bool) config('auth.passwords.settings.contains_lowercase'),
                'contains_number' => (bool) config('auth.passwords.settings.contains_number'),
                'contains_special' => (bool) config('auth.passwords.settings.contains_special'),
            ],
        ]);
    }

    /**
     * Update the password policy
     */
    public function store(PasswordPolicyRequest $request)
    {
        foreach ($request->all() as $settingKey => $settingValue) {
            $this->saveSettings('auth.passwords.settings.'.$settingKey, $settingValue);
        }
        Log::notice('Password policy has been updated by '.$request->user()->username, $request->toArray());

        //  If the first time setup flag is set, we will disable it
        if(config('app.first_time_setup'))
        {
            $this->clearSetting('app.first_time_setup');
        }

        return back()->with('success', __('admin.password_policy'));
    }
}
