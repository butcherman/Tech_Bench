<?php

namespace Tests\Unit\Listeners\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleFailedLoginUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Log::shouldReceive('stack->warning')->once();

        event(new Failed('web', null, ['username' => 'invalid']));
    }
}
