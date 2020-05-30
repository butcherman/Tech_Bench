<?php

namespace Tests\Unit\Roles;

use App\Domains\Roles\SetPermissions;
use App\Http\Requests\Admin\NewRoleRequest;
use App\UserRoleType;
use Tests\TestCase;

class SetPermissionsTest extends TestCase
{
    public function test_submit_role_new_role()
    {
        $role = factory(UserRoleType::class)->make();

        $data = new NewRoleRequest([
            'role_id'     => null,
            'allow_edit'  => true,
            'name'        => $role->name,
            'description' => $role->description,
            'user_role_permissions' => [
                ['perm_type_id' => 1,  'allow' => 1],
                ['perm_type_id' => 2,  'allow' => 1],
                ['perm_type_id' => 3,  'allow' => 1],
                ['perm_type_id' => 4,  'allow' => 1],
                ['perm_type_id' => 5,  'allow' => 1],
                ['perm_type_id' => 6,  'allow' => 1],
                ['perm_type_id' => 7,  'allow' => 1],
                ['perm_type_id' => 8,  'allow' => 1],
                ['perm_type_id' => 9,  'allow' => 1],
                ['perm_type_id' => 10, 'allow' => 1],
                ['perm_type_id' => 11, 'allow' => 1],
            ],
        ]);

        $res = (new SetPermissions)->submitRole($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('user_role_types', ['name' => $role->name, 'description' => $role->description]);
    }

    public function test_submit_role_edit_role()
    {
        $existingRole = factory(UserRoleType::class)->create();
        $role = factory(UserRoleType::class)->make();

        $data = new NewRoleRequest([
            'role_id'     => $existingRole->role_id,
            'allow_edit'  => true,
            'name'        => $role->name,
            'description' => $role->description,
            'user_role_permissions' => [
                ['perm_type_id' => 1,  'allow' => 1],
                ['perm_type_id' => 2,  'allow' => 1],
                ['perm_type_id' => 3,  'allow' => 1],
                ['perm_type_id' => 4,  'allow' => 1],
                ['perm_type_id' => 5,  'allow' => 1],
                ['perm_type_id' => 6,  'allow' => 1],
                ['perm_type_id' => 7,  'allow' => 1],
                ['perm_type_id' => 8,  'allow' => 1],
                ['perm_type_id' => 9,  'allow' => 1],
                ['perm_type_id' => 10, 'allow' => 1],
                ['perm_type_id' => 11, 'allow' => 1],
            ],
        ]);

        $res = (new SetPermissions)->submitRole($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('user_role_types', ['role_id' => $existingRole->role_id, 'name' => $role->name, 'description' => $role->description]);
    }
}
