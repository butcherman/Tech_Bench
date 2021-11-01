<?php

namespace App\Listeners\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\TechTipRestoredEvent;

class LogTechTipRestored
{
    /**
     * Handle the event
     */
    public function handle(TechTipRestoredEvent $event)
    {
        Log::channel('tips')->info('Tech Tip ID '.$event->tip->tip_id.' restored by '.Auth::user()->username.'.  Details - ', $event->tip->toArray());
    }
}
