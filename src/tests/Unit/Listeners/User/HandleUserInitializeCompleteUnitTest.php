<?php

namespace Tests\Unit\Listeners\User;

use App\Events\User\UserInitializeComplete;
use App\Listeners\User\HandleUserInitializeCompleteListener;
use App\Models\UserInitialize;
use Tests\TestCase;

class HandleUserInitializeCompleteUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $token = UserInitialize::factory()->create();

        $event = new UserInitializeComplete($token);
        $testObj = new HandleUserInitializeCompleteListener;
        $testObj->handle($event);

        $this->assertDatabaseMissing(
            'user_initializes',
            $token->only(['username', 'token'])
        );
    }
}
