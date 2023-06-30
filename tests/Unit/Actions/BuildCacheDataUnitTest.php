<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildCacheData;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BuildCacheDataUnitTest extends TestCase
{
    /**
     * Role Cache
     */
    public function test_role_cache()
    {
        $roleArr = UserRoles::all()->toArray();
        $cachedArr = BuildCacheData::buildRoleCache()->toArray();
        //  Verify that the Cache was built
        $getCache = Cache::get('users.role')->toArray();

        $this->assertEquals($roleArr, $cachedArr);
        $this->assertEquals($roleArr, $getCache);
    }
}
