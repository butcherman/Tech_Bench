<?php

namespace App\Listeners\Home;

use App\Events\Home\DownloadedFileEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogDownloadedFile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DownloadedFileEvent  $event
     * @return void
     */
    public function handle(DownloadedFileEvent $event)
    {
        //
    }
}
