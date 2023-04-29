<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    protected $password = 'ChangeMe$secure1';

    /**
     * Initial Route uses an Inertia Landing method
     */
    public function test_inertia_landing_guest()
    {
        $response = $this->get(route('settings.password.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_inertia_landing()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('settings.password.index'));
        $response->assertSuccessful();
    }

    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'current_password' => 'password',
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->post(route('settings.password.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs($user)->post(route('settings.password.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', __('user.password_changed'));
    }
}
