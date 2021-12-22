<?php

namespace App\Listeners\Notify;

use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Events\TechTips\TechTipCreatedEvent;
use App\Notifications\TechTips\NewTechTipNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyNewTechTip implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(TechTipCreatedEvent $event)
    {
        if($event->notify)
        {
            Notification::send(User::all(), new NewTechTipNotification($event->techTip));
        }
    }
}
