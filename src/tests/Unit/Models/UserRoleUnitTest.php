<?php

namespace Tests\Unit\Models;

use App\Models\UserRole;
use App\Models\UserRolePermission;
use Tests\TestCase;

class UserRoleUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = UserRole::where('role_id', 1)->first();
    }

    /**
     * Route Model Binding Key
     */
    public function test_route_binding_key()
    {
        $this->assertEquals($this->model->getRouteKeyName(), 'role_id');
    }

    /**
     * Model Relationships
     */
    public function test_user_role_permission_relationship()
    {
        $rolePermissions = UserRolePermission::where('role_id', $this->model->role_id)
            ->get();
        $this->assertEquals(
            $this->model->UserRolePermission->toArray(),
            $rolePermissions->toArray()
        );
    }
}
