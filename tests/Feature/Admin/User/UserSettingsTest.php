<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    /**
     * Get Method
     */
    public function test_get_guest()
    {
        $response = $this->get(route('admin.user-settings.get'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_get_no_permission()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('admin.user-settings.get'));
        $response->assertStatus(403);
    }

    public function test_get()
    {
        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.user-settings.get'));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_guest()
    {
        $data = [
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
                'allow_via_email' => true,
                'allow_via_sms' => false,
            ],
            'twilio' => [
                'sid' => null,
                'token' => null,
                'from' => null,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'redirectUri' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->post(route('admin.user-settings.set'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_set_no_permission()
    {
        $data = [
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
                'allow_via_email' => true,
                'allow_via_sms' => false,
            ],
            'twilio' => [
                'sid' => null,
                'token' => null,
                'from' => null,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'redirectUri' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.user-settings.set'), $data);
        $response->assertStatus(403);
    }

    public function test_set()
    {
        $data = [
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
                'allow_via_email' => true,
                'allow_via_sms' => false,
            ],
            'twilio' => [
                'sid' => null,
                'token' => null,
                'from' => null,
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => 'someRandomPassword',
                'redirectUri' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.user-settings.set'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.settings_updated'));

        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.required', 'value' => $data['twoFa']['required']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.allow_save_device', 'value' => $data['twoFa']['allow_save_device']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.allow_via_email', 'value' => $data['twoFa']['allow_via_email']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.allow_via_sms', 'value' => $data['twoFa']['allow_via_sms']]);

        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.allow_login', 'value' => $data['oath']['allow_login']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.allow_register', 'value' => $data['oath']['allow_register']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.tenant', 'value' => $data['oath']['tenant']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.client_id', 'value' => $data['oath']['client_id']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.client_secret', 'value' => $data['oath']['client_secret']]);
    }

    public function test_set_no_password_change()
    {
        $data = [
            'twoFa' => [
                'required' => false,
                'allow_save_device' => false,
                'allow_via_email' => true,
                'allow_via_sms' => false,
            ],
            'twilio' => [
                'sid' => 'randomSID',
                'token' => 'thisIsAToken',
                'from' => '+15555551212',
            ],
            'oath' => [
                'allow_login' => true,
                'allow_register' => true,
                'tenant' => 'someRadomUUID',
                'client_id' => 'someRandomID',
                'client_secret' => __('admin.fake-password'),
                'redirectUri' => 'localhost/auth/callback',
            ],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.user-settings.set'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.settings_updated'));

        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.required', 'value' => $data['twoFa']['required']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.allow_save_device', 'value' => $data['twoFa']['allow_save_device']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.allow_via_email', 'value' => $data['twoFa']['allow_via_email']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'auth.twoFa.allow_via_sms', 'value' => $data['twoFa']['allow_via_sms']]);

        $this->assertDatabaseHas('app_settings', ['key' => 'services.twilio.sid', 'value' => $data['twilio']['sid']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.twilio.token', 'value' => $data['twilio']['token']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.twilio.from', 'value' => $data['twilio']['from']]);

        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.allow_login', 'value' => $data['oath']['allow_login']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.allow_register', 'value' => $data['oath']['allow_register']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.tenant', 'value' => $data['oath']['tenant']]);
        $this->assertDatabaseHas('app_settings', ['key' => 'services.azure.client_id', 'value' => $data['oath']['client_id']]);
        $this->assertDatabaseMissing('app_settings', ['key' => 'services.azure.client_secret', 'value' => $data['oath']['client_secret']]);
    }
}
