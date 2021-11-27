<?php

namespace App\Events\Home;

use App\Models\FileUploads;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DownloadedFileEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $file;

    /**
     * Create a new event instance
     */
    public function __construct(FileUploads $file)
    {
        $this->file = $file;
    }
}
