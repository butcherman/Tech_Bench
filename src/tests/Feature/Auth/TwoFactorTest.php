<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\UserVerificationCode;
use App\Notifications\User\SendAuthCode;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    /**
     * Test the 2FA Middleware
     */
    public function test_middleware()
    {
        Notification::fake();

        config(['auth.twoFa.required' => true]);

        $response = $this->actingAs($user = User::factory()->create())
            ->get(route('dashboard'));
        $response->assertStatus(302);
        $response->assertRedirect(route('2fa.show'));

        $this->assertDatabaseHas('user_verification_codes', ['user_id' => $user->user_id]);

        Notification::assertSentTo($user, SendAuthCode::class);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $response = $this->get(route('2fa.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show()
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('2fa.show'));
        $response->assertSuccessful();
    }

    public function test_get_already_verified()
    {
        $response = $this->actingAs(User::factory()->create())
            ->withSession(['2fa_verified' => true])
            ->get(route('2fa.show'));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $data = [
            'code' => '1234',
            'remember' => false,
        ];

        $response = $this->put(route('2fa.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_update()
    {
        $user = User::factory()->create();
        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '1234',
            'remember' => false,
        ];

        $response = $this->actingAs($user)->put(route('2fa.update'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_update_bad_code()
    {
        $user = User::factory()->create();
        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '4321',
            'remember' => false,
        ];

        $response = $this->actingAs($user)->put(route('2fa.update'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('code');
    }

    // TODO - Get this working...
    // public function test_update_with_remember_device()
    // {
    //     $user = User::factory()->create();
    //     UserVerificationCode::create([
    //         'user_id' => $user->user_id,
    //         'code' => 1234,
    //     ]);

    //     $data = [
    //         'code' => '1234',
    //         'remember' => true,
    //     ];

    //     $response = $this->actingAs($user)->put(route('2fa.update'), $data);
    //     $response->assertStatus(302);
    //     $response->assertRedirect(route('dashboard'));

    //     $this->assertDatabaseHas('device_tokens', ['user_id' => $user->user_id]);
    // }
}
