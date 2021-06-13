<?php

namespace Tests\Feature\User;

use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
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
    *   Index Method
    */
    public function test_index_guest()
    {
        $response = $this->get(route('settings.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_index()
    {
        $response = $this->from(route('dashboard'))->actingAs($this->user)->get(route('settings.index'));
        $response->assertSuccessful();
    }

    /*
    *   Create Method
    */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.user.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_create_user()
    {
        $response = $this->actingAs($this->user)->get(route('admin.user.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.user.create'));
        $response->assertSuccessful();
    }

    /*
    *   Store Method
    */
    public function test_store_guest()
    {
        $form = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);
        $response = $this->post(route('admin.user.store'), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_store_user()
    {
        $form = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);
        $response = $this->actingAs($this->user)->post(route('admin.user.store'), $form);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $form = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);
        $response = $this->actingAs($this->admin)->post(route('admin.user.store'), $form);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', $form);
    }

    /*
    *   Edit Method
    */
    public function test_edit_guest()
    {
        $user2 = User::factory()->create();
        $response = $this->get(route('admin.user.edit', $user2->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_edit_user()
    {
        $user2 = User::factory()->create();
        $response = $this->actingAs($this->user)->get(route('admin.user.edit', $user2->username));
        $response->assertStatus(403);
    }

    public function test_edit_higher_user()
    {
        $user2 = User::factory()->create(['role_id' => 1]);
        $response = $this->actingAs($this->admin)->get(route('admin.user.edit', $user2->username));
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'You cannot modify a user with higher permissions than you']);
    }

    public function test_edit_invalid_user()
    {
        $user2 = User::factory()->make();
        $response = $this->actingAs($this->admin)->get(route('admin.user.edit', $user2->username));
        $response->assertStatus(404);
    }

    public function test_edit()
    {
        $user2 = User::factory()->create();
        $response = $this->actingAs($this->admin)->get(route('admin.user.edit', $user2->username));
        $response->assertSuccessful();
    }

    /*
    *   Update Method
    */
    public function test_update_guest()
    {
        $user2 = User::factory()->create();
        $form  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->put(route('admin.user.update', $user2->user_id), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_update_user()
    {
        $user2 = User::factory()->create();
        $form  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs($this->user)->put(route('admin.user.update', $user2->user_id), $form);
        $response->assertStatus(403);
    }

    public function test_update_higher_user()
    {
        $user2 = User::factory()->create(['role_id' => 1]);
        $form  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs($this->admin)->put(route('admin.user.update', $user2->user_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'You cannot modify a user with higher permissions than you']);
    }

    public function test_update_invalid_user()
    {
        $form  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs($this->admin)->put(route('admin.user.update', 13), $form);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $user2 = User::factory()->create();
        $form  = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs($this->admin)->put(route('admin.user.update', $user2->user_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Account Details Updated']);
        $this->assertDatabaseHas('users', ['user_id' => $user2->user_id, 'username' => $form['username'], 'first_name' => $form['first_name'], 'last_name' => $form['last_name'], 'email' => $form['email']]);
    }

    public function test_update_yourself()
    {
        $form  = User::factory()->make()->only(['first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs($this->user)->put(route('settings.update', $this->user->user_id), $form);
        $response->assertStatus(302);
        $response->assertSessionHas(['message' => 'Account Details Updated']);
        $this->assertDatabaseHas('users', ['user_id' => $this->user->user_id, 'first_name' => $form['first_name'], 'last_name' => $form['last_name'], 'email' => $form['email']]);
    }

    /*
    *   Destroy Method
    */
    public function test_destroy_guest()
    {
        $user2 = User::factory()->create();

        $response = $this->delete(route('admin.user.destroy', $user2->user_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
    }

    public function test_destroy_user()
    {
        $user2 = User::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.user.destroy', $user2->user_id));
        $response->assertStatus(403);
    }

    public function test_destroy()
    {
        $user2 = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('admin.user.destroy', $user2->username));
        $response->assertStatus(302);
        $this->assertSoftDeleted('users', $user2->only(['user_id', 'first_name', 'last_name', 'email']));
    }
}
