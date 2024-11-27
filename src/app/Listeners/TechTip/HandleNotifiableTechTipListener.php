<?php

namespace App\Listeners\TechTip;

use App\Events\TechTip\NotifiableTechTipEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleNotifiableTechTipListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotifiableTechTipEvent $event): void
    {
        //
    }
}
