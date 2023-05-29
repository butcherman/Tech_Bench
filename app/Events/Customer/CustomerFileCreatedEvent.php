<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerFile;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerFileCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public $file;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerFile $file, User $user)
    {
        $this->customer = $customer;
        $this->file = $file;
        $this->user = $user;
    }
}
