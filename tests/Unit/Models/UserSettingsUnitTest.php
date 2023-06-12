<?php

namespace Tests\Unit\Models;

use App\Models\UserSetting;
use App\Models\UserSettingType;
use Tests\TestCase;

class UserSettingsUnitTest extends TestCase
{
    protected $userSetting;

    public function setUp():void
    {
        parent::setUp();

        $this->userSetting = UserSetting::find(1);
    }

    /**
     * Test Additional Attributes
     */
    public function test_attributes()
    {
        $this->assertArrayHasKey('name', $this->userSetting->toArray());
    }

    /**
     * Test Model Relationships
     */
    public function test_model_relationships()
    {
        $settingType = UserSettingType::where('setting_type_id', $this->userSetting->setting_type_id)->first();
        $this->assertEquals($this->userSetting->UserSettingType->name, $settingType->name);
    }
}
