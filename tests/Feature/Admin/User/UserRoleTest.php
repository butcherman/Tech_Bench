<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use App\Models\UserRoles;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use WithFaker;

    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.user-roles.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
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
        $response->assertRedirect(route('login'));
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
     * Copy Method
     */
    public function test_copy_guest()
    {
        $role = UserRoles::factory()->create();

        $response = $this->get(route('admin.user-roles.copy', $role->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_copy_no_permission()
    {
        $role = UserRoles::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.user-roles.copy', $role->role_id));
        $response->assertStatus(403);
    }

    public function test_copy()
    {
        $role = UserRoles::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user-roles.copy', $role->role_id));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
        ];

        $response = $this->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.user-roles.store'), $form);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user-roles.created'));
        $this->assertDatabaseHas('user_roles', ['name' => $form['name'], 'description' => $form['description']]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $role = UserRoles::factory()->create();

        $response = $this->get(route('admin.user-roles.show', $role->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $role = UserRoles::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.user-roles.show', $role->role_id));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $role = UserRoles::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user-roles.show', $role->role_id));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $testRole = UserRoles::factory()->create();

        $response = $this->get(route('admin.user-roles.edit', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
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
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
        ];

        $response = $this->put(route('admin.user-roles.update', $testRole->role_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_user()
    {
        $testRole = UserRoles::factory()->create();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.user-roles.update', $testRole->role_id), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $testRole = UserRoles::factory()->create();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.user-roles.update', $testRole->role_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user-roles.updated'));
        $this->assertDatabaseHas('user_roles', ['role_id' => $testRole->role_id, 'name' => $form['name'], 'description' => $form['description']]);
    }

    public function test_update_default_role()
    {
        $testRole = UserRoles::factory()->create();
        $form = [
            'name' => 'New Role',
            'description' => 'This is for testing purposes only',
            'type-1' => $this->faker->boolean(),
            'type-2' => $this->faker->boolean(),
            'type-3' => $this->faker->boolean(),
            'type-4' => $this->faker->boolean(),
            'type-5' => $this->faker->boolean(),
            'type-6' => $this->faker->boolean(),
            'type-7' => $this->faker->boolean(),
            'type-8' => $this->faker->boolean(),
            'type-9' => $this->faker->boolean(),
            'type-10' => $this->faker->boolean(),
            'type-11' => $this->faker->boolean(),
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
        $response->assertRedirect(route('login'));
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
        $response->assertStatus(403);
    }

    public function test_destroy_in_use()
    {
        $testRole = UserRoles::factory()->create();
        User::factory()->create(['role_id' => $testRole->role_id]);
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['in-use' => __('admin.user-roles.in-use')]);
    }

    public function test_destroy()
    {
        $testRole = UserRoles::factory()->create();
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.user-roles.destroy', $testRole->role_id));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('admin.user-roles.destroyed'));
    }
}
