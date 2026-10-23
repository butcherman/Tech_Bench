<?php

namespace Tests\Unit\Listeners\Auth;

use App\Models\User;
use Laravel\Fortify\Events\TwoFactorAuthenticationConfirmed;
use Tests\TestCase;

class HandleTwoFactorAuthenticationConfirmedUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $user = User::factory()->create();

        TwoFactorAuthenticationConfirmed::dispatch($user);

        $user->fresh();

        $this->assertEquals($user->two_factor_via, 'authenticator');
    }
}
