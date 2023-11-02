<?php

namespace App\Events\Admin\Backup;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * When running a backup, events are generated
 * This event will broadcast those events so that anyone monitoring can see
 * the current status of the backup
 *
 * @codeCoverageIgnore
 */
class BroadcastBackupStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $message, public bool $completed = false)
    {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('process.backup');
    }

    public function broadcastAs(): string
    {
        return 'BroadcastBackupStatus';
    }
}
