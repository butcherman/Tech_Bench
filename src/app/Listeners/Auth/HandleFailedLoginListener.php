<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;

class HandleFailedLoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        Log::stack(['daily', 'auth'])
            ->warning('User tried to login with invalid credentials', [
                'Username' => $event->credentials['username'],
                'IP Address' => request()->ip(),
                'Attempt Number' => session('failed_login') + 1,
            ]);
    }
}
