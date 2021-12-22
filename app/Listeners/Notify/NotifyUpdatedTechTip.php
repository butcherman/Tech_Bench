<?php

namespace App\Listeners\Notify;

use App\Events\TechTips\TechTipUpdatedEvent;
use App\Models\User;
use App\Models\UserSettingType;
use App\Notifications\TechTips\UpdatedTechTipNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUpdatedTechTip implements ShouldQueue
{
    /**
     * Handle the event
     */
    public function handle(TechTipUpdatedEvent $event)
    {
        if($event->notify)
        {
            Notification::send(User::all(), new UpdatedTechTipNotification($event->techTip));
        }
    }
}
