<?php

namespace Tests\Unit\Actions\Fortify;

use App\Events\User\UserPasswordChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateUserPasswordUnitTest extends TestCase
{
    public function test_update()
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        $pass = 'S3curePassw0rd!';
        $data = [
            'current_password' => 'password',
            'password' => $pass,
            'password_confirmation' => $pass,
        ];

        // Because of the web guard, we must call this as an http request
        $this->actingAs($user)
            ->put(route('user-password.update'), $data);

        $this->assertTrue(Hash::check($pass, $user->fresh()->password));

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }
}
