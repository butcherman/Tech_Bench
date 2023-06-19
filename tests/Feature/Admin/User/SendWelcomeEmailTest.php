<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use App\Models\UserInitialize;
use App\Notifications\User\SendWelcomeEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendWelcomeEmailTest extends TestCase
{
    /**
     * Invoke Method
     */
    public function test_invoke_guest()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.send-welcome', $user->username));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_invoke_no_permission()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('admin.users.send-welcome', $user->username));
        $response->assertStatus(403);
    }

    public function test_invoke()
    {
        Notification::fake();
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.send-welcome', $user->username));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.welcome_sent'));
        $this->assertDatabaseHas('user_initializes', [
            'username' => $user->username,
        ]);
        Notification::assertSentTo($user, SendWelcomeEmail::class);
    }

    public function test_invoke_with_existing_invite()
    {
        Notification::fake();
        $user = User::factory()->create();
        $oldLink = UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);

        $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->get(route('admin.users.send-welcome', $user->username));
        $response->assertStatus(302);
        $response->assertSessionHas('success', __('admin.user.welcome_sent'));
        $this->assertDatabaseHas('user_initializes', [
            'username' => $user->username,
        ]);
        $this->assertDatabaseMissing('user_initializes', [
            'id' => $oldLink->id,
        ]);
        Notification::assertSentTo($user, SendWelcomeEmail::class);
    }
}
