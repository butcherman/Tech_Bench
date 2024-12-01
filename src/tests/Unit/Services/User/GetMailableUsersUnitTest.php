<?php

namespace Tests\Unit\Services\User;

use App\Models\User;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use App\Services\User\GetMailableUsers;
use Tests\TestCase;

class GetMailableUsersUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getAllMailable()
    |---------------------------------------------------------------------------
    */
    public function test_get_all_mailable(): void
    {
        User::factory()->count(10)->create(['role_id' => 4]);

        $testObj = new GetMailableUsers;
        $res = $testObj->getAllMailable();

        $this->assertCount(11, $res);
    }

    public function test_get_all_mailable_ignore_installer(): void
    {
        User::factory()->count(10)->create(['role_id' => 4]);

        $testObj = new GetMailableUsers;
        $res = $testObj->getAllMailable(User::find(1));

        $this->assertCount(10, $res);
    }

    public function test_get_all_mailable_ignore_setting_off(): void
    {
        $userList = User::factory()->count(10)->create(['role_id' => 4]);
        $settingId = UserSettingType::where(
            'name',
            'Receive Email Notifications'
        )
            ->first()
            ->setting_type_id;

        foreach ($userList as $index => $user) {
            if ($index > 5) {
                UserSetting::where('user_id', $user->user_id)
                    ->where('setting_type_id', $settingId)
                    ->update(['value' => false]);
            }
        }

        $testObj = new GetMailableUsers;
        $res = $testObj->getAllMailable();

        $this->assertCount(7, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getAdminUsers()
    |---------------------------------------------------------------------------
    */
    public function test_get_admin_users(): void
    {
        User::factory()->count(5)->create(['role_id' => 1]);
        User::factory()->count(5)->create(['role_id' => 2]);
        User::factory()->count(10)->create(['role_id' => 3]);
        User::factory()->count(15)->create(['role_id' => 4]);

        $testObj = new GetMailableUsers;
        $res = $testObj->getAdminUsers();

        $this->assertCount(11, $res);
    }
}
