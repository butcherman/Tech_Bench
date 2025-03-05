<?php

namespace App\Observers;

use App\Facades\CacheData;
use App\Models\DataFieldType;
use Illuminate\Support\Facades\Log;

class DataFieldTypeObserver extends Observer
{
    /**
     * Handle the DataFieldType "created" event.
     */
    public function created(DataFieldType $dataFieldType): void
    {
        CacheData::clearCache('dataFieldTypes');

        Log::info(
            'New Data Field Type for Customer Equipment created by '.$this->user,
            $dataFieldType->toArray()
        );
    }

    /**
     * Handle the DataFieldType "updated" event.
     */
    public function updated(DataFieldType $dataFieldType): void
    {
        CacheData::clearCache('dataFieldTypes');

        Log::info(
            'Data Field Type for Customer Equipment updated by '.$this->user,
            $dataFieldType->toArray()
        );
    }

    /**
     * Handle the DataFieldType "deleted" event.
     */
    public function deleted(DataFieldType $dataFieldType): void
    {
        CacheData::clearCache('dataFieldTypes');

        Log::info(
            'Data Field Type for Customer Equipment deleted by '.$this->user,
            $dataFieldType->toArray()
        );
    }
}
