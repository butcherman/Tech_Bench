<?php

namespace Tests\Feature\_Console\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ClearValidationCodesTest extends TestCase
{
    public function test_command()
    {
        Mail::fake();

        $userList = User::factory()->count(5)->create();
        $userList->each(function ($user) {
            $user->generateVerificationCode();
            $this->travel(5)->minutes();
        });

        $this->artisan('auth:clear-validation-codes')
            ->expectsOutput('Cleared 2 verification codes.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('user_verification_codes', [
            'user_id' => $userList[0]->user_id,
        ]);
        $this->assertDatabaseMissing('user_verification_codes', [
            'user_id' => $userList[1]->user_id,
        ]);
        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $userList[2]->user_id,
        ]);
        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $userList[3]->user_id,
        ]);
        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $userList[4]->user_id,
        ]);
    }
}
