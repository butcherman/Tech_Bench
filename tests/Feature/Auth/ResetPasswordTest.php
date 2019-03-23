<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    
    
    
    //  Verify the user can view the reset form 
    public function testUserCanViewAPasswordResetForm()
    {
        $user = User::find(2);
        $token = Password::broker()->createToken($user);
        
        $response = $this->get(route('password.reset', $token));
                               
        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }
    
    //  Verify the user cannot view the reset form if already logged in
    public function testUserCannotViewAPasswordResetFormWhenAuthenticated()
    {
        $user = User::find(2);
        $token = Password::broker()->createToken($user);
        
        $response = $this->actingAs($user)->get(route('password.reset', $token));
        
        $response->assertRedirect(route('dashboard'));
    }
    
    //  Verify the user can reset their password if the token is valid
//    public function testUserCanResetPasswordWithValidToken()
//    {
//        Event::fake();
//        $user = factory(User::class)->create();
//        $response = $this->post($this->passwordResetPostRoute(), [
//            'token' => $this->getValidToken($user),
//            'email' => $user->email,
//            'password' => 'new-awesome-password',
//            'password_confirmation' => 'new-awesome-password',
//        ]);
//        $response->assertRedirect($this->successfulPasswordResetRoute());
//        $this->assertEquals($user->email, $user->fresh()->email);
//        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
//        $this->assertAuthenticatedAs($user);
//        Event::assertDispatched(PasswordReset::class, function ($e) use ($user) {
//            return $e->user->id === $user->id;
//        });
//    }
    
//    public function testUserCannotResetPasswordWithInvalidToken()
//    {
//        $user = factory(User::class)->create([
//            'password' => bcrypt('old-password'),
//        ]);
//        $response = $this->from($this->passwordResetGetRoute($this->getInvalidToken()))->post($this->passwordResetPostRoute(), [
//            'token' => $this->getInvalidToken(),
//            'email' => $user->email,
//            'password' => 'new-awesome-password',
//            'password_confirmation' => 'new-awesome-password',
//        ]);
//        $response->assertRedirect($this->passwordResetGetRoute($this->getInvalidToken()));
//        $this->assertEquals($user->email, $user->fresh()->email);
//        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
//        $this->assertGuest();
//    }
//    public function testUserCannotResetPasswordWithoutProvidingANewPassword()
//    {
//        $user = factory(User::class)->create([
//            'password' => bcrypt('old-password'),
//        ]);
//        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
//            'token' => $token,
//            'email' => $user->email,
//            'password' => '',
//            'password_confirmation' => '',
//        ]);
//        $response->assertRedirect($this->passwordResetGetRoute($token));
//        $response->assertSessionHasErrors('password');
//        $this->assertTrue(session()->hasOldInput('email'));
//        $this->assertFalse(session()->hasOldInput('password'));
//        $this->assertEquals($user->email, $user->fresh()->email);
//        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
//        $this->assertGuest();
//    }
//    public function testUserCannotResetPasswordWithoutProvidingAnEmail()
//    {
//        $user = factory(User::class)->create([
//            'password' => bcrypt('old-password'),
//        ]);
//        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
//            'token' => $token,
//            'email' => '',
//            'password' => 'new-awesome-password',
//            'password_confirmation' => 'new-awesome-password',
//        ]);
//        $response->assertRedirect($this->passwordResetGetRoute($token));
//        $response->assertSessionHasErrors('email');
//        $this->assertFalse(session()->hasOldInput('password'));
//        $this->assertEquals($user->email, $user->fresh()->email);
//        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
//        $this->assertGuest();
//    }
//    
    
    
    
    
    
    
    
    
    
    
    
}
