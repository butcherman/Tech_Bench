<?php

namespace Tests\Unit\User;

use App\Actions\User\GetUserRoles;
use App\Models\User;
use App\Models\UserRoles;
use Tests\TestCase;

class GetUserRolesTest extends TestCase
{
    /*
    *   Run function
    */
    public function test_roles_installer()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $result = (new GetUserRoles)->run($user);

        $this->assertEquals($result, UserRoles::all());
    }

    public function test_roles_admin()
    {
        $user = User::factory()->create(['role_id' => 2]);
        $result = (new GetUserRoles)->run($user);

        $this->assertEquals($result, UserRoles::where('role_id', '>', 1)->get());
    }

    public function test_roles_other()
    {
        $user = User::factory()->create(['role_id' => 3]);
        $result = (new GetUserRoles)->run($user);

        $this->assertEquals($result, UserRoles::where('role_id', '>', 2)->get());
    }
}
