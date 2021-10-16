<?php

namespace App\Listeners\Notify;

use App\Events\TechTips\TechTipUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUpdatedTechTip
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
     * @param  TechTipUpdatedEvent  $event
     * @return void
     */
    public function handle(TechTipUpdatedEvent $event)
    {
        //
    }
}
