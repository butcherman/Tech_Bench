<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\UpdatePasswordExpireJob;
use App\Models\User;
use App\Services\User\UserAdministrationService;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdatePasswordExpireJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        User::factory()->count(5)->create();
        $userCount = User::all()->count();

        $this->mock(
            UserAdministrationService::class,
            function (MockInterface $mock) use ($userCount) {
                $mock->shouldReceive('getAllUsers')
                    ->once()
                    ->andReturn(User::all());
                $mock->shouldReceive('updatePasswordExpire')
                    ->times($userCount)
                    ->with(User::class);
            });

        UpdatePasswordExpireJob::dispatch();
    }
}
