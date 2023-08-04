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
}
