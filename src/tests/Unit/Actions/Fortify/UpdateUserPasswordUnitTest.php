<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\UpdateUserPassword;
use App\Events\User\UserPasswordChangedEvent;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateUserPasswordUnitTest extends TestCase
{
    public function test_reset(): void
    {
        Event::fake();

        $pass = 'S3curePassw0rd!';
        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass,
            'password_confirmation' => $pass,
        ];

        Validator::shouldReceive('make')->once()->andReturnSelf();
        Validator::shouldReceive('validateWithBag')->once()->andReturnTrue();

        (new UpdateUserPassword)->update($user, $data);

        $this->assertTrue(Hash::check($pass, $user->fresh()->password));

        Event::assertDispatched(UserPasswordChangedEvent::class);
    }
}
