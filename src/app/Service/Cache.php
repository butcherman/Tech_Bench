<?php

namespace App\Service;

use Illuminate\Support\Facades\Cache as FacadesCache;

/**
 * Cache will return cached items and build them if they are not already in the cache
 */
class Cache
{
    /**
     * Rules for users passwords
     */
    public static function PasswordRules()
    {
        return FacadesCache::get('password_rules', function () {
            $passwordRules = [
                'Password must be at least '.
                config('auth.passwords.settings.min_length').
                ' characters'];

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

            FacadesCache::put('passwordRules', $passwordRules);

            return $passwordRules;
        });
    }
}
