<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerSlugChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @codeCoverageIgnore
     */
    public function __construct(public Customer $customer, public string $oldSlug) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer.'.$this->oldSlug),
        ];
    }

    /**
     * Get the name the event should broadcast as
     */
    public function broadcastAs(): string
    {
        return 'CustomerSlugChanged';
    }
}
