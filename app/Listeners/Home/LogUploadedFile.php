<?php

namespace App\Listeners\Home;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\Home\UploadedFileEvent;

class LogUploadedFile
{
    /**
     * Handle the event
     */
    public function handle(UploadedFileEvent $event)
    {
        $user = Auth::check() ? Auth::user()->username : \Request::ip();

        Log::info('New file has been uploaded by '.$user.'.  Details - ', $event->file->toArray());
    }
}
