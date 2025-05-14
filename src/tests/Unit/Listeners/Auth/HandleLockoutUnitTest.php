<?php

namespace Tests\Unit\Listeners\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleLockoutUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Log::shouldReceive('stack->notice')->once();

        $request = new Request;

        event(new Lockout($request));
    }
}
