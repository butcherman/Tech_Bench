<?php

namespace App\Observers;

use App\Events\File\FileUploadDeletedEvent;
use App\Models\CustomerFile;
use Illuminate\Support\Facades\Log;

class CustomerFileObserver extends Observer
{
    /**
     * Handle the CustomerFile "created" event.
     */
    public function created(CustomerFile $customerFile): void
    {
        Log::info(
            'New Customer File uploaded for '.$customerFile->Customer->name.
                ' by '.$this->user,
            $customerFile->toArray()
        );
    }

    /**
     * Handle the CustomerFile "updated" event.
     */
    public function updated(CustomerFile $customerFile): void
    {
        Log::info(
            'Customer File details updated for '.$customerFile->Customer->name.
                ' by '.$this->user,
            $customerFile->toArray()
        );
    }

    /**
     * Handle the CustomerFile "deleted" event.
     */
    public function deleted(CustomerFile $customerFile): void
    {
        Log::info(
            'Customer File deleted for '.$customerFile->Customer->name.
                ' by '.$this->user,
            $customerFile->toArray()
        );
    }

    /**
     * Handle the CustomerFile "restored" event.
     */
    public function restored(CustomerFile $customerFile): void
    {
        Log::info(
            'Customer File deleted for '.$customerFile->Customer->name.
                ' by '.$this->user,
            $customerFile->toArray()
        );
    }

    /**
     * Handle the CustomerFile "force deleted" event.
     */
    public function forceDeleted(CustomerFile $customerFile): void
    {
        event(new FileUploadDeletedEvent($customerFile->FileUpload));

        Log::info(
            'Customer File deleted for '.$customerFile->Customer->name.
                ' by '.$this->user,
            $customerFile->toArray()
        );
    }
}
