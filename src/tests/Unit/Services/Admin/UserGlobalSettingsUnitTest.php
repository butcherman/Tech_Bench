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
}
