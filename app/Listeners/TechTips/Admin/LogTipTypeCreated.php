<?php

namespace App\Listeners\TechTips\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\Admin\TipTypeCreatedEvent;

class LogTipTypeCreated
{
    /**
     * Handle the event
     */
    public function handle(TipTypeCreatedEvent $event)
    {
        Log::channel('tips')->info('New Tech Tip Type created by '.Auth::user()->username.'.  Details - ', $event->tipType->toArray());
    }
}
