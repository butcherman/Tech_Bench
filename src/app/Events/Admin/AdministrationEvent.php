<?php

namespace App\Events\Admin;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Administration Event is called when a real-time event happens that needs
| to update a system administrator.  Used for long running processes.
|-------------------------------------------------------------------------------
*/

class AdministrationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
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
