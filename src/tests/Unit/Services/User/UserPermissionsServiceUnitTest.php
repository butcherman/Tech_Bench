<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Services\User\UserPermissionsService;
use Tests\TestCase;

class UserPermissionsServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | customerPermissions()
    |---------------------------------------------------------------------------
    */
    public function test_customer_permissions_installer(): void
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

        $testObj = new UserPermissionsService;
        $res = $testObj->customerPermissions(
            User::factory()->create(['role_id' => 1])
        );

        $this->assertEquals($shouldBe, $res);
    }

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

        $testObj = new UserPermissionsService;
        $res = $testObj->customerPermissions(
            User::factory()->create(['role_id' => 2])
        );

        $this->assertEquals($shouldBe, $res);
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

        $testObj = new UserPermissionsService;
        $res = $testObj->customerPermissions(
            User::factory()->create(['role_id' => 4])
        );

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | techTipPermissions()
    |---------------------------------------------------------------------------
    */
    public function test_tech_tip_permissions_installer(): void
    {
        $shouldBe = [
            'manage' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
            'public' => false,
            'comment' => true,
        ];

        $testObj = new UserPermissionsService;
        $res = $testObj->techTipPermissions(
            User::factory()->create(['role_id' => 1])
        );

        $this->assertEquals($shouldBe, $res);
    }

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

        $testObj = new UserPermissionsService;
        $res = $testObj->techTipPermissions(
            User::factory()->create(['role_id' => 2])
        );

        $this->assertEquals($shouldBe, $res);
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

        $testObj = new UserPermissionsService;
        $res = $testObj->techTipPermissions(
            User::factory()->create(['role_id' => 4])
        );

        $this->assertEquals($shouldBe, $res);
    }

    public function test_tech_tip_permissions_tech_public_enabled(): void
    {
        config(['tech-tips.allow_public' => true]);

        $this->changeRolePermission(4, 'Add Public Tech Tip', true);

        $shouldBe = [
            'manage' => false,
            'create' => true,
            'update' => false,
            'delete' => false,
            'public' => true,
            'comment' => true,
        ];

        $testObj = new UserPermissionsService;
        $res = $testObj->techTipPermissions(
            User::factory()->create(['role_id' => 4])
        );

        $this->assertEquals($shouldBe, $res);
    }
}
