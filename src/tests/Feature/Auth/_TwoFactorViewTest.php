<?php

namespace Tests\Feature\Auth;

use App\Mail\Auth\VerificationCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class _TwoFactorViewTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test the different views brought on by the Two Factor logic
    |---------------------------------------------------------------------------
    */

    public function test_two_fa_view_with_email(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => false]);
        config(['auth.twoFa.allow_via_email' => true]);

        Mail::fake();

        $user = User::factory()->create();

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->get(route('two-factor.login'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorAuth')
                    ->has('allow-remember')
                    ->has('via')
            );

        Mail::assertQueued(VerificationCodeMail::class);
    }

    public function test_two_fa_view_with_email_preference(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => true]);

        Mail::fake();

        $user = User::factory()->create(['two_factor_via' => 'email']);

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->get(route('two-factor.login'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorAuth')
                    ->has('allow-remember')
                    ->has('via')
            );

        Mail::assertQueued(VerificationCodeMail::class);
    }

    public function test_two_fa_view_with_app(): void
    {
        config(['auth.twoFa.required' => true]);
        config(['auth.twoFa.allow_save_device' => true]);
        config(['auth.twoFa.allow_via_authenticator' => true]);
        config(['auth.twoFa.allow_via_email' => false]);

        Mail::fake();

        $user = User::factory()->create();

        $response = $this->withSession(['login' => [
            'id' => $user->user_id,
            'remember' => false,
        ]])->get(route('two-factor.login'));

        $response->assertSuccessful()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Auth/TwoFactorAuth')
                    ->has('allow-remember')
                    ->has('via')
            );

        Mail::assertNotQueued(VerificationCodeMail::class);
    }
}
