<?php

namespace App\Events\FileLinks;

use App\Models\FileLink;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileLinkEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * File Link Event triggered on Create/Update/Destroy of a File Link
     */
    public function __construct(public FileLink $link) {}
}
