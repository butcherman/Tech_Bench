<?php

namespace Tests\Unit\Listeners\User;

use App\Events\User\UserEmailChangedEvent;
use App\Listeners\User\HandleUserEmailChangedListener;
use App\Mail\User\EmailChangedMail;
use App\Models\User;
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

        $user = User::factory()->create();
        $event = new UserEmailChangedEvent($user, 'original@em.com');
        (new HandleUserEmailChangedListener)->handle($event);

        Mail::assertQueued(EmailChangedMail::class);
    }
}
