<?php

// TODO - Refactor

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserSettingsRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('create', User::class);

        return Inertia::render('Admin/User/UserSettings', [
            'auto-logout-timer' => intval(config('auth.auto_logout_timer')),
            'two-fa' => [
                'required' => (bool) config('auth.twoFa.required'),
                'allow_save_device' => (bool) config('auth.twoFa.allow_save_device'),
            ],
            'oath' => [
                'allow_login' => (bool) config('services.azure.allow_login'),
                'allow_bypass_2fa' => (bool) config('services.azure.allow_bypass_2fa'),
                'allow_register' => (bool) config('services.azure.allow_register'),
                'tenant' => config('services.azure.tenant'),
                'client_id' => config('services.azure.client_id'),
                'client_secret' => config('services.azure.client_secret') ? __('admin.fake-password') : '',
                'secret_expires' => config('services.azure.secret_expires'),
                'redirect' => config('services.azure.redirect') ?? 'https://'.config('app.url').'/auth/callback',
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(UserSettingsRequest $request)
    {
        $request->updateUserSettings();

        Log::notice('User Administration Settings updated by '.
            $request->user()->username, $request->except(['client_secret']));

        //  If the user just enabled 2FA, they will be prompted for a code immediately.
        //  Bypass by manually adding verification to session
        $request->session()->put('2fa_verified', true);

        return back()->with('success', __('admin.user.settings_updated'));
    }
}
