<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\CreateUserSettingsJob;
use App\Models\User;
use App\Models\UserSettingType;
use Tests\TestCase;

class CreateUserSettingsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $user = User::factory()->create();

        $job = new CreateUserSettingsJob($user);
        $job->handle();

        $settingsTypes = UserSettingType::all();
        foreach ($settingsTypes as $type) {
            $this->assertDatabaseHas('user_settings', [
                'user_id' => $user->user_id,
                'setting_type_id' => $type->setting_type_id,
                'value' => 1,
            ]);
        }
    }
}
