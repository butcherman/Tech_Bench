<?php

namespace App\Listeners\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\TechTipDeletedEvent;

class LogTechTipDeleted
{
    /**
     * Handle the event
     */
    public function handle(TechTipDeletedEvent $event)
    {
        Log::channel('tip')->info('Tech Tip '.$event->techTip->subject.' deleted by '.Auth::user()->username);
    }
}
