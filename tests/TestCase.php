<?php

namespace Tests;

use App\User;
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

        return $user;
    }

    //  Act as a registered Admin user
    public function getAdministrator()
    {
        $user = factory(User::class)->create(
            [
                'role_id' => 2
            ]
        );
        return $user;
    }

    //  Act as a standard registered Tech user
    public function getTech()
    {
        $user = factory(User::class)->create();
        return $user;
    }

    //  Create a user missing a specific permission
    public function getUserWithoutPermission($permission)
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
    public function getUserWithPermission($permission)
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
