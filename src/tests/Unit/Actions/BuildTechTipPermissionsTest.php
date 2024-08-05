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
        $this->actingAs($user);

        $permissions = BuildTechTipPermissions::build($user);
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
        $user = User::factory()->create(['role_id' => 2]);
        $this->actingAs($user);

        $permissions = BuildTechTipPermissions::build($user);
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
        $user = User::factory()->create(['role_id' => 4]);
        $this->actingAs($user);

        $permissions = BuildTechTipPermissions::build($user);
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
