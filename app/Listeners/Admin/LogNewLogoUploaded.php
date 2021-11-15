<?php

namespace App\Listeners\Admin;

use App\Events\Admin\NewLogoUploadedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogNewLogoUploaded
{
    /**
     * Handle the event
     */
    public function handle(NewLogoUploadedEvent $event)
    {
        Log::notice('A new Application Logo has been uploaded by '.Auth::user()->username.'.  Logo file can be found at '.$event->location);
    }
}
