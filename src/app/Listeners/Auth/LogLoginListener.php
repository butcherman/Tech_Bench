<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogLoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // TODO - Add Model
        // UserLogins::create(
        //     [
        //         'user_id' => $event->user->user_id,
        //         'ip_address' => request()->ip(),
        //     ]);

        Log::stack(['daily', 'auth'])
            ->info('User '.$event->user->full_name.' successfully logged in from IP Address '.request()->ip(), [
                'User ID' => $event->user->user_id,
                'Username' => $event->user->username,
                'IP Address' => request()->ip(),
            ]);
    }
}
