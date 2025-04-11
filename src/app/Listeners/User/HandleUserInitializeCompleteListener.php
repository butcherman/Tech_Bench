<?php

namespace App\Listeners\User;

use App\Events\User\UserInitializeComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class HandleUserInitializeCompleteListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(UserInitializeComplete $event): void
    {
        Log::stack(['daily', 'auth'])->notice(
            'User '.$event->token->User->full_name.
                ' has finished setting up their account'
        );

        $event->token->delete();
    }
}
