<?php

namespace Tests\Unit\Actions;

use App\Actions\AvailableUserRoles;
use App\Models\User;
use App\Models\UserRole;
use Tests\TestCase;

class AvailableUserRolesUnitTest extends TestCase
{
    /**
     * Build User Role list as different users to ensure that we cannot access
     * the Installer Role if we are not an installer
     * Note:  Only default Roles tested
     */
    public function test_installer_role()
    {
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $obj = new AvailableUserRoles;

        $shouldBe = UserRole::all()->append('href')->toArray();
        $testData = $obj->get($user)->toArray();

        $this->assertEquals($shouldBe, $testData);
    }

    public function test_administrator_role()
    {
        $user = User::factory()->createQuietly(['role_id' => 2]);
        $obj = new AvailableUserRoles;

        $shouldBe = UserRole::where('role_id', '>=', 2)
            ->get()
            ->append('href')
            ->toArray();
        $testData = $obj->get($user)->toArray();

        $this->assertEquals($shouldBe, array_values($testData));
    }

    public function test_other_role()
    {
        $user = User::factory()->createQuietly(['role_id' => 3]);
        $obj = new AvailableUserRoles;

        $shouldBe = UserRole::where('role_id', '>=', 2)
            ->get()
            ->append('href')
            ->toArray();
        $testData = $obj->get($user)->toArray();

        $this->assertEquals($shouldBe, array_values($testData));
    }
}
