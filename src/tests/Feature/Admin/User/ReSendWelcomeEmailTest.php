<?php

namespace Tests\Feature\Admin\User;

use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class ReSendWelcomeEmailTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Invoke Method
    |---------------------------------------------------------------------------
    */
    public function test_invoke_guest()
    {
        Bus::fake();

        $user = User::factory()->createQuietly();

        $response = $this->get(
            route('admin.user.send-welcome', $user->username)
        );

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();

        Bus::assertNothingDispatched();
    }

    public function test_invoke_no_permission()
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $newUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.send-welcome', $newUser->username));

        $response->assertStatus(403);

        Bus::assertNothingDispatched();
    }

    public function test_invoke()
    {
        Bus::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly(['role_id' => 1]);
        $newUser = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('admin.user.send-welcome', $newUser->username));

        $response->assertStatus(302)
            ->assertSessionHas('success', __('admin.user.welcome_sent'));

        Bus::assertDispatched(SendWelcomeEmailJob::class);
    }
}
