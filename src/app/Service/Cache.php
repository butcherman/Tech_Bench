<?php

namespace App\Service;

use App\Models\UserRole;
use Illuminate\Support\Facades\Cache as FacadesCache;
use PragmaRX\Version\Package\Version;

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

    /**
     * Current Version of application
     */
    public static function version()
    {
        return FacadesCache::get('version', function () {
            $version = (new Version)->full();

            FacadesCache::put('version', $version);

            return $version;
        });
    }

    /**
     * Copyright Year String
     */
    public static function copyright()
    {
        return FacadesCache::get('copyright', function () {
            $copyright = (new Version)->copyright();

            FacadesCache::put('copyright', $copyright);

            return $copyright;
        });
    }

    /**
     * Full List of User Roles
     */
    public static function userRoles()
    {
        return FacadesCache::get('user_roles', function () {
            $roleList = UserRole::all();

            FacadesCache::put('user_roles', $roleList);

            return $roleList;
        });
    }
}
