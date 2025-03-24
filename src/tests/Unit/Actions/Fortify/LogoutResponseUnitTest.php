<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\LogoutResponse;
use App\Models\User;
use Illuminate\Http\Request;
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
        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertSessionHas('info', 'Successfully Logged Out');
    }

    public function test_to_response_timeout(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('logout', ['reason' => 'timeout']));

        $response->assertSessionHas(
            'info',
            'You have been logged out after being idle for more than ' .
                config('auth.auto_logout_timer') . ' minutes'
        );
    }
}
