<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetUserPasswordUnitTest extends TestCase
{
    public function test_reset(): void
    {
        $pass = 'S3curePassw0rd!';
        $user = User::factory()->create();
        $data = [
            'password' => $pass,
            'password_confirmation' => $pass,
        ];

        (new ResetUserPassword)->reset($user, $data);

        $this->assertTrue(Hash::check($pass, $user->fresh()->password));
    }
}
