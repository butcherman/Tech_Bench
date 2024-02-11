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
                'value' => false,
                'name' => $setting->name,
            ];
        }
        // $settingData = (new BuildUserSettings)->build($testUser)->toArray();
        $settingData = BuildUserSettings::build($testUser)->toArray();

        // dd($settingData);

        $this->assertEquals($settingData, $shouldBe);
    }
}
