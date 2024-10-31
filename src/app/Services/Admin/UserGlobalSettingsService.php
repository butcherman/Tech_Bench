<?php

namespace App\Services\Admin;

use App\Jobs\User\UpdatePasswordExpireJob;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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
    public function savePasswordPolicy(Collection $requestData)
    {
        // If the password expire field has changed, we must update all users
        if ($requestData->get('expire') !== config('auth.passwords.settings.expire')) {
            dispatch(new UpdatePasswordExpireJob)->delay(now()->addMinutes(5));
        }

        $this->saveSettingsArray(
            $requestData->toArray(),
            'auth.passwords.settings'
        );

        Log::notice(
            request()->user()->username.' has updated the User Password Policy',
            $requestData->toArray()
        );
    }
}
