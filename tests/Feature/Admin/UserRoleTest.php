<?php

namespace Tests\Feature\admin;

use Tests\TestCase;
use App\UserRoleType;
use App\UserRolePermissions;
use App\UserRolePermissionTypes;

class UserRoleTest extends TestCase
{
    protected $role, $permissions;

    public function setUp(): void
    {
        Parent::setUp();

        //  Setup a custom role to edit later
        $this->role        = factory(UserRoleType::class)->create();
        $this->permissions = UserRolePermissionTypes::all();

        //  add some permissions to the role
        foreach($this->permissions as $perm)
        {
            UserRolePermissions::create([
                'role_id'      => $this->role->role_id,
                'perm_type_id' => $perm->perm_type_id,
            ]);
        }
    }

    public function test_visit_role_form_page_as_guest()
    {
        $response = $this->get(route('admin.roleSettings'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_visit_role_form_page_as_user_no_permissions()
    {
        $response = $this->actingAs($this->getTech())->get(route('admin.roleSettings'));

        $response->assertStatus(403);
    }

    public function test_visit_role_form_page_as_user_with_permissions()
    {
        $response = $this->actingAs($this->userWithPermission('Manage User Roles'))->get(route('admin.roleSettings'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.roleSettings');
    }

    public function test_visit_role_form_page_as_installer()
    {
        $response = $this->actingAs($this->getInstaller())->get(route('admin.roleSettings'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.roleSettings');
    }

    public function test_submit_new_role_form_as_guest()
    {
        $data = [
            'name'        => 'New Role Name',
            'description' => 'Random Description Name',
            'permissions'  => [],
        ];
        foreach ($this->permissions as $perm) {
            $data['permissions'][] = [
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => 0,
            ];
        }
        $response = $this->post(route('admin.roleSettings'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_submit_new_role_form_as_user_no_permissions()
    {
        $data = [
            'name'        => 'New Role Name',
            'description' => 'Random Description Name',
            'permissions'  => [],
        ];
        foreach ($this->permissions as $perm) {
            $data['permissions'][] = [
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => 0,
            ];
        }
        $response = $this->actingAs($this->getTech())->post(route('admin.roleSettings'), $data);

        $response->assertStatus(403);
    }

    public function test_submit_new_role_form_as_user_with_permissions()
    {
        $data = [
            'name'        => 'New Role Name',
            'description' => 'Random Description Name',
            'permissions'  => [],
        ];
        foreach ($this->permissions as $perm) {
            $data['permissions'][] = [
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => 0,
            ];
        }
        $response = $this->actingAs($this->userWithPermission('Manage User Roles'))->post(route('admin.roleSettings'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_submit_new_role_form_as_installer()
    {
        $data = [
            'name'        => 'New Role Name',
            'description' => 'Random Description Name',
            'permissions'  => [],
        ];
        foreach($this->permissions as $perm)
        {
            $data['permissions'][] = [
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => 0,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->post(route('admin.roleSettings'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_edit_existing_role()
    {
        $data = [
            'role_id'     => $this->role->role_id,
            'name'        => 'New Role Name',
            'description' => 'Random Description Name',
            'permissions'  => [],
        ];
        foreach ($this->permissions as $perm) {
            $data['permissions'][] = [
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => 0,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->post(route('admin.roleSettings'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    public function test_edit_default_role()
    {
        $data = [
            'role_id'     => 1,
            'name'        => 'New Role Name',
            'description' => 'Random Description Name',
            'permissions'  => [],
        ];
        foreach ($this->permissions as $perm) {
            $data['permissions'][] = [
                'perm_type_id' => $perm->perm_type_id,
                'allow'        => 0,
            ];
        }
        $response = $this->actingAs($this->getInstaller())->post(route('admin.roleSettings'), $data);

        $response->assertSuccessful();
        $response->assertJson(['success' => false]);
    }
}
