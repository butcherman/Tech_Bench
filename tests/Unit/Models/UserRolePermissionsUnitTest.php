<?php

namespace Tests\Unit\Models;

use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use Tests\TestCase;

class UserRolePermissionsUnitTest extends TestCase
{
    protected $permission;

    public function setUp(): void
    {
        parent::setUp();

        $this->permission = UserRolePermissions::find(1);
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $permType = UserRolePermissionTypes::where('perm_type_id', $this->permission->perm_type_id)->first();
        $this->assertEquals($this->permission->UserRolePermissionTypes->perm_type_id, $permType->perm_type_id);
    }
}
