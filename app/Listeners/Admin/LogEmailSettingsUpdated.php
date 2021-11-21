<?php

namespace App\Listeners\Admin;

use App\Events\Admin\EmailSettingsUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogEmailSettingsUpdated
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
     * @param  \App\Events\Admin\EmailSettingsUpdatedEvent  $event
     * @return void
     */
    public function handle(EmailSettingsUpdatedEvent $event)
    {
        //
        Log::notice('Email Settings have been updated by '.Auth::user()->username.'.  Details - ', $event->settings->toArray());
    }
}
