<?php

namespace App\Listeners;

use App\Models\UserLogins;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        UserLogins::create(
        [
            'user_id'    => $event->user->user_id,
            'ip_address' => \Request::ip()
        ]);

        Log::stack(['auth', 'user'])->info('User '.$event->user->full_name.' logged in from IP Address '.\Request::ip(), ['User ID' => $event->user->user_id, 'Username' => $event->user->username, 'IP Address' => \Request::ip()]);
    }
}
