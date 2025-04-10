<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class HandleLoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $user->UserLogins()->create([
            'ip_address' => request()->ip(),
        ]);

        Log::stack(['daily', 'auth'])
            ->info('User ' . $user->full_name .
                ' successfully logged in from IP Address ' .
                request()->ip(), [
                'User ID' => $user->user_id,
                'Username' => $user->username,
                'IP Address' => request()->ip(),
            ]);
    }
}
