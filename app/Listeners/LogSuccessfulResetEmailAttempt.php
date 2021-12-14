<?php

namespace App\Listeners;

use App\Events\SuccessfulResetEmailAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogSuccessfulResetEmailAttempt
{

    /**
     * Handle the event
     */
    public function handle(SuccessfulResetEmailAttempt $event)
    {
        Log::channel('auth')->notice('A password reset email has been sent to '.$event->email);
    }
}
