<?php

namespace Tests\Unit\Models;

use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Tests\TestCase;

class UserRolePermissionUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = UserRolePermission::where('id', 1)->first();
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('description', $this->model);
    }

    /**
     * Model Relationships
     */
    public function test_user_role_permission_type_relationship()
    {
        $permType = UserRolePermissionType::where('perm_type_id', $this->model->perm_type_id)
            ->first();
        $this->assertEquals(
            $this->model->UserRolePermissionType->perm_type_id,
            $permType->perm_type_id
        );
    }
}
