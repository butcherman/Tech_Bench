<?php

namespace App\Listeners\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\TechTipUpdatedEvent;

class LogTechTipUpdated
{
    /**
     * Handle the event
     */
    public function handle(TechTipUpdatedEvent $event)
    {
        Log::channel('tip')->info('Tech Tip '.$event->techTip->subject.' updated by '.Auth::user()->username);
    }
}
