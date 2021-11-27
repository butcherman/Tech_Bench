<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserUpdated
{
    /**
     * Handle the event
     */
    public function handle(UserUpdatedEvent $event)
    {
        Log::channel('user')->notice('User '.$event->user->username.' has been updated by '.Auth::user()->username.'.  Details - ', $event->user->toArray());
    }
}
