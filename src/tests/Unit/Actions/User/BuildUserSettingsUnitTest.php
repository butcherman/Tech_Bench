<?php

namespace Tests\Unit\Actions\User;

use App\Actions\User\BuildUserSettings;
use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Tests\TestCase;

class BuildUserSettingsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | build()
    |---------------------------------------------------------------------------
    */
    public function test_build_as_default(): void
    {
        $testUser = User::factory()->createQuietly();
        $testObj = new BuildUserSettings;
        $shouldBe = [
            [
                'setting_type_id' => 1,
                'name' => 'Receive Email Notifications',
                'value' => true,
            ],
        ];

        $settingData = $testObj($testUser);

        $this->assertEquals($settingData->toArray(), $shouldBe);
    }

    public function test_build_file_link_enabled(): void
    {
        config(['file-link.feature_enabled' => true]);

        $testUser = User::factory()->createQuietly();
        $testObj = new BuildUserSettings;
        $shouldBe = [
            [
                'setting_type_id' => 1,
                'name' => 'Receive Email Notifications',
                'value' => true,
            ],
            [
                'setting_type_id' => 2,
                'name' => 'Auto Delete Expired File Links',
                'value' => true,
            ],
        ];

        $settingData = $testObj($testUser);

        $this->assertEquals($settingData->toArray(), $shouldBe);
    }

    public function test_build_file_link_permission_removed(): void
    {
        config(['file-link.feature_enabled' => true]);

        $fileLinkPerm = UserRolePermissionType::where(
            'description',
            'Use File Links'
        )->first();

        UserRolePermission::where('role_id', 4)
            ->where('perm_type_id', $fileLinkPerm->perm_type_id)
            ->update(['allow' => false]);

        $testUser = User::factory()->createQuietly();
        $testObj = new BuildUserSettings;
        $shouldBe = [
            [
                'setting_type_id' => 1,
                'name' => 'Receive Email Notifications',
                'value' => true,
            ],
        ];

        $settingData = $testObj($testUser);

        $this->assertEquals($settingData->toArray(), $shouldBe);
    }

    public function test_build_file_link_delete_config_disabled(): void
    {
        config(['file-link.feature_enabled' => true]);
        config(['file-link.auto_delete_override' => false]);

        $testUser = User::factory()->createQuietly();
        $testObj = new BuildUserSettings;
        $shouldBe = [
            [
                'setting_type_id' => 1,
                'name' => 'Receive Email Notifications',
                'value' => true,
            ],
        ];

        $settingData = $testObj($testUser);

        $this->assertEquals($settingData->toArray(), $shouldBe);
    }
}
