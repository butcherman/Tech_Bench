<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerEquipmentUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    public $equipment;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerEquipment $equipment)
    {
        $this->customer = $customer;
        $this->equipment = $equipment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
