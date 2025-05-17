<?php

namespace App\Listeners\TechTip;

use App\Enums\CrudAction;
use App\Events\TechTip\NotifiableTechTipEvent;
use App\Models\User;
use App\Notifications\TechTip\NewTechTipNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class HandleNotifiableTechTipListener implements ShouldQueue
{
    /**
     * Send Notifications to all users about the Tech Tip Event.
     */
    public function handle(NotifiableTechTipEvent $event): void
    {
        Log::debug('Handling Notifiable Tech Tip Event', [
            'tip_id' => $event->techTip->tip_id,
            'action' => $event->action,
        ]);

        $userList = User::whereNot('user_id', $event->ignoreUser->user_id)
            ->get();

        if ($event->action === CrudAction::Create) {
            Notification::send(
                $userList,
                new NewTechTipNotification($event->techTip)
            );
        }

        if ($event->action === CrudAction::Update) {
            // TODO - Handle Update Action
        }
    }
}
