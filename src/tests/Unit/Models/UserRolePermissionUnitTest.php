<?php

namespace Tests\Unit\Models;

use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Tests\TestCase;

class UserRolePermissionUnitTest extends TestCase
{
    /** @var UserRolePermission */
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = UserRolePermission::find(1);
    }

    /**
     * Model Relationships
     */
    public function test_user_role_permission_type_relationship(): void
    {
        // Testing default data - Role Permission 1 is type App Settings
        $permType = UserRolePermissionType::find(1);
        $this->assertEquals(
            $this->model->UserRolePermissionType,
            $permType
        );
    }
}
