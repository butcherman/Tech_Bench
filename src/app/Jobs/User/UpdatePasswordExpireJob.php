<?php

namespace App\Jobs\User;

use App\Services\User\UserAdministrationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdatePasswordExpireJob implements ShouldQueue
{
    use Queueable;

    /**
     * Update the password expires field for all users in the database
     */
    public function handle(UserAdministrationService $svc): void
    {
        $userList = $svc->getAllUsers();

        foreach ($userList as $user) {
            $svc->updatePasswordExpire($user);
        }
    }
}
