<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('admin.users.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->post(route('admin.users.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs(User::factory()->create())->post(route('admin.users.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        Notification::fake();

        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.store'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.created', ['user' => $data['first_name'].' '.$data['last_name']]));
        $this->assertDatabaseHas('users', $data);

        //  Assert settings model created
        $newUser = User::where('username', $data['username'])->first();
        Notification::assertSentTo($newUser, SendWelcomeEmail::class);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $newUser->user_id,
            'setting_type_id' => 1,
            'value' => 1,
        ]);
        $this->assertDatabaseHas('user_settings', [
            'user_id' => $newUser->user_id,
            'setting_type_id' => 2,
            'value' => 1,
        ]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.show', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.show', $user->username));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.show', $user->username));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.edit', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.edit', $user->username));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.edit', $user->username));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->put(route('admin.users.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $user = User::factory()->create();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.users.update', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_update_lower_role()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->put(route('admin.users.update', $user->username), $data);
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', $user->only(['user_id', 'username', 'first_name', 'last_name', 'email', 'role_id']));
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'username' => 'newUserName',
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'bbob@noem.com',
            'role_id' => 3,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.users.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.updated', ['user' => $data['first_name'].' '.$data['last_name']]));
        $this->assertDatabaseHas('users', $data);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create())->delete(route('admin.users.destroy', $user->username));
        $response->assertStatus(403);
    }

    public function test_destroy_higher_role()
    {
        $user = User::factory()->create(['role_id' => 1]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->delete(route('admin.users.destroy', $user->username));
        $response->assertStatus(302);
        $this->assertSoftDeleted('users', $user->only(['user_id', 'username']));
    }

    public function test_destroy()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->delete(route('admin.users.destroy', $user->username));
        $response->assertStatus(302);
        $response->assertSessionHas('warning', __('admin.user.disabled', ['user' => $user->full_name]));
        $this->assertSoftDeleted('users', $user->only(['user_id', 'username']));
    }

    /**
     * Restore Method
     */
    public function test_restore_guest()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->get(route('admin.users.restore', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_restore_no_permission()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.restore', $user->username));
        $response->assertStatus(403);
    }

    public function test_restore()
    {
        $user = User::factory()->create();
        $user->delete();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.restore', $user->username));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.restored', ['user' => $user->full_name]));
        $this->assertDatabaseHas('users', $user->only(['user_id', 'username', 'first_name', 'last_name']));
    }
}