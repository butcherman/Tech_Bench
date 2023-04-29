<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class UpdateAccountTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email', 'role_id']);

        $response = $this->post(route('settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

        $response = $this->actingAs($user)->post(route('settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.account_updated'));
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    public function test_invoke_another_user_as_admin()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('settings.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.account_updated'));
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    public function test_invoke_another_user()
    {
        $user = User::factory()->create();
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create())->post(route('settings.update', $user->username), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
    }

    public function test_invoke_higher_user()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = User::factory()->make()->only(['username', 'first_name', 'last_name', 'email']);

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->post(route('settings.update', $user->username), $data);
        $response->assertStatus(403);
    }
}
