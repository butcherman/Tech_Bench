<?php

namespace App\Listeners;

use App\Events\FailedResetEmailAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogFailedResetEmailAttempt
{
    /**
     * Handle the event
     */
    public function handle(FailedResetEmailAttempt $event)
    {
        Log::channel('auth')->warning('Someone has failed to enter a proper email address to request a Password Reset link', [
            'Email'      => $event->email,
            'IP Address' => \Request::ip(),
        ]);
    }
}
