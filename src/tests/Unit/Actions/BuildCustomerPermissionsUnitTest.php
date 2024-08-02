<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildCustomerPermissions;
use App\Models\User;
use Tests\TestCase;

class BuildCustomerPermissionsUnitTest extends TestCase
{
    public function test_build_installer()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $permissions = BuildCustomerPermissions::build($user);
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

        $this->assertEquals($permissions, $shouldBe);
    }

    public function test_build_admin()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $permissions = BuildCustomerPermissions::build($user);
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

        $this->assertEquals($permissions, $shouldBe);
    }

    public function test_build_tech()
    {
        $user = User::factory()->create(['role_id' => 4]);

        $permissions = BuildCustomerPermissions::build($user);
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

        $this->assertEquals($permissions, $shouldBe);
    }
}
