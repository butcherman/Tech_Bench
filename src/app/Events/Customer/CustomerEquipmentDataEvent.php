<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentDataEvent // implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered when customer equipment data is modified
     */
    public function __construct(public Customer $customer)
    {
        // TODO - Add additional information to broadcast to user
        Log::debug('Customer Equipment Data Event called', [
            'customer' => $this->customer->toArray(),
        ]);
    }

    /**
     * Get the channels the event should broadcast on
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Customer Equipment Data Event on channel `customer.' .
            $this->customer->slug . '`');

        return [
            new PrivateChannel('customer.' . $this->customer->slug),
        ];
    }
}
