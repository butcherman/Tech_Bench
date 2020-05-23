<?php

namespace Tests\Feature;

use DB;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class ForgotPasswordTest extends TestCase
{
    //  Verify user can view the email reset password form
    public function test_reset_password_form()
    {
        $response = $this->get(route('password.request'));

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
        $this->assertGuest();
    }

    //  Verify user cannot view the reset email form when logged in
    public function test_reset_password_form_while_logged_in()
    {
        $user = $this->getTech();

        $response = $this->actingAs($user)->get(route('password.request'));
        $response->assertRedirect(route('dashboard'));
        $response->assertStatus(302);
    }

    //  Verify that the "Forgot Password" form creates an email with link
    public function test_submit_reset_password_form()
    {
        Notification::fake();
        $user = $this->getTech();

        $response = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('status');
        $this->assertNotNull($token = DB::table('password_resets')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    //  Verify that the "Forgot Password" form will not email if invalid email is entered
    public function test_submit_reset_password_form_invalid_email()
    {
        Notification::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'randomEmail@em.com',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('index'));
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo(factory(User::class)->make(['email' => 'randomEmail@em.com']), ResetPassword::class);
    }

    //  Test user submitting form without putting in an email address
    public function test_submit_reset_password_form_no_email()
    {
        $response = $this->post(route('password.email'), []);
        $response->assertRedirect(route('index'));
        $response->assertSessionHasErrors('email');
    }
}
