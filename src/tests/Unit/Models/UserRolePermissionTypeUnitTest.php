<?php

namespace Tests\Unit\Models;

use App\Models\UserRolePermissionCategory;
use App\Models\UserRolePermissionType;
use Tests\TestCase;

class UserRolePermissionTypeUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = UserRolePermissionType::where('perm_type_id', 1)->first();
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('group', $this->model->toArray());
        $this->assertArrayHasKey('feature_enabled', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_user_role_permission_category_relationship()
    {
        $roleCat = UserRolePermissionCategory::where('role_cat_id', $this->model->role_cat_id)
            ->first();

        $this->assertEquals($this->model->UserRolePermissionCategory, $roleCat);
    }
}
