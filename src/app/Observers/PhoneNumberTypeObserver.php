<?php

namespace App\Observers;

use App\Models\PhoneNumberType;
use App\Service\Cache;
use Illuminate\Support\Facades\Log;

class PhoneNumberTypeObserver
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
     * Handle the PhoneNumberType "created" event.
     */
    public function created(PhoneNumberType $phoneNumberType): void
    {
        Cache::clearCache('phoneTypes');

        Log::info(
            'New Phone Number Type created by '.$this->user,
            $phoneNumberType->toArray()
        );
    }

    /**
     * Handle the PhoneNumberType "updated" event.
     */
    public function updated(PhoneNumberType $phoneNumberType): void
    {
        Cache::clearCache('phoneTypes');

        Log::info(
            'Phone Number Type updated by '.$this->user,
            $phoneNumberType->toArray()
        );
    }

    /**
     * Handle the PhoneNumberType "deleted" event.
     */
    public function deleted(PhoneNumberType $phoneNumberType): void
    {
        Cache::clearCache('phoneTypes');

        Log::notice(
            'Phone Number Type deleted by '.$this->user,
            $phoneNumberType->toArray()
        );
    }
}
