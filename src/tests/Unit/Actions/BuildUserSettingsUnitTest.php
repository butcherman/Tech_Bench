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
            if (is_null($setting->feature_name) && is_null($setting->feature_key)) {
                $shouldBe[] = [
                    'setting_type_id' => $setting->setting_type_id,
                    'value' => true,
                    'name' => $setting->name,
                ];
            }
        }
        $settingData = BuildUserSettings::build($testUser)->toArray();

        // dd($settingData, $shouldBe);

        $this->assertEquals($settingData, $shouldBe);
    }
}
