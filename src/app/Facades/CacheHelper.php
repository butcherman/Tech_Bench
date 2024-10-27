<?php

namespace App\Facades;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * Clear a specific cache key, or wipe the entire cache
     */
    public function clearCache(?string $key): void
    {
        if ($key) {
            Cache::forget($key);

            return;
        }

        Cache::flush();
    }

    /**
     * Build the password rules for users
     */
    public function passwordRules(): array
    {
        return Cache::get('password_rules', function () {
            $passwordRules = [
                'Password must be at least '.
                config('auth.passwords.settings.min_length').
                ' characters',
            ];

            if (config('auth.passwords.settings.contains_uppercase')) {
                $passwordRules[] = 'Must contain an Uppercase letter';
            }

            if (config('auth.passwords.settings.contains_lowercase')) {
                $passwordRules[] = 'Must contain a Lowercase letter';
            }

            if (config('auth.passwords.settings.contains_number')) {
                $passwordRules[] = 'Must contain a Number';
            }

            if (config('auth.passwords.settings.contains_special')) {
                $passwordRules[] = 'Must contain a Special Character';
            }

            return $passwordRules;
        });
    }
}
