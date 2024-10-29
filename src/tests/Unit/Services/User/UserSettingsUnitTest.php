<?php

namespace Tests\Unit\Services\User;

use App\Events\User\UserEmailChangedEvent;
use App\Events\User\UserSettingsUpdatedEvent;
use App\Models\User;
use App\Services\User\UserSettingsService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserSettingsUnitTest extends TestCase
{
    /**
     * Update User Account Method
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

    /**
     * Update User Settings Method
     */
    public function test_update_user_settings(): void
    {
        Event::fake(UserSettingsUpdatedEvent::class);

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

        Event::assertDispatched(UserSettingsUpdatedEvent::class);
    }
}
