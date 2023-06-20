<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'action' => 'fetch',
        ];

        $response = $this->post(route('user.notifications'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke()
    {
        //  Create two fake notifications
        $notification = [
            'subject' => 'test subject',
            'message' => 'test message',
        ];

        $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $this->user->username), $notification);
        $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $this->user->username), $notification);

        $data = [
            'action' => 'fetch',
        ];

        $response = $this->actingAs($this->user)->post(route('user.notifications'), $data);
        $response->assertSuccessful();
        $response->assertJsonStructure(['list', 'new']);
        $response->assertJsonFragment(['new' => 2]);
    }

    public function test_invoke_mark_as_read()
    {
        //  Create two fake notifications
        $notification = [
            'subject' => 'test subject',
            'message' => 'test message',
        ];

        $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $this->user->username), $notification);
        $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $this->user->username), $notification);

        $notificationList = $this->user->notifications;

        //  Make sure that the notifications are currently unread
        $this->assertNull($notificationList[0]['read_at']);
        $this->assertNull($notificationList[1]['read_at']);

        $data = [
            'action' => 'mark',
            'list' => Arr::pluck($notificationList, 'id'),
        ];

        $response = $this->actingAs($this->user)->post(route('user.notifications'), $data);
        $response->assertSuccessful();
        $response->assertJsonStructure(['list', 'new']);
        $response->assertJsonFragment(['new' => 0]);

        //  Notification should exist,
        $this->assertDatabaseHas('notifications', [
            'id' => $notificationList[0]['id'],
        ]);
        //  but not be unread
        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationList[0]['id'],
            'read_at' => null,
        ]);
        //  Notification should exist,
        $this->assertDatabaseHas('notifications', [
            'id' => $notificationList[1]['id'],
        ]);
        //  but not be unread
        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationList[1]['id'],
            'read_at' => null,
        ]);
    }

    public function test_invoke_delete()
    {
        //  Create two fake notifications
        $notification = [
            'subject' => 'test subject',
            'message' => 'test message',
        ];

        $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $this->user->username), $notification);
        $this->actingAs(User::factory()->create(['role_id' => 1]))->post(route('admin.users.send-notification', $this->user->username), $notification);

        $notificationList = $this->user->notifications;

        //  Make sure that the notifications are currently unread
        $this->assertNull($notificationList[0]['read_at']);
        $this->assertNull($notificationList[1]['read_at']);

        $data = [
            'action' => 'delete',
            'list' => Arr::pluck($notificationList, 'id'),
        ];

        $response = $this->actingAs($this->user)->post(route('user.notifications'), $data);
        $response->assertSuccessful();
        $response->assertJsonStructure(['list', 'new']);
        $response->assertJsonFragment(['new' => 0]);
        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationList[0]['id'],
        ]);
        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationList[1]['id'],
        ]);
    }

    //  Missing notifications are bypassed and do not raise exceptions as they may have been taken care of in a previous request
    public function test_invoke_missing_notification()
    {
        $data = [
            'action' => 'delete',
            'list' => ['some-random-uuid'],
        ];

        $response = $this->actingAs($this->user)->post(route('user.notifications'), $data);
        $response->assertSuccessful();
    }
}
