<?php

namespace App\Observers;

use App\Models\CustomerFileType;
use Illuminate\Support\Facades\Log;

class CustomerFileTypeObserver extends Observer
{
    /**
     * Handle the CustomerFileType "created" event.
     */
    public function created(CustomerFileType $customerFileType): void
    {
        Log::info(
            'New File Upload Type created by '.$this->user,
            $customerFileType->toArray()
        );
    }

    /**
     * Handle the CustomerFileType "updated" event.
     */
    public function updated(CustomerFileType $customerFileType): void
    {
        Log::info(
            'File Upload Type updated by '.$this->user,
            $customerFileType->toArray()
        );
    }

    /**
     * Handle the CustomerFileType "deleted" event.
     */
    public function deleted(CustomerFileType $customerFileType): void
    {
        Log::info(
            'File Upload Type deleted by '.$this->user,
            $customerFileType->toArray()
        );
    }
}
