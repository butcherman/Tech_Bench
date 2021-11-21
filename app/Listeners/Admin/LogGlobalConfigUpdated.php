<?php

namespace App\Listeners\Admin;

use App\Events\Admin\GlobalConfigUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogGlobalConfigUpdated
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
     * @param  GlobalConfigUpdatedEvent  $event
     * @return void
     */
    public function handle(GlobalConfigUpdatedEvent $event)
    {
        Log::alert('Global Configuration Settings have been updated by '.Auth::user()->username.'.  Details - ', $event->settings->toArray());
    }
}
