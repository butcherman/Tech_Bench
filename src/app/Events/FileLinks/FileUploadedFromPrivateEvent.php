<?php

// TODO - Refactor

namespace App\Events\FileLinks;

use App\Models\FileLink;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FileUploadedFromPrivateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public FileLink $fileLink)
    {
        Log::debug('File Uploaded from Private Connection Event Called');
    }

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('file-links.'.$this->fileLink->link_hash),
        ];
    }

    public function broadcastAs()
    {
        return 'FileUploadedEvent';
    }

    public function broadcastWith()
    {
        return [
            // 'link_hash' => $this->fileLink->link_hash
            'title' => 'New File Available',
            'message' => 'Refresh page to see new file',
        ];
    }
}
