<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use Tests\TestCase;

class UserRoleUnitTest extends TestCase
{
    /** @var User */
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = UserRole::find(1);
    }

    /**
     * Model Relationships
     */
    public function test_user_role_permission_relationship(): void
    {
        $shouldBe = UserRolePermission::where('role_id', $this->model->role_id)
            ->get();

        $this->assertEquals(
            $shouldBe->toArray(),
            $this->model->UserRolePermission->toArray(),
        );
    }
}
