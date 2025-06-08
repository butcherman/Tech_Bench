<?php

namespace App\Listeners\Auth;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Fortify\Events\TwoFactorAuthenticationConfirmed;

class HandleTwoFactorAuthenticationConfirmedListener
{
    /**
     * Set the users preferred 2FA method to be the authenticator app
     */
    public function handle(TwoFactorAuthenticationConfirmed $event): void
    {
        $event->user->two_factor_via = 'authenticator';
        $event->user->save();
    }
}
