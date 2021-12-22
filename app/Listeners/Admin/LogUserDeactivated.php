<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserDeactivatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserDeactivated
{
    /**
     * Handle the event
     */
    public function handle(UserDeactivatedEvent $event)
    {
        Log::channel('user')->notice('User '.$event->user->full_name.' has been deactivated by '.Auth::user()->username);
    }
}
