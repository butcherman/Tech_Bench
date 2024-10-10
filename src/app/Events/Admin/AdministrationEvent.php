<?php

namespace App\Events\Admin;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdministrationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when an admin needs real time update of a working job
     */
    public function __construct(public string $msg)
    {
        Log::debug('Administration Event Called', [
            'message' => $msg,
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
