<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Notifications\User\PasswordChangedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    //  Change a users password as guest
    public function test_change_password_guest()
    {
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3wSecurePa33!',
            'password_confirmed' => $pass,
        ];

        $response = $this->put(route('user-password.update'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_change_password()
    {
        Notification::fake();

        $user = User::factory()->create();
        $data = [
            'current_password' => 'password',
            'password' => $pass = 'N3wSecurePa33!',
            'password_confirmation' => $pass,
        ];

        $response = $this->actingAs($user)->put(route('user-password.update'), $data);

        $response->assertStatus(302);
        // $response->assertSessionHas('status', __('passwords.reset'));
        Notification::assertSentTo($user, PasswordChangedNotification::class);
    }
}
