<?php

namespace App\Observers;

use App\Models\CustomerNote;
use Illuminate\Support\Facades\Log;

class CustomerNoteObserver extends Observer
{
    /**
     * Handle the CustomerNote "created" event.
     */
    public function created(CustomerNote $customerNote): void
    {
        Log::info(
            'New Customer Note created for '.$customerNote->Customer->name.
                ' by '.$this->user,
            $customerNote->toArray()
        );
    }

    /**
     * Handle the CustomerNote "updated" event.
     */
    public function updated(CustomerNote $customerNote): void
    {
        Log::info(
            'Customer Note updated for '.$customerNote->Customer->name.
                ' by '.$this->user,
            $customerNote->toArray()
        );
    }

    /**
     * Handle the CustomerNote "deleted" event.
     */
    public function deleted(CustomerNote $customerNote): void
    {
        Log::info(
            'Customer Note deleted for '.$customerNote->Customer->name.
                ' by '.$this->user,
            $customerNote->toArray()
        );
    }

    /**
     * Handle the CustomerNote "restored" event.
     */
    public function restored(CustomerNote $customerNote): void
    {
        Log::info(
            'Customer Note restored for '.$customerNote->Customer->name.
                ' by '.$this->user,
            $customerNote->toArray()
        );
    }

    /**
     * Handle the CustomerNote "force deleted" event.
     */
    public function forceDeleted(CustomerNote $customerNote): void
    {
        Log::info(
            'Customer Note trashed for '.$customerNote->Customer->name.
                ' by '.$this->user,
            $customerNote->toArray()
        );
    }
}
