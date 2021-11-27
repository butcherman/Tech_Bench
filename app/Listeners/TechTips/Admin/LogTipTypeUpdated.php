<?php

namespace App\Listeners\TechTips\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\Admin\TipTypeUpdatedEvent;

class LogTipTypeUpdated
{
    /**
     * Handle the event
     */
    public function handle(TipTypeUpdatedEvent $event)
    {
        Log::channel('tip')->info('A Tech Tip Type has been updated by '.Auth::user()->username.'.  Details - ', $event->tipType->toArray());
    }
}
