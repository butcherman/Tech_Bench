<?php

namespace Tests\Feature;

use DB;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Auth\Notifications\ResetPassword;
// use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    //  Verify user can view the email reset password form
    public function test_reset_password_form()
    {
        $response = $this->get('/password/reset');

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }

    //  Verify user cannot view the reset email form when logged in
    public function test_reset_password_form_while_logged_in()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/password/reset');
        $response->assertRedirect(route('dashboard'));
        $response->assertStatus(302);
    }

    //  Verify that the "Forgot Password" form creates an email with link
    public function test_submit_reset_password_form()
    {
        Notification::fake();
        $user = factory(User::class)->create([
            'email' => 'random@example.com',
        ]);

        $response = $this->post('/password/email', [
            'email' => 'random@example.com',
        ]);

        $this->assertNotNull($token = DB::table('password_resets')->first());
        Notification::assertSentTo($user, ResetPassword::class, function ($notification, $channels) use ($token) {
            return Hash::check($notification->token, $token->token) === true;
        });
    }

    //  Verify that the "Forgot Password" form will not email if invalid email is entered
    public function test_submit_reset_password_form_invalid_email()
    {
        Notification::fake();

        $response = $this->from('/password/reset')->post('/password/email', [
            'email' => 'randomEmail@em.com',
        ]);
        $response->assertRedirect('/password/reset');
        $response->assertSessionHasErrors('email');
        Notification::assertNotSentTo(factory(User::class)->make(['email' => 'nobody@example.com']), ResetPassword::class);
    }

    //  Test user submitting form without putting in an email address
    public function test_submit_reset_password_form_no_email()
    {
        $response = $this->from('/password/reset')->post('/password/email', []);
        $response->assertRedirect('/password/reset');
        $response->assertSessionHasErrors('email');
    }
}
