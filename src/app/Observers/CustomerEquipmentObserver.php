<?php

namespace App\Observers;

use App\Models\CustomerEquipment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            default => request()->ip(),
        };
    }

    public function created(CustomerEquipment $equipment): void
    {
        Log::info(
            'New Customer Equipment added to '.$equipment->Customer->name.
                ' by '.$this->user,
            $equipment->toArray()
        );
    }

    public function deleted(CustomerEquipment $equipment): void
    {
        Log::notice(
            'Customer Equipment Disabled for '.$equipment->Customer->name.
                ' by '.$this->user,
            $equipment->toArray()
        );
    }

    public function restored(CustomerEquipment $equipment): void
    {
        Log::info(
            'Customer Equipment restored for '.$equipment->Customer->name.' by '.
                $this->user,
            $equipment->toArray()
        );
    }

    public function forceDeleted(CustomerEquipment $equipment): void
    {
        Log::notice(
            'Customer Equipment force deleted for '.$equipment->Customer->name.
                ' by '.$this->user,
            $equipment->toArray()
        );
    }
}
