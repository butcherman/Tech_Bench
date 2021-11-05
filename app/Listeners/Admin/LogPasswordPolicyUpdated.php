<?php

namespace App\Listeners\Admin;

use App\Events\Admin\PasswordPolicyUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogPasswordPolicyUpdated
{
    /**
     * Handle the event
     */
    public function handle(PasswordPolicyUpdatedEvent $event)
    {
        Log::notice('User '.Auth::user()->username.' has updated the users password policy.  Details - ', [
            'password_expires' => $event->expiryDays,
        ]);
    }
}
