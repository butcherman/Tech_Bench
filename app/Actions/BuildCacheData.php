<?php

namespace App\Actions;

use App\Models\UserRoles;
use Illuminate\Support\Facades\Cache;

/**
 * When calling for a cached item, if it does not exist a static function from
 * this class will be called to build the cache
 */
class BuildCacheData
{
    /**
     * Cache User Role Data
     */
    public static function buildRoleCache()
    {
        return Cache::get('users.roles', function () {
            Cache::put('users.role', $roleList = UserRoles::all());

            return $roleList;
        });
    }

    /**
     * User Password Complexity Rules
     */
    public static function buildPasswordRules()
    {
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

        Cache::put('passwordRules', $passwordRules);

        return $passwordRules;
    }
}
