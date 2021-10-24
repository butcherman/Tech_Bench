<?php

namespace App\Listeners\TechTips;

use App\Events\TechTips\TechTipCommentCreatedEvent;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogNewTechTip
{
    /**
     * Handle the event
     */
    public function handle(TechTipCommentCreatedEvent $event)
    {
        Log::channel('tips')->info('A Comment has been created for Tech Tip ID - '.$event->comment->tip_id.' by '.Auth::user()->username.'.  Details - ', $event->comment->toArray());
    }
}
