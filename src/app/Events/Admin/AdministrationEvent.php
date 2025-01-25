<?php

namespace App\Events\Admin;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdministrationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered by an event that needs to send real time data
     * to the browser.
     */
    public function __construct(public string $msg)
    {
        Log::debug('Administration Event Called', [
            'message' => $msg
        ]);
    }

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('administration-channel'),
        ];
    }

    /**
     * Get the name the event should broadcast as
     */
    public function BroadcastAs(): string
    {
        return 'AdministrationEvent';
    }
}
