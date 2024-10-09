<?php

namespace App\Service\Admin;

use App\Http\Requests\Admin\UserSettingsRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;

class UserSettingsAdministrationService
{
    use AppSettingsTrait;

    /**
     * Return the current 2FA Configuration
     */
    public function getTwoFaConfig(): array
    {
        return [
            'required' => (bool) config('auth.twoFa.required'),
            'allow_save_device' => (bool) config('auth.twoFa.allow_save_device'),
        ];
    }

    /**
     * Return the current OATH Configuration
     */
    public function getOathConfig(): array
    {
        return [
            'allow_login' => (bool) config('services.azure.allow_login'),
            'allow_bypass_2fa' => (bool) config('services.azure.allow_bypass_2fa'),
            'allow_register' => (bool) config('services.azure.allow_register'),
            'default_role_id' => (int) config('services.azure.default_role_id'),
            'tenant' => config('services.azure.tenant'),
            'client_id' => config('services.azure.client_id'),
            'client_secret' => config('services.azure.client_secret') ? __('admin.fake-password') : '',
            'secret_expires' => config('services.azure.secret_expires'),
            'redirect' => config('services.azure.redirect') ?? 'https://'.config('app.url').'/auth/callback',
        ];
    }

    /**
     * Update all of the User Administration Settings
     */
    public function updateUserSettingsConfig(UserSettingsRequest $requestData): void
    {
        $this->saveSettings('auth.auto_logout_timer', intval($requestData->auto_logout_timer));
        $this->saveSettingsArray($requestData->oath, 'services.azure');
        $this->saveSettingsArray($requestData->twoFa, 'auth.twoFa');

        Log::notice('User Administration Settings updated by '.
            request()->user()->username, $requestData->except(['client_secret']));

        /**
         * If the user just enabled 2FA, they will be prompted for a code immediately.
         * Bypass by manually adding verification to session
         */
        request()->session()->put('2fa_verified', true);
    }
}
