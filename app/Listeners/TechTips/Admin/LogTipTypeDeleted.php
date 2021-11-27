<?php

namespace App\Listeners\TechTips\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\Admin\TipTypeDeletedEvent;

class LogTipTypeDeleted
{
    /**
     * Handle the event
     */
    public function handle(TipTypeDeletedEvent $event)
    {
        Log::channel('tip')->info('A Tech Tip Type has been deleted by '.Auth::user()->username.'.  Details - ', $event->tipType->toArray());
    }
}
