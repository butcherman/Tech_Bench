<?php

namespace App\Listeners\Home;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Home\DownloadedFileEvent;

class LogDownloadedFile
{
    /**
     * Handle the event
     */
    public function handle(DownloadedFileEvent $event)
    {
        $user = Auth::check() ? Auth::user()->username : \Request::ip();
        Log::info('File has been download by '.$user, $event->file->toArray());
    }
}
