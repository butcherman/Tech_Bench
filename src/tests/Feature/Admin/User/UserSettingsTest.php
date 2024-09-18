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
        $response = $this->actingAs(User::factory()->create())
            ->get(route('admin.user.user-settings.show'));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->get(route('admin.user.user-settings.show'));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'auto_logout_timer' => 5,
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
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
        $data = [
            'auto_logout_timer' => 5,
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())
            ->put(route('admin.user.user-settings.update'), $data);
        $response->assertStatus(403);
    }

    public function test_update()
    {
        $data = [
            'auto_logout_timer' => 5,
            'twoFa' => [
                'required' => true,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))
            ->put(route('admin.user.user-settings.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.auto_logout_timer',
            'value' => 5,
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.required',
            // 'value' => $data['twoFa']['required'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.allow_save_device',
            // 'value' => $data['twoFa']['allow_save_device'],
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_login',
            // 'value' => $data['oath']['allow_login'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_register',
            // 'value' => $data['oath']['allow_register'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_bypass_2fa',
            // 'value' => $data['oath']['allow_bypass_2fa'],
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
        $data = [
            'auto_logout_timer' => 5,
            'twoFa' => [
                'required' => true,
                'allow_save_device' => false,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'allow_bypass_2fa' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => __('admin.fake-password'),
                'secret_expires' => '2099-01-01',
                'redirect' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('admin.user.user-settings.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.settings_updated'));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.auto_logout_timer',
            'value' => 5,
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.required',
            // 'value' => $data['twoFa']['required'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.twoFa.allow_save_device',
            // 'value' => $data['twoFa']['allow_save_device'],
        ]);

        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_login',
            // 'value' => $data['oath']['allow_login'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_register',
            // 'value' => $data['oath']['allow_register'],
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'services.azure.allow_bypass_2fa',
            // 'value' => $data['oath']['allow_bypass_2fa'],
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
