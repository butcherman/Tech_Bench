<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildTechTipPermissions;
use App\Models\User;
use Tests\TestCase;

class BuildTechTipPermissionsTest extends TestCase
{
    public function test_build_installer()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $permissions = BuildTechTipPermissions::build($user);
        $shouldBe = [
            'manage' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
        ];

        $this->assertEquals($permissions, $shouldBe);
    }

    public function test_build_admin()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $permissions = BuildTechTipPermissions::build($user);
        $shouldBe = [
            'manage' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
        ];

        $this->assertEquals($permissions, $shouldBe);
    }

    public function test_build_tech()
    {
        $user = User::factory()->create(['role_id' => 4]);

        $permissions = BuildTechTipPermissions::build($user);
        $shouldBe = [
            'manage' => false,
            'create' => true,
            'update' => false,
            'delete' => false,
        ];

        $this->assertEquals($permissions, $shouldBe);
    }
}
