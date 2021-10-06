<?php

namespace App\Listeners\Admin;

use App\Events\Admin\NewUserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserCreated
{
    /**
     * Handle the event
     */
    public function handle(NewUserCreated $event)
    {
        Log::channel('user')->notice('New User created by '.Auth::user()->username.'.  Details - ', $event->user->makeVisible('user_id')->toArray());
    }
}
