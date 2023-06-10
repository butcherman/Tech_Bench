<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogPasswordReset
{
    /**
     * Handle the event.
     */
    public function handle(PasswordReset $event): void
    {
        //
        Log::stack(['daily', 'auth', 'user'])->info('User '.$event->user->full_name.' has reset their forgotten password', [
            'User ID' => $event->user->user_id,
            'Username' => $event->user->username,
            'IP Address' => \Request::ip(),
        ]);
    }
}
