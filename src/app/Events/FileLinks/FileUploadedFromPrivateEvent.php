<?php

namespace App\Events\FileLinks;

use App\Models\FileLink;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadedFromPrivateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when a file is uploaded to a private file link
     */
    public function __construct(public FileLink $fileLink) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('file-links.'.$this->fileLink->link_hash),
        ];
    }

    public function broadcastAs(): string
    {
        return 'FileUploadedEvent';
    }

    public function broadcastWith(): array
    {
        return [
            'title' => 'New File Available',
            'message' => 'Refresh page to see new file',
        ];
    }
}
