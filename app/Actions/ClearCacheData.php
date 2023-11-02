<?php

namespace App\Actions;

use Illuminate\Support\Facades\Cache;

/**
 * When making administrative modifications, some Cached data may need to
 * be cleared and rebuilt
 */
class ClearCacheData
{
    public static function clearEquipmentCache()
    {
        // Equipment Options list for Select Box
        // Cache::clear('equipmentOptionsList');
        // BuildCacheData::buildEquipmentOptionsList();
    }
}
