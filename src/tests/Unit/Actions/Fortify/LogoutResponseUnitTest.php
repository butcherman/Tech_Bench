<?php

namespace Tests\Unit\Actions\Fortify;

use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LogoutResponseUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | toResponse()
    |---------------------------------------------------------------------------
    */
    public function test_to_response_normal(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertSessionHas('info', 'Successfully Logged Out');

        Event::assertDispatched(Logout::class);
    }

    public function test_to_response_timeout(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('logout', ['reason' => 'timeout']));

        $response->assertSessionHas(
            'info',
            'You have been logged out after being idle for more than '.
                config('auth.auto_logout_timer').' minutes'
        );

        Event::assertDispatched(Logout::class);
    }
}
