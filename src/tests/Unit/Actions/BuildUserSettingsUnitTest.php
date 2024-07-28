<?php

namespace Tests\Unit\Actions;

use App\Actions\BuildUserSettings;
use App\Models\User;
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
            $shouldBe[] = [
                'setting_type_id' => $setting->setting_type_id,
                'value' => true,
                'name' => $setting->name,
            ];
        }
        $settingData = BuildUserSettings::build($testUser)->toArray();

        $this->assertEquals($settingData, $shouldBe);
    }
}
