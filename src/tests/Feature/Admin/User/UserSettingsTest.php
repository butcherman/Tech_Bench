<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    /**
     * Edit Method
     */
    public function test_show_guest(): void
    {
        $response = $this->get(route('admin.user.user-settings.edit'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.user-settings.edit'));
        $response->assertForbidden();
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user.user-settings.edit'));
        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/User/UserSettings')
                ->has('auto-logout-timer')
                ->has('two-fa')
                ->has('oath')
                ->has('role-list')
            );
    }

    /**
     * Update Method
     */
    public function test_update_guest(): void
    {
        $data = [
            'auto_logout_timer' => '5',
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'default_role_id' => '3',
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->put(route('admin.user.user-settings.update'), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'auto_logout_timer' => '5',
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'default_role_id' => '3',
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.user-settings.update'), $data);

        $response->assertForbidden();
    }

    public function test_update(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'auto_logout_timer' => '5',
            'twoFa' => [
                'required' => true,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'default_role_id' => '3',
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.user-settings.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.auto_logout_timer',
            'value' => '5',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.required',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.allow_save_device',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_login',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_register',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.default_role_id',
            'value' => '3',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_bypass_2fa',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.tenant',
            'value' => $data['oath']['tenant'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.client_id',
            'value' => $data['oath']['client_id'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.client_secret',
            'value' => $data['oath']['client_secret'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.secret_expires',
            'value' => $data['oath']['secret_expires'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.redirect',
            'value' => $data['oath']['redirect'],
        ]);
    }

    public function test_update_no_password_change(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'auto_logout_timer' => '5',
            'twoFa' => [
                'required' => true,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'default_role_id' => '3',
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => __('admin.fake-password'),
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs($user)
            ->put(route('admin.user.user-settings.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.auto_logout_timer',
            'value' => '5',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.required',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.allow_save_device',
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_login',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_register',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.default_role_id',
            'value' => '3',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_bypass_2fa',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.tenant',
            'value' => $data['oath']['tenant'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.client_id',
            'value' => $data['oath']['client_id'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.secret_expires',
            'value' => $data['oath']['secret_expires'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.redirect',
            'value' => $data['oath']['redirect'],
        ]);

        $this->assertDatabaseMissing('app_settings', [
            'key' => 'services.azure.client_secret',
            'value' => $data['oath']['client_secret'],
        ]);
    }
}
