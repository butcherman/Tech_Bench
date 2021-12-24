<?php

namespace App\Listeners\TechTips\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\Admin\TipTypeDeleteFailedEvent;

class LogTipTypeDeleteFail
{
    /**
     * Handle the event
     */
    public function handle(TipTypeDeleteFailedEvent $event)
    {
        Log::notice('Attempt to delete Tech Tip Type by '.Auth::user()->username.' failed as it is still in use by some Tech Tips.', $event->error->errorInfo);
    }
}
