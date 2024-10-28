<?php

namespace App\Facades;

use Illuminate\Support\Facades\Cache;
use PragmaRX\Version\Package\Version;

class CacheHelper
{
    /**
     * Clear a specific cache key, or wipe the entire cache
     */
    public function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget($key);

            return;
        }

        Cache::flush();
    }

    /**
     * Build the password rules for users
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function passwordRules()
    {
        return Cache::rememberForever('password_rules', function () {
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

    /**
     * Get the version of the Tech Bench Application
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function appData()
    {
        return Cache::rememberForever('appData', function () {
            $version = new Version;

            return [
                'name' => config('app.name'),
                'company_name' => config('app.company_name'),
                'logo' => config('app.logo'),
                'version' => $version->full(),
                'copyright' => $version->copyright(),
                'build' => $version->commit(),
                'build_date' => $version->build(),

                // File information
                'fileData' => [
                    'maxSize' => config('filesystems.max_filesize'),
                    'chunkSize' => config('filesystems.chunk_size'),
                ],

                // Inactivity timeout
                'idle_timeout' => intval(config('auth.auto_logout_timer')),
            ];
        });
    }
}
