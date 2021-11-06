<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetPasswordPolicyTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'password_expires' => 90,
        ];

        $response = $this->put(route('admin.set-password-policy'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $data = [
            'password_expires' => 90,
        ];

        $response = $this->actingAs(User::factory()->create())->put(route('admin.set-password-policy'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        $data = [
            'password_expires' => 90,
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.set-password-policy'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas([
            'message' => 'Password Policy Updated',
            'type'    => 'success',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key'   => 'auth.passwords.settings.expire',
            'value' => '90',
        ]);
    }
}
