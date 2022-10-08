<?php

namespace Tests\Unit\Actions;

use App\Actions\GetUserRoles;
use App\Models\User;
use Tests\TestCase;

class GetUserRolesTest extends TestCase
{
    public function test_run_as_installer()
    {
        $user     = User::factory()->create(['role_id' => 1]);
        $roleList = (new GetUserRoles)->run($user);

        $this->assertEquals($roleList->toArray(), [
            [
                'role_id'     => 1,
                'name'        => 'Installer',
                'description' => 'All Access Administrator',
            ],
            [
                'role_id'     => 2,
                'name'        => 'Administrator',
                'description' => 'System Administrator',
            ],
            [
                'role_id'     => 3,
                'name'        => 'Reports',
                'description' => 'User who can run reports',
            ],
            [
                'role_id'     => 4,
                'name'        => 'Tech',
                'description' => 'Standard User',
            ],
        ]);
    }

    public function test_run_as_admin()
    {
        $user     = User::factory()->create(['role_id' => 2]);
        $roleList = (new GetUserRoles)->run($user);

        $this->assertEquals($roleList->toArray(), [
            [
                'role_id'     => 2,
                'name'        => 'Administrator',
                'description' => 'System Administrator',
            ],
            [
                'role_id'     => 3,
                'name'        => 'Reports',
                'description' => 'User who can run reports',
            ],
            [
                'role_id'     => 4,
                'name'        => 'Tech',
                'description' => 'Standard User',
            ],
        ]);
    }

    public function test_run_default()
    {
        $user     = User::factory()->create(['role_id' => 3]);
        $roleList = (new GetUserRoles)->run($user);

        $this->assertEquals($roleList->toArray(), [
            [
                'role_id'     => 3,
                'name'        => 'Reports',
                'description' => 'User who can run reports',
            ],
            [
                'role_id'     => 4,
                'name'        => 'Tech',
                'description' => 'Standard User',
            ],
        ]);
    }
}
