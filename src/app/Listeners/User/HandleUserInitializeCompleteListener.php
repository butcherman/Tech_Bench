<?php

namespace App\Listeners\User;

use App\Events\User\UserInitializeComplete;
use Illuminate\Support\Facades\Log;

class HandleUserInitializeCompleteListener
{
    /**
     * Handle the event.
     */
    public function handle(UserInitializeComplete $event): void
    {
        Log::notice(
            'User '.$event->token->User->full_name.
            ' has finished setting up their account'
        );

        $event->token->delete();
    }
}