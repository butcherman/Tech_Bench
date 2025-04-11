<?php

namespace App\Jobs\User;

use App\Services\User\UserAdministrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdatePasswordExpireJob implements ShouldQueue
{
    use Queueable;

    /*
    |---------------------------------------------------------------------------
    | Modify the password_expires field for all users with the new value.
    |---------------------------------------------------------------------------
    */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(UserAdministrationService $svc): void
    {
        $svc->resetAllPasswordExpire();
    }
}
