<?php

namespace App\Services\Admin;

use App\Jobs\User\UpdatePasswordExpireJob;
use App\Traits\AppSettingsTrait;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class UserGlobalSettingsService
{
    use AppSettingsTrait;

    /**
     * Get the password Policy settings
     */
    public function getPasswordPolicy(): array
    {
        return [
            'expire' => config('auth.passwords.settings.expire'),
            'min_length' => config('auth.passwords.settings.min_length'),
            'contains_uppercase' => config('auth.passwords.settings.contains_uppercase'),
            'contains_lowercase' => config('auth.passwords.settings.contains_lowercase'),
            'contains_number' => config('auth.passwords.settings.contains_number'),
            'contains_special' => config('auth.passwords.settings.contains_special'),
            'disable_compromised' => config('auth.passwords.settings.disable_compromised'),
        ];
    }

    /**
     * Update the User Password Policy
     */
    public function savePasswordPolicy(Collection $requestData): void
    {
        // If the password expire field has changed, we must update all users
        if ($requestData->get('expire') !== config('auth.passwords.settings.expire')) {
            dispatch(new UpdatePasswordExpireJob)->delay(now()->addMinutes(5));
        }

        $this->saveSettingsArray(
            $requestData->toArray(),
            'auth.passwords.settings'
        );
    }

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
            'client_secret' => config('services.azure.client_secret')
                ? __('admin.fake-password') : '',
            'secret_expires' => config('services.azure.secret_expires')
                ? Carbon::parse(config('services.azure.secret_expires'))->format('m/d/Y') : null,
            'redirect' => config('services.azure.redirect') ?? 'https://'.config('app.url').'/auth/callback',
        ];
    }

    /**
     * Return the number of days until the Oath Certificate expires
     */
    public function getOathCertExpiresDays(): int
    {
        $certExpires = Carbon::parse(config('services.azure.secret_expires'));

        return floor(Carbon::now()->diffInDays($certExpires));
    }

    /**
     * Update all of the User Administration Settings
     */
    public function updateUserSettingsConfig(Collection $requestData): void
    {
        $this->saveSettings(
            'auth.auto_logout_timer',
            intval($requestData->get('auto_logout_timer'))
        );

        $this->saveSettingsArray($requestData->get('oath'), 'services.azure');
        $this->saveSettingsArray($requestData->get('twoFa'), 'auth.twoFa');
    }
}
