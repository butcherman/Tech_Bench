<?php

namespace Tests\Unit\Actions;

use App\Actions\TechTipPermissions;
use App\Models\User;
use Tests\TestCase;

class TechTipPermissionsUnitTest extends TestCase
{
    public function test_build_installer()
    {
        $perm = new TechTipPermissions;
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $permissions = $perm->get($user);
        $shouldBe = [
            'manage' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
            'comment' => true,
            'public' => false,
        ];

        $this->assertEquals($permissions, $shouldBe);
    }

    public function test_build_admin()
    {
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $perm = new TechTipPermissions;

        $permissions = $perm->get($user);
        $shouldBe = [
            'manage' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
            'comment' => true,
            'public' => false,
        ];

        $this->assertEquals($permissions, $shouldBe);
    }

    public function test_build_tech()
    {
        $user = User::factory()->createQuietly(['role_id' => 4]);
        $perm = new TechTipPermissions;

        $permissions = $perm->get($user);
        $shouldBe = [
            'manage' => false,
            'create' => true,
            'update' => false,
            'delete' => false,
            'comment' => true,
            'public' => false,
        ];

        $this->assertEquals($permissions, $shouldBe);
    }
}
