<?php

namespace Tests\Feature\Home;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $data = [
            'action' => 'read',
            'idList' => [],
        ];

        $response = $this->post(route('handle-notifications'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_read()
    {
        $user = User::factory()->create();
        $notificationData = [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->user_id,
            'data' => '{data}',
        ];

        DB::table('notifications')->insert([
            $notificationData,
        ]);

        $data = [
            'action' => 'read',
            'idList' => [$notificationData['id']],
        ];

        $response = $this->actingAs($user)
            ->post(route('handle-notifications'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('notifications', [
            'id' => $notificationData['id'],
        ]);
        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationData['id'],
            'read_at' => null,
        ]);
    }

    public function test_invoke_delete()
    {
        $user = User::factory()->create();
        $notificationData = [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->user_id,
            'data' => '{data}',
        ];

        DB::table('notifications')->insert([
            $notificationData,
        ]);

        $data = [
            'action' => 'delete',
            'idList' => [$notificationData['id']],
        ];

        $response = $this->actingAs($user)
            ->post(route('handle-notifications'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseMissing('notifications', [
            'id' => $notificationData['id'],
        ]);
    }

    public function test_invoke_someone_elses_notification()
    {
        $user = User::factory()->create();
        $notificationData = [
            'id' => Str::uuid(),
            'type' => 'App\Notifications\TestNotification',
            'notifiable_type' => 'App\Models\User',
            'notifiable_id' => $user->user_id,
            'data' => '{data}',
        ];

        DB::table('notifications')->insert([
            $notificationData,
        ]);

        $data = [
            'action' => 'delete',
            'idList' => [$notificationData['id']],
        ];

        $response = $this->actingAs(User::factory()->create())
            ->post(route('handle-notifications'), $data);
        $response->assertStatus(302);

        $this->assertDatabaseHas('notifications', [
            'id' => $notificationData['id'],
            'read_at' => null,
        ]);
    }
}
