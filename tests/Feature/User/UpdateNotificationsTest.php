<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\TestCase;

class UpdateNotificationsTest extends TestCase
{
    public function test_invoke_guest()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->user_id,
            'settingsData' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->post(route('settings.notifications'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->user_id,
            'settingsData' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs($user)->post(route('settings.notifications'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.notification_updated'));
    }

    public function test_invoke_another_user_as_admin()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->user_id,
            'settingsData' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('settings.notifications'), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.notification_updated'));
    }

    public function test_invoke_another_user()
    {
        $user = User::factory()->create();
        $data = [
            'user_id' => $user->user_id,
            'settingsData' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('settings.notifications'), $data);
        $response->assertStatus(403);
    }

    public function test_invoke_higher_user()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'user_id' => $user->user_id,
            'settingsData' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->post(route('settings.notifications'), $data);
        $response->assertStatus(403);
    }
}
