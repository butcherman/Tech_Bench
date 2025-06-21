<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\UpdatePasswordExpireJob;
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
        $this->partialMock(UserAdministrationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('resetAllPasswordExpire')->once();
        });

        UpdatePasswordExpireJob::dispatch();
    }
}
