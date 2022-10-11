<?php

namespace Tests\Feature\User;

use Tests\TestCase;

use App\Models\User;

class ChangePasswordTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('password.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('password.index'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'current_password'      => 'password',
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->post(route('password.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store()
    {
        $user = User::factory()->create();
        $data = [
            'current_password'      => 'password',
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->actingAs($user)->post(route('password.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', __('user.password_changed'));
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $data = [
            'username'              => $user->username,
            'full_name'             => $user->full_name,
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->put(route('password.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $user = User::factory()->create();
        $data = [
            'username'              => $user->username,
            'full_name'             => $user->full_name,
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('password.update', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'username'              => $user->username,
            'full_name'             => $user->full_name,
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('password.update', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.password_updated'));
    }
}
