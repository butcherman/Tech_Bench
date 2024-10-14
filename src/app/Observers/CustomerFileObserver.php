<?php

namespace App\Observers;

use App\Models\CustomerFile;
use Illuminate\Support\Facades\Log;

class CustomerFileObserver
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

    public function created(CustomerFile $customerFile): void
    {
        Log::info(
            'New Customer File created for '.
            $customerFile->Customer->name.' by '.$this->user,
            $customerFile->toArray()
        );
    }

    public function updated(CustomerFile $customerFile): void
    {
        Log::info(
            'Customer File Information updated by '.$this->user,
            $customerFile->toArray()
        );
    }

    public function deleted(CustomerFile $customerFile): void
    {
        Log::notice(
            'Customer File deleted for '.$customerFile->Customer->name.' by '.
                $this->user,
            $customerFile->toArray()
        );
    }

    public function restored(CustomerFile $customerFile): void
    {
        Log::info(
            'Customer File restored for '.$customerFile->Customer->name.' by '.
                $this->user,
            $customerFile->toArray()
        );
    }

    public function forceDeleted(CustomerFile $customerFile): void
    {
        Log::notice(
            'Customer File force deleted for '.$customerFile->Customer->name.
                ' by '.$this->user,
            $customerFile->toArray()
        );
    }
}
