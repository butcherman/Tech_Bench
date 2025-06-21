<?php

namespace Tests\Unit\Listeners\User;

use App\Events\User\UserPasswordChangedEvent;
use App\Mail\Auth\PasswordChangedMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleUserPasswordChangedUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Mail::fake();
        Log::shouldReceive('stack->info')->once();

        event(new UserPasswordChangedEvent(User::find(1)));

        Mail::assertQueued(PasswordChangedMail::class);
    }
}
