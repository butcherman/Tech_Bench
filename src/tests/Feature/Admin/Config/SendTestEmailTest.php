<?php

namespace Tests\Feature\Admin\Config;

use App\Models\User;
use App\Notifications\Admin\SendTestEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendTestEmailTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $response = $this->get(route('admin.test-email'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.test-email'));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Notification::fake();

        /** @var User $user */
        $user = $user = User::factory()->createQuietly(['role_id' => 1]);

        $response = $this->actingAs($user)
            ->get(route('admin.test-email'));
        $response->assertStatus(302);

        Notification::assertSentTo($user, SendTestEmail::class);
    }
}
