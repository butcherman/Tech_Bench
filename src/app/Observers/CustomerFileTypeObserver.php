<?php

namespace App\Observers;

use App\Models\CustomerFileType;
use App\Service\Cache;
use Illuminate\Support\Facades\Log;

class CustomerFileTypeObserver
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
     * Handle the CustomerFileType "created" event.
     */
    public function created(CustomerFileType $customerFileType): void
    {
        Cache::clearCache(['fileTypes']);

        Log::info(
            'New Customer File Type created by '.$this->user,
            $customerFileType->toArray()
        );
    }

    /**
     * Handle the CustomerFileType "updated" event.
     */
    public function updated(CustomerFileType $customerFileType): void
    {
        Cache::clearCache(['fileTypes']);

        Log::info(
            'Customer File Type Updated by '.$this->user,
            $customerFileType->toArray()
        );
    }

    /**
     * Handle the CustomerFileType "deleted" event.
     */
    public function deleted(CustomerFileType $customerFileType): void
    {
        Cache::clearCache(['fileTypes']);

        Log::info(
            'Customer File Type Deleted by '.$this->user,
            $customerFileType->toArray()
        );
    }
}
