<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;    
    
    //  Verify the user can view the reset form 
    public function testUserCanViewAPasswordResetForm()
    {
        $user = factory(User::class)->create();
        $token = Password::broker()->createToken($user);
        
        $response = $this->get(route('password.reset', $token));
                               
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }
    
    //  Verify the user cannot view the reset form if already logged in
    public function testUserCannotViewAPasswordResetFormWhenAuthenticated()
    {
        $user = factory(User::class)->create();
        $token = Password::broker()->createToken($user);
        
        $response = $this->actingAs($user)->get(route('password.reset', $token));
        
        $response->assertRedirect(route('dashboard'));
    }
    
    //  Verify the user can reset their password if the token is valid
    public function testUserCanResetPasswordWithValidToken()
    {
        Event::fake();
        $user = factory(User::class)->create();
        
        $response = $this->post('/password/reset', [
            'token' => Password::broker()->createToken($user),
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        
        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });
    }
    
    //  Verify user cannot reset a password with an Invalid Reset token
    public function testUserCannotResetPasswordWithInvalidToken()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('old-password'),
        ]);
        
        $response = $this->from(route('password.reset', 'InvalidToken'))->post('/password/reset', [
            'token' => 'InvalidToken',
            'email' => $user->email,
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        
        $response->assertRedirect(route('password.reset', 'InvalidToken'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
    
    //  Verify user cannot reset to blank password
    public function testUserCannotResetPasswordWithoutProvidingANewPassword()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);
        
        $response = $this->from(route('password.reset', $token))->post('/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);
        $response->assertRedirect(route('password.reset', $token));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
    
    //  Verify user cannot reset password without providing valid email address
    public function testUserCannotResetPasswordWithoutProvidingAnEmail()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('old-password'),
        ]);
        $token = Password::broker()->createToken($user);
        
        $response = $this->from(route('password.reset', $token))->post('/password/reset', [
            'token' => $token,
            'email' => '',
            'password' => 'new-awesome-password',
            'password_confirmation' => 'new-awesome-password',
        ]);
        $response->assertRedirect(route('password.reset', $token));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}
