<?php

namespace Tests\Unit\Models;

use App\Models\UserSetting;
use App\Models\UserSettingType;
use Tests\TestCase;

class UserSettingUnitTest extends TestCase
{
    protected $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = UserSetting::find(1);
    }

    /**
     * Model Attributes
     */
    public function test_model_attributes()
    {
        $this->assertArrayHasKey('name', $this->model->toArray());
    }

    /**
     * Model Relationships
     */
    public function test_user_setting_type_relationship()
    {
        $settingType = UserSettingType::where('setting_type_id', $this->model->setting_type_id)
            ->first();
        $this->assertEquals($this->model->UserSettingType, $settingType);
    }
}
