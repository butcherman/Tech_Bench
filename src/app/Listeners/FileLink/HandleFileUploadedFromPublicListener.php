<?php

namespace App\Listeners\FileLink;

use App\Events\FileLink\FileUploadedFromPublicEvent;
use App\Mail\FileLink\FileLinkFileUploadedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleFileUploadedFromPublicListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(FileUploadedFromPublicEvent $event): void
    {
        Mail::to($event->link->User)
            ->send(new FileLinkFileUploadedMail($event->link));
    }
}
