<?php

namespace Tests\Unit\Jobs\User;

use App\Jobs\User\SendWelcomeEmailJob;
use App\Mail\User\UserWelcomeEmail;
use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendWelcomeEmailJobUnitTest extends TestCase
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

        SendWelcomeEmailJob::dispatch($user);

        $this->assertDatabaseHas('user_initializes', [
            'username' => $user->username,
        ]);

        Mail::assertQueued(UserWelcomeEmail::class);
    }

    public function test_handle_existing_link(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $oldLink = UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);

        SendWelcomeEmailJob::dispatch($user);

        $this->assertDatabaseHas('user_initializes', [
            'username' => $user->username,
        ]);

        // Verify token was changed
        $this->assertDatabaseMissing(
            'user_initializes',
            $oldLink->only(['username', 'token'])
        );

        Mail::assertQueued(UserWelcomeEmail::class);
    }
}
