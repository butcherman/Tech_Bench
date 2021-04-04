<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRolesTest extends TestCase
{
    protected $user;
    protected $admin;

    public function setup():void
    {
        Parent::setup();

        $this->user  = User::factory()->create();
        $this->admin = User::factory()->create(['role_id' => 2]);
    }

    /*
    *   Index function
    */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.user-roles.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user-roles.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.user-roles.index'));
        $response->assertSuccessful();
    }

    /*
    *   Create Function
    */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.user-roles.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_create_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user-roles.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.user-roles.create'));
        $response->assertSuccessful();
    }

    /*
    *   Store function
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
    }

    public function test_store_user()
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

        $response = $this->actingAs($this->user)->post(route('admin.user-roles.store'), $form);
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

        $response = $this->actingAs($this->admin)->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(302);
        $this->assertDatabaseHas('user_roles', ['name' => $form['name'], 'description' => $form['description']]);
    }

    /*
    *   Edit function
    */
    public function test_edit_guest()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_edit_user()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertSuccessful();
    }

    /*
    *   Update function
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

        $response = $this->actingAs($this->user)->put(route('admin.user-roles.update', $testRole->role_id), $form);
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

        $response = $this->actingAs($this->admin)->put(route('admin.user-roles.update', $testRole->role_id), $form);
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

        $response = $this->actingAs($this->admin)->put(route('admin.user-roles.update', 1), $form);
        $response->assertStatus(403);
    }

    /*
    *   Destroy function
    */
    public function test_destroy_guest()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_user()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->actingAs($this->user)->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(403);
    }

    public function test_destroy_default()
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.user-roles.destroy', 1));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'You cannot delete one of the default User Roles']);
    }

    public function test_destroy_in_use()
    {
        $testRole = UserRoles::factory()->create();
        User::factory()->create(['role_id' => $testRole->role_id]);
        $response = $this->actingAs($this->admin)->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'This User Role is in use.  Please remove all users from this role before deleting']);
    }

    public function test_destroy()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->actingAs($this->admin)->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        // $response->assertSessionHas(['message' => ])
    }
}
