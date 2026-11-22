<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\ResetUserPassword;
use App\Events\User\UserPasswordChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserPasswordUnitTest extends TestCase
{
    public function test_reset(): void
    {
        Event::fake();

        $pass = 'S3curePassw0rd!';
        $user = User::factory()->create();
        $data = [
            'password' => $pass,
            'password_confirmation' => $pass,
        ];

        (new ResetUserPassword)->reset($user, $data);

        $this->assertTrue(Hash::check($pass, $user->fresh()->password));

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }
}
