<?php

namespace App\Services\Misc;

use App\Models\CustomerFileType;
use App\Models\DataFieldType;
use App\Models\EquipmentCategory;
use App\Models\EquipmentType;
use App\Models\PhoneNumberType;
use App\Models\TechTipType;
use App\Models\UserRole;
use App\Models\UserSettingType;
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

    /**
     * Get a list of User Roles
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function userRoles()
    {
        return Cache::rememberForever('userRoles', function () {
            return UserRole::all();
        });
    }

    /**
     * Get a list of all the possible User Settings Types
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function userSettingsType()
    {
        return Cache::rememberForever('userSettingsType', function () {
            return UserSettingType::all();
        });
    }

    /**
     * Get a list of all Equipment Categories with their Equipment Types.
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function equipmentCategories()
    {
        return Cache::rememberForever('equipmentCategories', function () {
            return EquipmentCategory::with('EquipmentType')->get();
        });
    }

    /**
     * Get a list of all Equipment Types.
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function equipmentTypes()
    {
        return Cache::rememberForever('equipmentTypes', function () {
            return EquipmentType::all();
        });
    }

    /**
     * Get a list of all Data Field Types for Equipment
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function dataFieldTypes()
    {
        return Cache::rememberForever('dataFieldTypes', function () {
            return DataFieldType::all();
        });
    }

    /**
     * Get a list of all Customer File Types for uploaded files.
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function fileTypes()
    {
        return Cache::rememberForever('fileTypes', function () {
            return CustomerFileType::all();
        });
    }

    /**
     * Get a list of all phone types for contact phone numbers
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function phoneTypes()
    {
        return Cache::rememberForever('phoneTypes', function () {
            return PhoneNumberType::all();
        });
    }

    /**
     * Get a list of all Tech Tip Types that can be assigned to a Tech Tip.
     *
     * @return \Illuminate\Cache\TCacheValue
     */
    public function techTipTypes()
    {
        return Cache::rememberForever('techTipTypes', function () {
            return TechTipType::all();
        });
    }
}
