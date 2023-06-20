<?php

namespace Tests\Unit\Models;

use App\Models\UserRolePermissions;
use App\Models\UserRoles;
use Tests\TestCase;

class UserRolesUnitTest extends TestCase
{
    protected $role;

    public function setUp(): void
    {
        parent::setUp();

        $this->role = UserRoles::where('role_id', 1)->first();
    }

    /**
     * Test Route Model Binding
     */
    public function test_route_model_binding()
    {
        $this->assertEquals($this->role->getRouteKeyName(), 'role_id');
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $rolePermissions = UserRolePermissions::where('role_id', $this->role->role_id)->get();
        $this->assertEquals($this->role->UserRolePermissions->toArray(), $rolePermissions->toArray());
    }
}
