<?php

namespace App\Listeners\FileLink;

use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Notifications\FileLink\LinkFileUploadedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class HandleFileUploadedFromPublicListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(FileUploadedFromPublicEvent $event): void
    {
        $owner = $event->link->User;

        Notification::send(
            $owner,
            new LinkFileUploadedNotification($event->link)
        );
    }
}
