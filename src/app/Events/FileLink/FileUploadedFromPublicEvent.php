<?php

namespace App\Events\FileLink;

use App\Models\FileLink;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadedFromPublicEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    |---------------------------------------------------------------------------
    | Event is triggered when a file is uploaded to a public file link from
    | the public URL.  An email will be sent to the link creator.
    |---------------------------------------------------------------------------
    */
    public function __construct(public FileLink $link, public int $timelineId) {}
}
