<?php

namespace Tests\Unit\Listeners\Auth;

use App\Mail\Auth\PasswordChangedMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandlePasswordResetUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Mail::fake();
        Log::shouldReceive('stack->notice')->once();

        event(new PasswordReset(User::find(1)));

        Mail::assertQueued(PasswordChangedMail::class);
    }
}
