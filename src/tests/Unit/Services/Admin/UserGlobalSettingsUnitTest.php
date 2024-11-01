<?php

namespace Tests\Unit\Services\Admin;

use App\Jobs\User\UpdatePasswordExpireJob;
use App\Services\Admin\UserGlobalSettingsService;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class UserGlobalSettingsUnitTest extends TestCase
{
    public function test_get_password_policy(): void
    {
        $shouldBe = [
            'expire' => config('auth.passwords.settings.expire'),
            'min_length' => config('auth.passwords.settings.min_length'),
            'contains_uppercase' => config('auth.passwords.settings.contains_uppercase'),
            'contains_lowercase' => config('auth.passwords.settings.contains_lowercase'),
            'contains_number' => config('auth.passwords.settings.contains_number'),
            'contains_special' => config('auth.passwords.settings.contains_special'),
            'disable_compromised' => config('auth.passwords.settings.disable_compromised'),
        ];

        $testObj = new UserGlobalSettingsService;
        $data = $testObj->getPasswordPolicy();

        $this->assertEquals($shouldBe, $data);
    }

    public function test_save_password_policy(): void
    {
        Bus::fake();

        $data = [
            'expire' => 60,
            'min_length' => 12,
            'contains_uppercase' => false,
            'contains_lowercase' => false,
            'contains_number' => false,
            'contains_special' => false,
            'disable_compromised' => true,
        ];

        $testObj = new UserGlobalSettingsService;
        $testObj->savePasswordPolicy(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.expire',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.min_length',
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

        Bus::assertDispatched(UpdatePasswordExpireJob::class);
    }

    public function test_save_password_policy_expire_not_changed(): void
    {
        Bus::fake();

        $data = [
            'expire' => 30,
            'min_length' => 12,
            'contains_uppercase' => true,
            'contains_lowercase' => true,
            'contains_number' => false,
            'contains_special' => false,
            'disable_compromised' => false,
        ];

        $testObj = new UserGlobalSettingsService;
        $testObj->savePasswordPolicy(collect($data));

        $this->assertDatabaseMissing('app_settings', [
            'key' => 'auth.passwords.settings.expire',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.min_length',
        ]);
        $this->assertDatabaseMissing('app_settings', [
            'key' => 'auth.passwords.settings.contains_uppercase',
        ]);
        $this->assertDatabaseMissing('app_settings', [
            'key' => 'auth.passwords.settings.contains_lowercase',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.contains_number',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'auth.passwords.settings.contains_special',
        ]);
        $this->assertDatabaseMissing('app_settings', [
            'key' => 'auth.passwords.settings.disable_compromised',
        ]);

        Bus::assertNotDispatched(UpdatePasswordExpireJob::class);
    }

    public function test_get_two_fa_config(): void
    {
        $shouldBe = [
            'required' => (bool) config('auth.twoFa.required'),
            'allow_save_device' => (bool) config('auth.twoFa.allow_save_device'),
        ];

        $testObj = new UserGlobalSettingsService;
        $res = $testObj->getTwoFaConfig();

        $this->assertEquals($shouldBe, $res);
    }

    public function test_get_oath_config(): void
    {
        $shouldBe = [
            'allow_login' => (bool) config('services.azure.allow_login'),
            'allow_bypass_2fa' => (bool) config('services.azure.allow_bypass_2fa'),
            'allow_register' => (bool) config('services.azure.allow_register'),
            'default_role_id' => (int) config('services.azure.default_role_id'),
            'tenant' => config('services.azure.tenant'),
            'client_id' => config('services.azure.client_id'),
            'client_secret' => config('services.azure.client_secret') ? __('admin.fake-password') : '',
            'secret_expires' => config('services.azure.secret_expires'),
            'redirect' => config('services.azure.redirect') ?? 'https://'.config('app.url').'/auth/callback',
        ];

        $testObj = new UserGlobalSettingsService;
        $res = $testObj->getOathConfig();

        $this->assertEquals($shouldBe, $res);
    }

    public function test_update_user_settings_config(): void
    {
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

        $testObj = new UserGlobalSettingsService;
        $testObj->updateUserSettingsConfig(collect($data));

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
