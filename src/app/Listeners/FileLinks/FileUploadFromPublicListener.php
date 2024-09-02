<?php

namespace App\Listeners\FileLinks;

use App\Events\FileLinks\FileUploadedFromPublicEvent;
use App\Notifications\FileLinks\GuestFileUploadedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class FileUploadFromPublicListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(FileUploadedFromPublicEvent $event): void
    {
        Notification::send(
            $event->link->User,
            new GuestFileUploadedNotification($event->link, $event->timeline)
        );
    }
}
