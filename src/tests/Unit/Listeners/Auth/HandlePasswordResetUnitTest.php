<?php

namespace Tests\Unit\Listeners\Auth;

use App\Listeners\Auth\HandlePasswordResetListener;
use App\Mail\Auth\PasswordChangedMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HandlePasswordResetUnitTest extends TestCase
{
    public function test_handle()
    {
        Mail::fake();

        $user = User::factory()->create();
        $event = new PasswordReset($user);
        (new HandlePasswordResetListener)->handle($event);

        Mail::assertQueued(PasswordChangedMail::class);
    }
}
