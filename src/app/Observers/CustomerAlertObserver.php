<?php

namespace App\Observers;

use App\Models\CustomerAlert;
use Illuminate\Support\Facades\Log;

class CustomerAlertObserver extends Observer
{
    /**
     * Handle the CustomerAlert "created" event.
     */
    public function created(CustomerAlert $customerAlert): void
    {
        Log::info(
            'New Customer Alert created for '.$customerAlert->Customer->name.' by '.
                $this->user,
            $customerAlert->toArray()
        );
    }

    /**
     * Handle the CustomerAlert "updated" event.
     */
    public function updated(CustomerAlert $customerAlert): void
    {
        Log::info(
            'Customer Alert updated for '.$customerAlert->Customer->name.' by '.
                $this->user,
            $customerAlert->toArray()
        );
    }

    /**
     * Handle the CustomerAlert "deleted" event.
     */
    public function deleted(CustomerAlert $customerAlert): void
    {
        Log::info(
            'Customer Alert deleted for '.$customerAlert->Customer->name.' by '.
                $this->user,
            $customerAlert->toArray()
        );
    }
}
