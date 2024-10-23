<?php

namespace App\Events\FileLinks;

use App\Models\FileLink;
use App\Models\FileLinkTimeline;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploadedFromPublicEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when a file is uploaded to a public file link
     */
    public function __construct(public FileLink $link, public FileLinkTimeline $timeline) {}

    /**
     * Get the channels the event should broadcast on
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('file-link.'.$this->link->link_id),
        ];
    }

    /**
     * Get the name the event should broadcast as
     *
     * @codeCoverageIgnore
     */
    public function broadcastAs(): string
    {
        return 'FileUploadedEvent';
    }
}
