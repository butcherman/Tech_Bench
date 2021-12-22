<?php

namespace App\Listeners\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\TechTipCreatedEvent;

class LogTechTipCreated
{
    /**
     * Handle the event
     */
    public function handle(TechTipCreatedEvent $event)
    {
        Log::channel('tip')->info('New Tech Tip '.$event->techTip->subject.' created by '.Auth::user()->username);
    }
}
