<?php

namespace Tests\Unit\Listeners\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleLoginUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $user = User::find(1);

        Log::shouldReceive('stack->info')->once();

        event(new Login('web', $user, false));

        $this->assertDatabaseHas('user_logins', [
            'user_id' => 1,
        ]);
    }
}
