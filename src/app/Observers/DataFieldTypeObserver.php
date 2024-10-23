<?php

namespace App\Observers;

use App\Models\DataFieldType;
use Illuminate\Support\Facades\Log;

class DataFieldTypeObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

    /**
     * Handle the DataFieldType "created" event.
     */
    public function created(DataFieldType $dataFieldType): void
    {
        Log::info(
            'New Equipment Data Field created by '.$this->user,
            $dataFieldType->toArray()
        );
    }

    /**
     * Handle the DataFieldType "updated" event.
     */
    public function updated(DataFieldType $dataFieldType): void
    {
        Log::info(
            'Equipment Data Type '.$dataFieldType->name.' updated by '.$this->user,
            $dataFieldType->toArray()
        );
    }

    /**
     * Handle the DataFieldType "deleted" event.
     */
    public function deleted(DataFieldType $dataFieldType): void
    {
        Log::notice('Data Field '.$dataFieldType->name.' deleted by '.$this->user);
    }
}
