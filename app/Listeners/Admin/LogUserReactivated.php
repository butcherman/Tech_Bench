<?php

namespace App\Listeners\Admin;

use App\Events\Admin\UserReactivatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserReactivated
{
    /**
     * Handle the event
     */
    public function handle(UserReactivatedEvent $event)
    {
        Log::channel('user')->notice('User '.$event->user->full_name.' has been reactivated by '.Auth::user()->username.'.  Details - ', $event->user->toArray());
    }
}
