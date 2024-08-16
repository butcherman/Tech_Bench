<?php

namespace App\Listeners\Notify\FileLinks;

use App\Events\FileLinks\FileUploadedFromPublicEvent;
use App\Notifications\FileLinks\GuestFileUploadedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class FileUploadFromPublicListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

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
