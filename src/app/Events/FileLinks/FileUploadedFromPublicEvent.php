<?php

namespace App\Events\FileLinks;

use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FileUploadedFromPublicEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public FileLink $link, public FileLinkTimeline $timeline)
    {
        Log::debug('File Uploaded from Public Event Called');
    }

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('file-link.'.$this->link->link_id),
        ];
    }

    public function broadcastAs()
    {
        return 'FileUploadedEvent';
    }
}
