<?php

namespace Tests;

use App\User;
use App\UserSettings;
use App\UserRoleType;
use App\UserRolePermissions;
use App\UserRolePermissionTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;

    //  Act as a registered Installer user
    public function getInstaller()
    {
        $user = factory(User::class)->create(
            [
                'role_id' => 1
            ]
        );
        UserSettings::create([
            'user_id' => $user->user_id,
        ]);

        return $user;
    }

    //  Act as a standard registered Tech user
    public function getTech()
    {
        $user = factory(User::class)->create();
        UserSettings::create([
            'user_id' => $user->user_id,
        ]);

        return $user;
    }

    //  Create a user missing a specific permission
    public function userWithoutPermission($permission)
    {
        $role = factory(UserRoleType::class)->create();
        $permTypes = UserRolePermissionTypes::all();

        foreach($permTypes as $perm)
        {
            UserRolePermissions::create([
                'role_id'      => $role->role_id,
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => $perm->description === $permission ? 0 : 1,
            ]);
        }

        return factory(User::class)->create([
            'role_id' => $role->role_id,
        ]);
    }

    //  Create a user that has a specific permission
    public function userWithPermission($permission)
    {
        $role = factory(UserRoleType::class)->create();
        $permTypes = UserRolePermissionTypes::all();

        foreach ($permTypes as $perm) {
            $allow = $perm->description == $permission ? 1 : 0;
            UserRolePermissions::create([
                'role_id'      => $role->role_id,
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => $perm->description == $permission ? 1 : 0,
            ]);
        }

        return factory(User::class)->create([
            'role_id' => $role->role_id,
        ]);
    }
}
