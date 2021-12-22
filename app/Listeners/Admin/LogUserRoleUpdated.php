<?php

namespace App\Listeners\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Admin\UserRoleUpdatedEvent;

class LogUserRoleUpdated
{
    /**
     * Handle the event
     */
    public function handle(UserRoleUpdatedEvent $event)
    {
        Log::channel('user')->notice('User Role updated by '.Auth::user()->username.'.  Details -', $event->role->toArray());
    }
}
