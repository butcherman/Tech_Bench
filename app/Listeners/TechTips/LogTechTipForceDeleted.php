<?php

namespace App\Listeners\TechTips;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\TechTips\TechTipForceDeletedEvent;

class LogTechTipForceDeleted
{
    /**
     * Handle the event
     */
    public function handle(TechTipForceDeletedEvent $event)
    {
        Log::channel('tip')->alert('Tech Tip ID '.$event->tip->tip_id.' has been permanently deleted by '.Auth::user()->username.'.  Details - ', $event->tip->toArray());
    }
}
