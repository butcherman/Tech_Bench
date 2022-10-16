<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.user.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.user.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.user.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.user.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->post(route('admin.user.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs(User::factory()->create())->post(route('admin.user.store'), $data);
        $response->assertStatus(403);
    }

    // public function test_store()
    // {
    //     $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.user.store'), $data);
    //     $response->assertStatus(302);
    //     $response->assertSessionHas([
    //         'message' => 'New User Created',
    //         'type'    => 'success',
    //     ]);
    //     $this->assertDatabaseHas('users', $data);
    // }

    /**
     * Edit Method
     */
    // public function test_edit_guest()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->get(route('admin.user.edit', $user->username));
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login.index'));
    //     $this->assertGuest();
    // }

    // public function test_edit_no_permission()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs(User::factory()->create())->get(route('admin.user.edit', $user->username));
    //     $response->assertStatus(403);
    // }

    // public function test_edit()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user.edit', $user->username));
    //     $response->assertSuccessful();
    // }

    /**
     * Update Method
     */
    // public function test_update_guest()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'username'   => 'newUserName',
    //         'first_name' => 'Billy',
    //         'last_name'  => 'Bob',
    //         'email'      => 'bbob@noem.com',
    //         'role_id'    => 3,
    //     ];

    //     $response = $this->put(route('admin.user.update', $user->username), $data);
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('login.index'));
    //     $this->assertGuest();
    // }

    // public function test_update_no_permission()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'username'   => 'newUserName',
    //         'first_name' => 'Billy',
    //         'last_name'  => 'Bob',
    //         'email'      => 'bbob@noem.com',
    //         'role_id'    => 3,
    //     ];

    //     $response = $this->actingAs(User::factory()->create())->put(route('admin.user.update', $user->username), $data);
    //     $response->assertStatus(403);
    // }

    // public function test_update_lower_role()
    // {
    //     $user = User::factory()->create(['role_id' => 1]);
    //     $data = [
    //         'username'   => 'newUserName',
    //         'first_name' => 'Billy',
    //         'last_name'  => 'Bob',
    //         'email'      => 'bbob@noem.com',
    //         'role_id'    => 3,
    //     ];

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->put(route('admin.user.update', $user->username), $data);
    //     $response->assertStatus(403);
    //     $this->assertDatabaseHas('users', $user->only(['user_id', 'username', 'first_name', 'last_name', 'email', 'role_id']));
    // }

    // public function test_update()
    // {
    //     $user = User::factory()->create();
    //     $data = [
    //         'username'   => 'newUserName',
    //         'first_name' => 'Billy',
    //         'last_name'  => 'Bob',
    //         'email'      => 'bbob@noem.com',
    //         'role_id'    => 3,
    //     ];

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.user.update', $user->username), $data);
    //     $response->assertStatus(302);
    //     $this->assertDatabaseHas('users', $data);
    // }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('admin.user.destroy', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    // public function test_destroy_no_permission()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs(User::factory()->create())->delete(route('admin.user.destroy', $user->username));
    //     $response->assertStatus(403);
    // }

    // public function test_destroy_higher_role()
    // {
    //     $user = User::factory()->create(['role_id' => 1]);

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->delete(route('admin.user.destroy', $user->username));
    //     $response->assertStatus(403);
    // }

    // public function test_destroy()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.user.destroy', $user->username));
    //     $response->assertStatus(302);
    //     $this->assertSoftDeleted('users', $user->only(['user_id', 'username']));
    // }
}
