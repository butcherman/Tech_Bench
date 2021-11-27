<?php

namespace App\Listeners\TechTips;

use App\Events\TechTips\TechTipCommentFlaggedEvent;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogFlaggedTechTip
{
    /**
     * Handle the event
     */
    public function handle(TechTipCommentFlaggedEvent $event)
    {
        Log::channel('tip')->alert('A Tech Tip Comment has been flagged as Innapropriate by '.Auth::user()->username.'.  Details - ', $event->comment->toArray());
    }
}
