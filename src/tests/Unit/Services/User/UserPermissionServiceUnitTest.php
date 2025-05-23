<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Services\User\UserPermissionsService;
use Tests\TestCase;

class UserPermissionServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | customerPermissions()
    |---------------------------------------------------------------------------
    */
    public function test_customer_permissions_admin(): void
    {
        $shouldBe = [
            'details' => [
                'create' => true,
                'update' => true,
                'manage' => true,
                'delete' => true,
            ],
            'equipment' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'contact' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'notes' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'files' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
        ];

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 2]);

        $testObj = new UserPermissionsService;
        $res = $testObj->customerPermissions($user);

        $this->assertEquals($res, $shouldBe);
    }

    public function test_customer_permissions_tech(): void
    {
        $shouldBe = [
            'details' => [
                'create' => true,
                'update' => true,
                'manage' => false,
                'delete' => false,
            ],
            'equipment' => [
                'create' => true,
                'update' => true,
                'delete' => false,
            ],
            'contact' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'notes' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
            'files' => [
                'create' => true,
                'update' => true,
                'delete' => true,
            ],
        ];

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 4]);

        $testObj = new UserPermissionsService;
        $res = $testObj->customerPermissions($user);

        $this->assertEquals($res, $shouldBe);
    }

    /*
    |---------------------------------------------------------------------------
    | techTipPermissions()
    |---------------------------------------------------------------------------
    */
    public function test_tech_tip_permissions_admin(): void
    {
        $shouldBe = [
            'manage' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
            'public' => false,
            'comment' => true,
        ];

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 1]);

        $testObj = new UserPermissionsService;
        $res = $testObj->techTipPermissions($user);

        $this->assertEquals($res, $shouldBe);
    }

    public function test_tech_tip_permissions_tech(): void
    {
        $shouldBe = [
            'manage' => false,
            'create' => true,
            'update' => false,
            'delete' => false,
            'public' => false,
            'comment' => true,
        ];

        /** @var User $user */
        $user = User::factory()->create(['role_id' => 4]);

        $testObj = new UserPermissionsService;
        $res = $testObj->techTipPermissions($user);

        $this->assertEquals($res, $shouldBe);
    }
}
