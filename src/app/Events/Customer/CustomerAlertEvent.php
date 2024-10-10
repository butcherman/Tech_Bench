<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Event is triggered when a Customer Alert is Created, Updated, or Destroyed
     */
    public function __construct(public Customer $customer)
    {
        Log::debug('Customer Alert Event called', [
            'customer' => $customer->toArray(),
        ]);
    }

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer.'.$this->customer->slug),
        ];
    }

    /**
     * Send all of the existing customer alerts in the broadcast message
     */
    public function broadcastWith(): array
    {
        return $this->customer->CustomerAlert->toArray();
    }
}
