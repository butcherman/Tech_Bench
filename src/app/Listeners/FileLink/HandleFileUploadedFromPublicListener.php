<?php

namespace App\Listeners\FileLink;

use App\Events\FileLink\HandleFileUploadFromPublicEvent;

class HandleFileUploadedFromPublicListener
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
    public function handle(HandleFileUploadFromPublicEvent $event): void
    {
        //
    }
}
