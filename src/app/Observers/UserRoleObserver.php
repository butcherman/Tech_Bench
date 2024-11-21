<?php

namespace App\Observers;

use App\Models\UserRole;
use Illuminate\Support\Facades\Log;

class UserRoleObserver extends Observer
{
    /**
     * Handle the UserRole "created" event.
     */
    public function created(UserRole $userRole): void
    {
        Log::info(
            'New User Role created by '.$this->user,
            $userRole->toArray()
        );
    }

    /**
     * Handle the UserRole "updated" event.
     */
    public function updated(UserRole $userRole): void
    {
        Log::info(
            'User Role updated by '.$this->user,
            $userRole->toArray()
        );
    }

    /**
     * Handle the UserRole "deleted" event.
     */
    public function deleted(UserRole $userRole): void
    {
        Log::info(
            'User Role deleted by '.$this->user,
            $userRole->toArray()
        );
    }
}
