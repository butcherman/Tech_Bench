<?php

namespace App\Service;

use App\Models\CustomerFileType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\PhoneNumberType;
use App\Models\UserRole;
use Illuminate\Support\Facades\Cache as FacadesCache;
use PragmaRX\Version\Package\Version;

/**
 * Cache will return cached items and build them if they are not already in the cache
 */
class Cache
{
    /**
     * Clear a Cached Item, or the entire Cache
     *
     * @codeCoverageIgnore
     */
    public static function clearCache(array|string|null $cacheKey = null)
    {
        if ($cacheKey) {
            if (is_array($cacheKey)) {
                foreach ($cacheKey as $value) {
                    FacadesCache::forget($value);
                }
            } else {
                FacadesCache::forget($cacheKey);
            }
        } else {
            FacadesCache::flush();
        }
    }

    /**
     * Rules for users passwords
     */
    public static function PasswordRules()
    {
        return FacadesCache::get('password_rules', function () {
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
            return (new Version)->copyright();
        });
    }

    /**
     * Full List of User Roles
     */
    public static function userRoles()
    {
        return FacadesCache::get('user_roles', function () {
            return UserRole::all();
        });
    }

    /**
     * List of Equipment Categories
     */
    public static function equipmentCategories()
    {
        return FacadesCache::get('equipmentCategories', function () {
            return EquipmentCategory::with('EquipmentType')->get();
        });
    }

    /**
     * List of Equipment Types
     */
    public static function equipmentTypes()
    {
        return FacadesCache::get('equipmentTypes', function () {
            return EquipmentType::all();
        });
    }

    /**
     * List of phone number types
     */
    public static function phoneTypes()
    {
        return FacadesCache::get('phoneTypes', function () {
            return PhoneNumberType::all();
        });
    }

    /**
     * List of file types that can be assigned to Customer Files
     */
    public static function fileTypes()
    {
        return FacadesCache::get('fileTypes', function () {
            return CustomerFileType::all();
        });
    }
}
