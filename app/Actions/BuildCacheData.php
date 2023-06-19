<?php

namespace App\Actions;

use App\Models\UserRoles;
use Illuminate\Support\Facades\Cache;

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
}
