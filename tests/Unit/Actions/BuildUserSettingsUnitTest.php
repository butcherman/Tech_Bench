<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildUserSettings;
use App\Models\User;
use App\Models\UserRolePermissionTypes;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Tests\TestCase;

class BuildUserSettingsUnitTest extends TestCase
{
    /**
     * Build Method
     */
    public function test_build_as_default()
    {
        $testUser = User::factory()->create();
        $settings = UserSettingType::all();
        $shouldBe = [];

        foreach ($settings as $setting) {
            UserSetting::create([
                'user_id' => $testUser->user_id,
                'setting_type_id' => $setting->setting_type_id,
                'value' => true,
            ]);

            $shouldBe[] = [
                'setting_type_id' => $setting->setting_type_id,
                'value' => true,
                'name' => $setting->name,
                'user_setting_type' => [
                    'name' => $setting->name,
                ]
            ];
        }
        $settingData = (new BuildUserSettings)->build($testUser)->toArray();

        $this->assertEquals($settingData, $shouldBe);
    }
}
