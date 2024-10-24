<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Tests\TestCase;

class PasswordPolicyTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.user.password-policy.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.password-policy.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user.password-policy.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'expire' => '60',
            'min_length' => '12',
            'contains_uppercase' => 'false',
            'contains_lowercase' => 'false',
            'contains_number' => 'false',
            'contains_special' => 'false',
            'disable_compromised' => 'false',
        ];

        $response = $this->put(route('admin.user.password-policy.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'expire' => '60',
            'min_length' => '12',
            'contains_uppercase' => 'false',
            'contains_lowercase' => 'false',
            'contains_number' => 'false',
            'contains_special' => 'false',
            'disable_compromised' => 'false',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.password-policy.update'), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'expire' => '60',
            'min_length' => '12',
            'contains_uppercase' => false,
            'contains_lowercase' => false,
            'contains_number' => false,
            'contains_special' => false,
            'disable_compromised' => 'false',
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.password-policy.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.password_policy'));
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.expire',
            'value' => $data['expire'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.min_length',
            'value' => $data['min_length'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.contains_uppercase',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.contains_lowercase',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.contains_number',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.contains_special',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.disable_compromised',
        ]);
    }
}
