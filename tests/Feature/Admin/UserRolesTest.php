<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRolesTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.user-roles.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.user-roles.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user-roles.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.user-roles.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.user-roles.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user-roles.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $form = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['perm_type_id' => 1,  'allow' => false],
                ['perm_type_id' => 2,  'allow' => false],
                ['perm_type_id' => 3,  'allow' => false],
                ['perm_type_id' => 4,  'allow' => false],
                ['perm_type_id' => 5,  'allow' => false],
                ['perm_type_id' => 6,  'allow' => true],
                ['perm_type_id' => 7,  'allow' => true],
                ['perm_type_id' => 8,  'allow' => false],
                ['perm_type_id' => 9,  'allow' => false],
                ['perm_type_id' => 10, 'allow' => true],
                ['perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $form = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['perm_type_id' => 1,  'allow' => false],
                ['perm_type_id' => 2,  'allow' => false],
                ['perm_type_id' => 3,  'allow' => false],
                ['perm_type_id' => 4,  'allow' => false],
                ['perm_type_id' => 5,  'allow' => false],
                ['perm_type_id' => 6,  'allow' => true],
                ['perm_type_id' => 7,  'allow' => true],
                ['perm_type_id' => 8,  'allow' => false],
                ['perm_type_id' => 9,  'allow' => false],
                ['perm_type_id' => 10, 'allow' => true],
                ['perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $form = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['perm_type_id' => 1,  'allow' => false],
                ['perm_type_id' => 2,  'allow' => false],
                ['perm_type_id' => 3,  'allow' => false],
                ['perm_type_id' => 4,  'allow' => false],
                ['perm_type_id' => 5,  'allow' => false],
                ['perm_type_id' => 6,  'allow' => true],
                ['perm_type_id' => 7,  'allow' => true],
                ['perm_type_id' => 8,  'allow' => false],
                ['perm_type_id' => 9,  'allow' => false],
                ['perm_type_id' => 10, 'allow' => true],
                ['perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(302);
        $this->assertDatabaseHas('user_roles', ['name' => $form['name'], 'description' => $form['description']]);
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_user()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $testRole = UserRoles::factory()->create();
        $form     = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['role_id' => $testRole->role_id, 'perm_type_id' => 1,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 2,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 3,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 4,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 5,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 6,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 7,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 8,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 9,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 10, 'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->put(route('admin.user-roles.update', $testRole->role_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_user()
    {
        $testRole = UserRoles::factory()->create();
        $form     = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['role_id' => $testRole->role_id, 'perm_type_id' => 1,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 2,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 3,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 4,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 5,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 6,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 7,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 8,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 9,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 10, 'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.user-roles.update', $testRole->role_id), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $testRole = UserRoles::factory()->create();
        $form     = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['role_id' => $testRole->role_id, 'perm_type_id' => 1,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 2,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 3,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 4,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 5,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 6,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 7,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 8,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 9,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 10, 'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.user-roles.update', $testRole->role_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Role Updated']);
        $this->assertDatabaseHas('user_roles', ['role_id' => $testRole->role_id, 'name' => $form['name'], 'description' => $form['description']]);
    }

    public function test_update_default_role()
    {
        $testRole = UserRoles::factory()->create();
        $form     = [
            'name'        => 'New Role',
            'description' => 'This is for testing purposes only',
            'user_role_permissions' => [
                ['role_id' => $testRole->role_id, 'perm_type_id' => 1,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 2,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 3,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 4,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 5,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 6,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 7,  'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 8,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 9,  'allow' => false],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 10, 'allow' => true],
                ['role_id' => $testRole->role_id, 'perm_type_id' => 11, 'allow' => false],
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.user-roles.update', 1), $form);
        $response->assertStatus(403);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_user()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->actingAs(User::factory()->create())->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(403);
    }

    public function test_destroy_default()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.user-roles.destroy', 1));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'You cannot delete one of the default User Roles']);
    }

    public function test_destroy_in_use()
    {
        $testRole = UserRoles::factory()->create();
        User::factory()->create(['role_id' => $testRole->role_id]);
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'This User Role is in use.  Please remove all users from this role before deleting']);
    }

    public function test_destroy()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
    }
}
