<?php

namespace Tests\Feature;

use DB;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;
    
    //  Verify user can view the email reset password form
    public function testResetEmailForm()
    {
        $response = $this->get('/password/reset');
        
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');
    }
    
    //  Verify user cannot view the reset email form when logged in
    public function testResetEmailFormWhenLoggedIn()
    {
        $user = factory(User::class)->create();
        
        $response = $this->actingAs($user)->get('/password/reset');
        $response->assertRedirect(route('dashboard'));
        $response->assertStatus(302);
    }
    
    //  Verify that the "Forgot Password" form creates an email with link
    public function testSubmitForgotPasswordForm()
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
    public function testSubmitForgotPasswordFormInvalidEmail()
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
    public function testEmailIsRequired()
    {
        $response = $this->from('/password/reset')->post('/password/email', []);
        $response->assertRedirect('/password/reset');
        $response->assertSessionHasErrors('email');
    }
}
