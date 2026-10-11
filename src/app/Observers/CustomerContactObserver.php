<?php

namespace App\Observers;

use App\Models\CustomerContact;
use Illuminate\Support\Facades\Log;

class CustomerContactObserver extends Observer
{
    /**
     * Handle the CustomerContact "created" event.
     */
    public function created(CustomerContact $customerContact): void
    {
        Log::info(
            'New Customer Contact created for '.$customerContact->Customer->name.
                ' by '.$this->user,
            $customerContact->toArray()
        );
    }

    /**
     * Handle the CustomerContact "updated" event.
     */
    public function updated(CustomerContact $customerContact): void
    {
        Log::info(
            'Customer Contact updated for '.$customerContact->Customer->name.
                ' by '.$this->user,
            $customerContact->toArray()
        );
    }

    /**
     * Handle the CustomerContact "deleted" event.
     */
    public function deleted(CustomerContact $customerContact): void
    {
        Log::info(
            'Customer Contact deleted for '.$customerContact->Customer->name.
                ' by '.$this->user,
            $customerContact->toArray()
        );
    }

    /**
     * Handle the CustomerContact "restored" event.
     */
    public function restored(CustomerContact $customerContact): void
    {
        Log::info(
            'Customer Contact restored for '.$customerContact->Customer->name.
                ' by '.$this->user,
            $customerContact->toArray()
        );
    }

    /**
     * Handle the CustomerContact "force deleted" event.
     */
    public function forceDeleted(CustomerContact $customerContact): void
    {
        Log::info(
            'Customer Contact trashed for '.$customerContact->Customer->name.
                ' by '.$this->user,
            $customerContact->toArray()
        );
    }
}
