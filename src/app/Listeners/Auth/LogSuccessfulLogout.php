<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        Log::stack(['daily', 'auth'])
            ->info('User '.$event->user->username.' logged out', [
                'User ID' => $event->user->user_id,
                'Username' => $event->user->username,
                'IP Address' => request()->ip(),
            ]);
    }
}
