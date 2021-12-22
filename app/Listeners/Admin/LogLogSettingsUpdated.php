<?php

namespace App\Listeners\Admin;

use App\Events\Admin\LogSettingsUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogLogSettingsUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Admin\LogSettingsUpdatedEvent  $event
     * @return void
     */
    public function handle(LogSettingsUpdatedEvent $event)
    {
        Log::notice('Log Settings have been updated by '.Auth::user()->username, $event->logSettings);
    }
}
