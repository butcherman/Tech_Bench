<?php

namespace App\Listeners\Auth;

use App\Models\UserLogins;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        UserLogins::create(
            [
                'user_id' => $event->user->user_id,
                'ip_address' => \Request::ip(),
            ]);

        Log::stack(['daily', 'auth'])->info('User '.$event->user->full_name.' successfully logged in from IP Address '.\Request::ip(), [
            'User ID' => $event->user->user_id,
            'Username' => $event->user->username,
            'IP Address' => \Request::ip(),
        ]);
    }
}
