<?php

namespace Tests\Unit\Listeners\User;

use App\Events\User\UserPasswordChangedEvent;
use App\Listeners\User\HandleUserPasswordChangedListener;
use App\Mail\Auth\PasswordChangedMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandleUserPasswordChangedUnitTest extends TestCase
{
    public function test_handle()
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = new UserPasswordChangedEvent($user);
        (new HandleUserPasswordChangedListener)->handle($event);

        Mail::assertQueued(PasswordChangedMail::class);
    }
}
