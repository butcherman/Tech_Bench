<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\PasswordPolicyRequest;
use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * View and modify the Global User Password policy
 */
class PasswordPolicyController extends Controller
{
    use AppSettingsTrait;

    public function get()
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

    public function set(PasswordPolicyRequest $request)
    {
        $this->saveSettingsArray($request->toArray(), 'auth.passwords.settings');

        Log::notice('Administrator '.$request->user()->username.' has updated the User Password Policy', $request->toArray());

        return back()->with('success', __('admin.user.password_policy'));
    }
}
