<?php

namespace App\Observers;

use App\Events\User\UserEmailChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            request()->user() !== null => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Log::info('New User Profile created by '.$this->user, $user->toArray());
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        Log::info(
            'User profile for '.$user->full_name.' updated by '.$this->user, [
                'user_data' => $user->toArray(),
            ]);

        if ($user->wasChanged('email')) {
            event(
                new UserEmailChangedEvent($user, $user->getOriginal('email'))
            );
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    // public function deleted(User $user): void
    // {
    //     //
    //     Log::info('deleted');
    // }

    /**
     * Handle the User "restored" event.
     */
    // public function restored(User $user): void
    // {
    //     //
    //     Log::info('restored');
    // }

    /**
     * Handle the User "force deleted" event.
     */
    // public function forceDeleted(User $user): void
    // {
    //     //
    //     Log::info('force deleted');
    // }
}
