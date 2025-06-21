<?php

namespace Tests\Unit\Services\User;

use App\Events\User\UserEmailChangedEvent;
use App\Models\User;
use App\Services\User\UserSettingsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserSettingsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | updateUserAccount()
    |---------------------------------------------------------------------------
    */
    public function test_update_user_account(): void
    {
        Event::fake(UserEmailChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => 'SomeRandomEmail@em.com',
        ];

        $testObj = new UserSettingsService;
        $testObj->updateUserAccount(collect($data), $user);

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        Event::assertDispatched(UserEmailChangedEvent::class);
    }

    public function test_update_user_account_no_email_change(): void
    {
        Event::fake(UserEmailChangedEvent::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'first_name' => 'Billy',
            'last_name' => 'Bob',
            'email' => $user->email,
        ];

        $testObj = new UserSettingsService;
        $testObj->updateUserAccount(collect($data), $user);

        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);

        Event::assertNotDispatched(UserEmailChangedEvent::class);
    }

    /*
    |---------------------------------------------------------------------------
    | updateUserSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_user_settings(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $this->actingAs($user);

        $testObj = new UserSettingsService;
        $testObj->updateUserSettings(collect($data), $user);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->user_id,
            'setting_type_id' => 1,
            'value' => false,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | verifyUserSettings()
    |---------------------------------------------------------------------------
    */
    public function test_verify_user_settings(): void
    {
        $testUser = User::factory()->make();
        User::factory()->count(10)->create();
        DB::table('users')
            ->insert($testUser->only([
                'user_id',
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]));

        $testObj = new UserSettingsService;
        $res = $testObj->verifyUserSettings(false);

        $pulledTestUser = User::where('username', $testUser->username)->first();

        $this->assertCount(1, $res);

        $this->assertDatabaseMissing('user_settings', [
            'user_id' => $pulledTestUser->user_id,
            'setting_type_id' => 1,
        ]);
        $this->assertDatabaseMissing('user_settings', [
            'user_id' => $pulledTestUser->user_id,
            'setting_type_id' => 2,
        ]);
    }

    public function test_verify_user_settings_fix_on(): void
    {
        $testUser = User::factory()->make();
        User::factory()->count(10)->create();
        DB::table('users')
            ->insert($testUser->only([
                'user_id',
                'username',
                'first_name',
                'last_name',
                'email',
                'role_id',
            ]));

        $testObj = new UserSettingsService;
        $res = $testObj->verifyUserSettings(true);

        $pulledTestUser = User::where('username', $testUser->username)->first();

        $this->assertCount(1, $res);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $pulledTestUser->user_id,
            'setting_type_id' => 1,
        ]);
        $this->assertDatabaseHas('user_settings', [
            'user_id' => $pulledTestUser->user_id,
            'setting_type_id' => 2,
        ]);
        $this->assertDatabaseHas('user_settings', [
            'user_id' => $pulledTestUser->user_id,
            'setting_type_id' => 3,
        ]);
    }
}
