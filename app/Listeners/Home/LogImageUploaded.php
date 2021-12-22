<?php

namespace App\Listeners\Home;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Home\ImageUploadedEvent;

class LogImageUploaded
{
    /**
     * Handle the event
     */
    public function handle(ImageUploadedEvent $event)
    {
        Log::channel('user')->notice('Image uploaded for text box by '.Auth::user()->username.'.  Details - ', [
            'user_id' => Auth::user()->user_id,
            'image_location' => $event->location,
        ]);
    }
}
