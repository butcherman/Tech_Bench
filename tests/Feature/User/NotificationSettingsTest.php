<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Notifications\User\SendAuthCode;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationSettingsTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $user = User::factory()->create();
        $data = [
            'receive_sms' => false,
            'phone' => null,
            'settingList' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->post(route('user.settings.notifications', ['user' => $user->username]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        $user = User::factory()->create();
        $data = [
            'receive_sms' => false,
            'phone' => null,
            'settingList' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs($user)->post(route('user.settings.notifications', ['user' => $user->username]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.notification'));
    }

    public function test_invoke_add_phone()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = [
            'receive_sms' => true,
            'phone' => 2135551212,
            'settingList' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs($user)->post(route('user.settings.notifications', ['user' => $user->username]), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('2fa.index'));
        $response->assertSessionHas('success', __('user.verify-sms'));
        $response->assertSessionHas('verify_sms', true);

        Notification::assertSentTo($user, SendAuthCode::class);
    }

    public function test_invoke_another_user_as_admin()
    {
        $user = User::factory()->create();
        $data = [
            'receive_sms' => false,
            'phone' => null,
            'settingList' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('user.settings.notifications', ['user' => $user->username]), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('user.notification'));
    }

    public function test_invoke_another_user()
    {
        $user = User::factory()->create();
        $data = [
            'receive_sms' => false,
            'phone' => null,
            'settingList' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('user.settings.notifications', ['user' => $user->username]), $data);
        $response->assertStatus(403);
    }

    public function test_invoke_higher_user()
    {
        $user = User::factory()->create(['role_id' => 1]);
        $data = [
            'receive_sms' => false,
            'phone' => null,
            'settingList' => ['type_id_1' => false, 'type_id_2' => true],
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 2]))->post(route('user.settings.notifications', ['user' => $user->username]), $data);
        $response->assertStatus(403);
    }
}
