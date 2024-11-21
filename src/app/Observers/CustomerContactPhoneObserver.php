<?php

namespace App\Observers;

use App\Models\CustomerContactPhone;
use Illuminate\Support\Facades\Log;

class CustomerContactPhoneObserver extends Observer
{
    /**
     * Handle the CustomerContactPhone "created" event.
     */
    public function created(CustomerContactPhone $customerContactPhone): void
    {
        Log::info(
            'New Customer Contact Phone Type created by '.$this->user,
            $customerContactPhone->toArray()
        );
    }

    /**
     * Handle the CustomerContactPhone "updated" event.
     */
    public function updated(CustomerContactPhone $customerContactPhone): void
    {
        Log::info(
            'Customer Contact Phone Type updated by '.$this->user,
            $customerContactPhone->toArray()
        );
    }

    /**
     * Handle the CustomerContactPhone "deleted" event.
     */
    public function deleted(CustomerContactPhone $customerContactPhone): void
    {
        Log::info(
            'Customer Contact Phone Type deleted by '.$this->user,
            $customerContactPhone->toArray()
        );
    }
}
