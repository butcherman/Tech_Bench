<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class _LogoutTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test Logging out
    |---------------------------------------------------------------------------
    */

    // Verify that the user can log out
    public function test_logout(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->post(route('logout'));

        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertDispatched(Logout::class);
    }

    // Verify that the user can log out with a logout message
    public function test_logout_timeout(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->post(route('logout'), ['reason' => 'timeout']);

        $response->assertStatus(302)->assertRedirect(route('login'));
        $this->assertGuest();

        Event::assertDispatched(Logout::class);
    }
}
