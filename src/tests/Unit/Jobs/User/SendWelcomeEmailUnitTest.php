<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\SendWelcomeEmailJob;
use App\Mail\User\UserWelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWelcomeEmailUnitTest extends TestCase
{
    public function test_handle(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $job = new SendWelcomeEmailJob($user);
        $job->handle();

        $this->assertDatabaseHas('user_initializes', [
            'username' => $user->username,
        ]);

        Mail::assertQueued(UserWelcomeEmail::class);
    }
}
