<?php

namespace App\Listeners\TechTips;

use App\Events\TechTips\TechTipEvent;
use App\Models\User;
use App\Notifications\TechTips\NewTechTipNotification;
use App\Notifications\TechTips\UpdatedTechTipNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class TechTipNotificationListener implements ShouldQueue
{
    /**
     * Send the Create or Updated Email Notification
     */
    public function handle(TechTipEvent $event): void
    {
        $author = $event->techTip->updated_id ?? $event->techTip->user_id;

        if ($event->sendNotification) {
            $userList = User::whereNot('user_id', $author)->get();

            if ($event->action->name === 'Create') {
                Log::debug('Sending New Tech Tip Notification to', $userList->toArray());
                Notification::send($userList, new NewTechTipNotification($event->techTip));
            } else {
                Log::debug('Sending Updated Tech Tip Notification to', $userList->toArray());
                Notification::send($userList, new UpdatedTechTipNotification($event->techTip));
            }
        }
    }
}
