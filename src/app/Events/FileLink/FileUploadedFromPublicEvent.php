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
    | Event is triggered when someone uploads a file to a File Link from the
    | public URL.
    |---------------------------------------------------------------------------
    */
    public function __construct(public FileLink $link) {}

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
