<?php

namespace Tests\Unit\Actions;

use App\Actions\CustomerPermissions;
use App\Models\User;
use Tests\TestCase;

class CustomerPermissionsUnitTest extends TestCase
{
    public function test_build_installer()
    {
        $perm = new CustomerPermissions;
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $permissions = $perm->get($user);
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
        $perm = new CustomerPermissions;
        $user = User::factory()->createQuietly(['role_id' => 2]);

        $permissions = $perm->get($user);
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
        $perm = new CustomerPermissions;
        $user = User::factory()->createQuietly(['role_id' => 4]);

        $permissions = $perm->get($user);
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
