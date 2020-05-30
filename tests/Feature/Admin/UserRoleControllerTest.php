<?php

namespace Tests\Feature\Admin;

use App\UserRoleType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRoleControllerTest extends TestCase
{
    public function test_permission_settings_guest()
    {
        $response = $this->get(route('admin.user.permissions'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_permission_settings_no_permission()
    {
        $response = $this->actingAs($this->getUserWithoutPermission('Manage Permissions'))->get(route('admin.user.permissions'));

        $response->assertStatus(403);
    }

    public function test_permission_settings()
    {
        $response = $this->actingAs($this->getUserWithPermission('Manage Permissions'))->get(route('admin.user.permissions'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.userPermissions');
    }

    public function test_submit_role_guest()
    {
        $role = factory(UserRoleType::class)->make();
        $data = [
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
        ];

        $response = $this->post(route('admin.user.submit_role'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_role_no_permission()
    {
        $role = factory(UserRoleType::class)->make();
        $data = [
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
        ];

        $response = $this->actingAs($this->getUserWithoutPermission('Manage Permissions'))->post(route('admin.user.submit_role'), $data);
        $response->assertStatus(403);
    }

    public function test_submit_role()
    {
        $role = factory(UserRoleType::class)->make();
        $data = [
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
        ];

        $response = $this->actingAs($this->getUserWithPermission('Manage Permissions'))->post(route('admin.user.submit_role'), $data);
        $response->assertSuccessful();
    }
}
