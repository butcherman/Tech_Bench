<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PasswordPolicyTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_edit_guest(): void
    {
        $response = $this->get(route('admin.user.password-policy.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_edit_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.password-policy.edit'));

        $response->assertForbidden();
    }

    public function test_edit(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user.password-policy.edit'));

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/User/PasswordPolicy')
                ->has('policy')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
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

        $response = $this->put(
            route('admin.user.password-policy.update'),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
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

        $response->assertForbidden();
    }

    public function test_update(): void
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
            'disable_compromised' => true,
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.password-policy.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.password_policy'));

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
