<?php

namespace App\Listeners\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Admin\UserRoleCreatedEvent;

class LogUserRoleCreated
{
    /**
     * Handle the event
     */
    public function handle(UserRoleCreatedEvent $event)
    {
        Log::channel('user')->notice('New User Role created by '.Auth::user()->username.'.  Details -', $event->role->toArray());
    }
}
