<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use App\Notifications\User\SendUserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendUserNotificationTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $user = User::factory()->create();
        $data = [
            'subject' => 'This is a subject',
            'message' => 'This is a message...',
        ];

        $response = $this->post(route('admin.users.send-notification', $user->username), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $user = User::factory()->create();
        $data = [
            'subject' => 'This is a subject',
            'message' => 'This is a message...',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('admin.users.send-notification', $user->username), $data);
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = [
            'subject' => 'This is a subject',
            'message' => 'This is a message...',
        ];

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $user->username), $data);
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.notification_sent'));
        Notification::assertSentTo($user, SendUserNotification::class);
    }
}
