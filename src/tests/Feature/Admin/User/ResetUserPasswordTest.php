<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Tests\TestCase;

class ResetUserPasswordTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $user = User::factory()->create();

        $response = $this->put(route('admin.user.reset-password', $user->username), [
            'password' => 'test_Password123!',
        ]);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission(): void
    {
        /** @var User */
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->ActingAs($admin)
            ->put(route('admin.user.reset-password', $user->username), [
                'password' => 'test_Password123!',
            ]);

        $response->assertStatus(403);
    }

    public function test_invoke(): void
    {
        /** @var User */
        $admin = User::factory()->create(['role_id' => 1]);
        $user = User::factory()->create();

        $response = $this->ActingAs($admin)
            ->put(route('admin.user.reset-password', $user->username), [
                'password' => 'test_Password123!',
                'password_confirmation' => 'test_Password123!',
            ]);

        $response->assertStatus(302)
            ->assertSessionHas('success', 'Password Updated');
    }
}
