<?php

namespace Tests\Feature\User;

use App\Events\User\UserSettingsUpdatedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateUserSettingsTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest(): void
    {
        $user = User::factory()->createQuietly();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->put(
            route('user.user-settings.update', $user->username),
            $data
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs($user)
            ->put(route('user.user-settings.update', $user->username), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('user.updated'));

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->user_id,
            'setting_type_id' => 1,
            'value' => false,
        ]);

        Event::assertDispatched(UserSettingsUpdatedEvent::class);
    }

    public function test_invoke_another_user_as_admin(): void
    {
        Event::fake();

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 1]);
        $user = User::factory()->createQuietly();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs($actingAs)
            ->put(route('user.user-settings.update', $user->username), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success', __('user.updated'));

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $user->user_id,
            'setting_type_id' => 1,
            'value' => false,
        ]);

        Event::assertDispatched(UserSettingsUpdatedEvent::class);
    }

    public function test_invoke_another_user(): void
    {
        Event::fake();

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs($actingAs)
            ->put(route('user.user-settings.update', $user->username), $data);

        $response->assertForbidden();

        Event::assertNotDispatched(UserSettingsUpdatedEvent::class);
    }

    public function test_invoke_higher_user(): void
    {
        Event::fake();

        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly(['role_id' => 2]);
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $data = [
            'settingList' => ['type_id_1' => false],
        ];

        $response = $this->actingAs($actingAs)
            ->put(route('user.user-settings.update', $user->username), $data);

        $response->assertForbidden();

        Event::assertNotDispatched(UserSettingsUpdatedEvent::class);
    }
}
