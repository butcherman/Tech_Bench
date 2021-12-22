<?php

namespace App\Listeners\TechTips;

use App\Events\TechTips\TechTipCommentDeletedEvent;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogDeletedTechTip
{
    /**
     * Handle the event
     */
    public function handle(TechTipCommentDeletedEvent $event)
    {
        Log::channel('tip')->info('A Comment has been created for Tech Tip ID - '.$event->comment->tip_id.' by '.Auth::user()->username.'.  Details - ', $event->comment->toArray());
    }
}
