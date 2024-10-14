<?php

namespace App\Events\File;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileDataDeletedEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when a file is removed from the file_uploads table
     */
    public function __construct(public int $fileId) {}
}
