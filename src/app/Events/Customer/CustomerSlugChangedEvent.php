<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerSlugChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    /*
    |---------------------------------------------------------------------------
    | This event is triggered when the unique slug for a customer URL is
    | modified.  This means that anyone viewing the same customer is now
    | on an invalid URL and any clicked links will result in a 404 error.
    |---------------------------------------------------------------------------
    */
    public function __construct(public Customer $customer, public string $oldSlug, public string $newSlug) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer.'.$this->oldSlug),
        ];
    }

    /**
     * Get the name the event should broadcast as.
     *
     * @codeCoverageIgnore
     */
    public function broadcastAs(): string
    {
        return 'CustomerSlugChanged';
    }
}
