<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;

class HandleLockoutListener
{
    /**
     * Handle the event.
     */
    public function handle(Lockout $event): void
    {
        Log::stack(['app', 'auth'])
            ->notice(
                'Username '.$event->request->username.
                    ' has been locked out due to too many failed login attempts',
                [
                    'Username' => $event->request->username,
                    'IP Address' => $event->request->ip(),
                ]
            );
    }
}
