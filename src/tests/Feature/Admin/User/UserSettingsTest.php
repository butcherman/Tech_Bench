<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('admin.user.user-settings.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.user-settings.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.user.user-settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
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
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
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
        $response->assertStatus(403);
    }

    public function test_update()
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
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.settings_updated'));

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

    public function test_update_no_password_change()
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
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.settings_updated'));

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
