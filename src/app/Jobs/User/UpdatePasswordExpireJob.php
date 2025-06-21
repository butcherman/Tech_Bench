<?php

namespace App\Jobs\User;

use App\Services\User\UserAdministrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/*
|-------------------------------------------------------------------------------
| Modify the password_expires field for all users with the new value.
|-------------------------------------------------------------------------------
*/

class UpdatePasswordExpireJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
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
