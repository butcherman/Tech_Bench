<?php

namespace Tests\Unit\Models;

use App\Models\UserRolePermissionCategory;
use App\Models\UserRolePermissionTypes;
use Tests\TestCase;

class UserRolePermissionTypesUnitTest extends TestCase
{
    protected $roleType;

    public function setUp():void
    {
        parent::setUp();

        $this->roleType = UserRolePermissionTypes::find(1);
    }

    /**
     * Test Additional Attributes
     */
    public function test_additional_attributes()
    {
        $this->assertArrayHasKey('group', $this->roleType->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $category = UserRolePermissionCategory::find($this->roleType->role_cat_id);
        $this->assertEquals($this->roleType->UserRolePermissionCategory->toArray(), $category->toArray());
    }
}
