<?php

namespace App\Listeners;

use App\Events\UserInitializedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogUserInitialized
{
    /**
     * Handle the event
     */
    public function handle(UserInitializedEvent $event)
    {
        Log::stack(['user', 'auth'])->info('User '.$event->user->username.' has completed setting up their account');
    }
}
