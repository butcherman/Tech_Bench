<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\User;
use App\Models\UserVerificationCode;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class TwoFactorTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test the 2FA Middleware
    |---------------------------------------------------------------------------
    */
    public function test_middleware(): void
    {

        Mail::fake();

        config(['auth.twoFa.required' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('2fa.show'));

        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $user->user_id,
        ]);

        Mail::assertQueued(VerificationCodeMail::class);
    }

    public function test_middleware_new_code(): void
    {
        Mail::fake();

        config(['auth.twoFa.required' => true]);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 9876,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('2fa.show'));

        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $user->user_id,
        ]);

        $this->assertDatabaseMissing('user_verification_codes', [
            'user_id' => $user->user_id,
            'code' => 9876,
        ]);

        Mail::assertQueued(VerificationCodeMail::class);
    }

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_guest(): void
    {
        $response = $this->get(route('2fa.show'));
        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_show(): void
    {
        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->actingAs($user)
            ->get(route('2fa.show'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorAuth')
                    ->has('allow-remember')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_update_guest(): void
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

    public function test_update(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '1234',
            'remember' => false,
        ];

        $response = $this->actingAs($user)->put(route('2fa.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(['2fa_verified' => true])
            ->assertRedirect(route('dashboard'));
    }

    public function test_update_bad_code(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '4321',
            'remember' => false,
        ];

        $response = $this->actingAs($user)->put(route('2fa.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrors('code');
    }

    public function test_update_expired_code(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();
        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $data = [
            'code' => '1234',
            'remember' => false,
        ];

        $this->travel(45)->minutes();

        $response = $this->actingAs($user)->put(route('2fa.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHasErrors('code');
    }

    public function test_update_with_remember_device(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->createQuietly();

        UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => 1234,
        ]);

        $httpUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36';

        $data = [
            'code' => '1234',
            'remember' => true,
        ];

        $response = $this->actingAs($user)
            ->withHeaders(['User-Agent' => $httpUserAgent])
            ->put(route('2fa.update'), $data);

        $response->assertStatus(302)
            ->assertSessionHas(['2fa_verified' => true])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('device_tokens', ['user_id' => $user->user_id]);
    }
}
