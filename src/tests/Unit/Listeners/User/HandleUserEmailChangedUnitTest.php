<?php

namespace Tests\Unit\Listeners\User;

use App\Events\User\UserEmailChangedEvent;
use App\Mail\User\EmailChangedMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleUserEmailChangedUnitTest extends TestCase
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

        event(new UserEmailChangedEvent(User::find(1), 'oldem@noem.com'));

        Mail::assertQueued(EmailChangedMail::class);
    }
}
