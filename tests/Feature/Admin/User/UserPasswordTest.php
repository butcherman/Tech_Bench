<?php

namespace Tests\Feature\Admin\User;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserPasswordTest extends TestCase
{
    protected $password = 'ChangeMe$secure1';

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.reset-password.edit', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.reset-password.edit', $user->username));
        $response->assertStatus(403);
    }

    public function test_edit()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.reset-password.edit', $user->username));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $data = [
            'password'              => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('admin.users.reset-password.edit', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $user = User::factory()->create();
        $data = [
            'password'              => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.users.reset-password.edit', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'password'              => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.users.reset-password.edit', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.password_updated'));
        $this->assertTrue(Hash::check($data['password'], User::find($user->user_id)->password));
    }
}