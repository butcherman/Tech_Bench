<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogFailedLoginAttempt
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        Log::stack(['daily', 'auth'])->warning('User tried to login with invalid credentials', [
            'Username' => $event->credentials['username'],
            'IP Address' => \Request::ip(),
            'Attempt Number' => session('failed_login') + 1,
        ]);
    }
}
