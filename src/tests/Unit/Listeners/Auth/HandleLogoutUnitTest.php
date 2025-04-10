<?php

namespace Tests\Unit\Listeners\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleLogoutUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Log::shouldReceive('stack->info')->once();

        event(new Logout('web', User::find(1)));
    }
}
