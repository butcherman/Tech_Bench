<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Log;

class LogLockout
{
    /**
     * Handle the event
     */
    public function handle(Lockout $event)
    {
        Log::channel('auth')->notice('Username '.$event->request->username.' has been locked out due to too many failed login attempts', [
            'Username'         => $event->request->username,
            'Attempts Made'    => session('failed_login'),
            'Locked Out Until' => session('timeout'),
            'IP Address'       => \Request::ip(),
        ]);
    }
}
