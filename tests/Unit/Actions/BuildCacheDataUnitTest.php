<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildCacheData;
use App\Models\UserRoles;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Tests made against default database
 */
class BuildCacheDataUnitTest extends TestCase
{
    /**
     * Role Cache
     */
    public function test_build_role_cache()
    {
        $roleArr = UserRoles::all()->toArray();
        $cachedArr = BuildCacheData::buildRoleCache()->toArray();
        //  Verify that the Cache was built
        $getCache = Cache::get('users.role')->toArray();

        $this->assertEquals($roleArr, $cachedArr);
        $this->assertEquals($roleArr, $getCache);
    }

    /**
     * User Password Complexity
     */
    public function test_build_password_rules()
    {
        $passwordRules = BuildCacheData::buildPasswordRules();
        $cachedData = Cache::get('passwordRules');

        $shouldBe = [
            'Password must be at least 6 characters',
            'Must contain an Uppercase letter',
            'Must contain a Lowercase letter',
            'Must contain a Number',
            'Must contain a Special Character',
        ];

        $this->assertEquals($passwordRules, $shouldBe);
        $this->assertEquals($cachedData, $shouldBe);
    }
}
