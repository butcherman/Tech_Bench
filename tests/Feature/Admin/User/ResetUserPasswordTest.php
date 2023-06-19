<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetUserPasswordTest extends TestCase
{
    protected $password = 'ChangeMe$secure1';

    /**
     * Invoke Method
     */
    public function test_update_guest()
    {
        $user = User::factory()->create();
        $data = [
            'changeRequired' => true,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->post(route('admin.users.reset-password', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        $user = User::factory()->create();
        $data = [
            'changeRequired' => true,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.users.reset-password', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_update_higher_role()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'changeRequired' => true,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->post(route('admin.users.reset-password', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $data = [
            'changeRequired' => true,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.reset-password', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.password_reset', ['user' => $user->full_name]));
        $this->assertTrue(Hash::check($data['password'], User::find($user->user_id)->password));
    }
}
