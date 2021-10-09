<?php

namespace App\Listeners\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Admin\UserRoleDeletedEvent;

class LogUserRoleDeleted
{
    /**
     * Handle the event
     */
    public function handle(UserRoleDeletedEvent $event)
    {
        Log::channel('user')->notice('User Role deleted by '.Auth::user()->username.'.  Details -', $event->role->toArray());
    }
}
