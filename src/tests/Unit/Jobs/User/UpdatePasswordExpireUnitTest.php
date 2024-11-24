<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\UpdatePasswordExpireJob;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Tests\TestCase;

class UpdatePasswordExpireUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        config(['auth.passwords.settings.expire' => 0]);

        $userList = User::factory()
            ->count(10)
            ->create(['password_expires' => now()->addDays(30)]);

        $jobObj = new UpdatePasswordExpireJob;
        $jobObj->handle(new UserAdministrationService);

        foreach ($userList as $user) {
            $this->assertNull($user->fresh()->password_expires);
        }
    }
}
