<?php

namespace Tests;

use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function changeRolePermission(int $roleId, string $permName, bool $value = false)
    {
        $permId = UserRolePermissionType::where('description', $permName)->first()->perm_type_id;
        UserRolePermission::where('role_id', $roleId)
            ->where('perm_type_id', $permId)
            ->update([
                'allow' => $value
            ]);
    }
}
