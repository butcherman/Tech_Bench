<?php

namespace App\Observers;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class CustomerObserver extends Observer
{
    public function created(Customer $customer): void
    {
        Log::info('New Customer created by '.$this->user, $customer->toArray());
    }

    public function updated(Customer $customer): void
    {
        Log::info(
            'Customer information updated for '.
                $customer->name.' by '.$this->user,
            $customer->toArray()
        );
    }

    public function deleted(Customer $customer): void
    {
        Log::alert(
            'Customer '.$customer->name.' has been disabled by '.$this->user
        );
    }

    public function restored(Customer $customer): void
    {
        Log::notice(
            'Customer '.$customer->name.' has been restored by '.$this->user
        );
    }

    public function forceDeleted(Customer $customer): void
    {
        Log::warning(
            'Customer '.$customer->name.' has been permanently removed by '.
                $this->user
        );
    }
}
