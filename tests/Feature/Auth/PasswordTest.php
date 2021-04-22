<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;

use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class PasswordTest extends TestCase
{
        //  Verify user can view the email reset password form
        public function test_reset_password_form()
        {
            $response = $this->get(route('password.forgot'));

            $response->assertSuccessful();
            $this->assertGuest();
        }

        //  Verify user cannot view the reset email form when logged in
        public function test_reset_password_form_while_logged_in()
        {
            $user = User::factory()->create();

            $response = $this->actingAs($user)->get(route('password.forgot'));
            $response->assertRedirect(route('dashboard'));
            $response->assertStatus(302);
        }

        //  Verify that the "Forgot Password" form creates an email with link
        public function test_submit_reset_password_form()
        {
            Notification::fake();
            $user = User::factory()->create();

            $response = $this->post(route('password.store'), [
                'email' => $user->email,
            ]);

            $response->assertStatus(302);
            $response->assertSessionHas('message');
            $this->assertNotNull($token = DB::table('password_resets')->first());
        }

        //  Verify that the "Forgot Password" form will not email if invalid email is entered
        public function test_submit_reset_password_form_invalid_email()
        {
            Notification::fake();

            $response = $this->post(route('password.store'), [
                'email' => 'randomEmail@em.com',
            ]);
            $response->assertStatus(302);
            $response->assertRedirect(route('home'));
            $response->assertSessionHasErrors('email');
            Notification::assertNotSentTo(User::factory()->make(['email' => 'randomEmail@em.com']), ResetPassword::class);
        }

        //  Test user submitting form without putting in an email address
        public function test_submit_reset_password_no_email()
        {
            $response = $this->post(route('password.store'), []);
            $response->assertRedirect(route('home'));
            $response->assertSessionHasErrors('email');
        }

        //  Verify the user can view the reset form
        public function test_view_password_reset_form()
        {
            $user = User::factory()->create();
            $token = Password::broker()->createToken($user);

            $response = $this->get(route('password.reset', $token));

            $response->assertSuccessful();
        }

        //  Verify the user cannot view the reset form if already logged in
        public function test_view_password_form_while_logged_in()
        {
            $user = User::factory()->create();
            $token = Password::broker()->createToken($user);

            $response = $this->actingAs($user)->get(route('password.reset', $token));

            $response->assertRedirect(route('dashboard'));
        }

        //  Verify the user can reset their password if the token is valid
        public function test_submit_reset_password_form_valid()
        {
            Event::fake();
            $user = User::factory()->create();

            $response = $this->put(route('password.reset'), [
                'token'                 => Password::broker()->createToken($user),
                'email'                 => $user->email,
                'password'              => 'new-awesome-password',
                'password_confirmation' => 'new-awesome-password',
            ]);

            $response->assertRedirect(route('login.index'));
            $this->assertEquals($user->email, $user->fresh()->email);
            $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));
        }

        //  Verify user cannot reset a password with an Invalid Reset token
        public function test_submit_reset_password_form_invalid_token()
        {
            $user = User::factory()->create([
                'password' => bcrypt('old-password'),
            ]);

            $response = $this->from(route('password.reset', 'InvalidToken'))->put(route('password.reset'), [
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
        public function test_submit_reset_password_form_blank_new_pass()
        {
            $user = User::factory()->create([
                'password' => bcrypt('old-password'),
            ]);
            $token = Password::broker()->createToken($user);

            $response = $this->from(route('password.reset', $token))->put(route('password.reset'), [
                'token'                 => $token,
                'email'                 => $user->email,
                'password'              => '',
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
        public function test_submit_reset_password_form_no_email()
        {
            $user = User::factory()->create([
                'password' => bcrypt('old-password'),
            ]);
            $token = Password::broker()->createToken($user);

            $response = $this->from(route('password.reset', $token))->put(route('password.reset'), [
                'token'                 => $token,
                'email'                 => '',
                'password'              => 'new-awesome-password',
                'password_confirmation' => 'new-awesome-password',
            ]);
            $response->assertRedirect(route('password.reset', $token));
            $response->assertSessionHasErrors('email');
            $this->assertFalse(session()->hasOldInput('password'));
            $this->assertEquals($user->email, $user->fresh()->email);
            $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
            $this->assertGuest();
        }

        //  Test a guest cannot see the change password page
        public function test_view_settings_password_guest()
        {
            $response = $this->get(route('password.edit', 'change'));

            $response->assertStatus(302);
            $response->assertRedirect(route('login.index'));
        }

        //  Test visiting the password change page while logged in
        public function test_view_settings_password()
        {
            $response = $this->actingAs(User::factory()->create())->get(route('password.edit', 'change'));

            $response->assertSuccessful();
        }

        //  Test trying to change password as a guest
        public function test_change_password_as_guest()
        {
            $user = User::factory()->create();
            $form = [
                'password' => 'newPass',
                'password_confirmation' => 'newPass',
            ];

            $response = $this->put(route('password.update', $user->username), $form);
            $response->assertStatus(302);
            $response->assertRedirect(route('login.index'));
        }

        //  Test trying to change password
        public function test_change_password()
        {
            $user = User::factory()->create();
            $form = [
                'password' => 'newPass',
                'password_confirmation' => 'newPass',
            ];

            $response = $this->actingAs($user)->put(route('password.update', $user->username), $form);
            $response->assertStatus(302);
            $response->assertSessionHas(['message' => 'Password Successfully Updated']);
        }

        //  Test trying to change password using same password
        public function test_change_password_same_password()
        {
            $user = User::factory()->create(['password' => Hash::make('newPass')]);
            $form = [
                'password' => 'newPass',
                'password_confirmation' => 'newPass',
            ];

            $response = $this->actingAs($user)->put(route('password.update', $user->username), $form);
            $response->assertStatus(302);
            $response->assertSessionHas(['message' => 'You cannot use the same password']);
        }

        //  Test trying to change someone elses password
        public function test_change_another_password()
        {
            $user = User::factory()->create();
            $form = [
                'password' => 'newPass',
                'password_confirmation' => 'newPass',
            ];

            $response = $this->actingAs(User::factory()->create())->put(route('password.update', $user->username), $form);
            $response->assertStatus(403);
        }

        //  Test trying to change someone elses password as admin
        public function test_change_another_password_as_admin()
        {
            $user = User::factory()->create();
            $form = [
                'password' => 'newPass',
                'password_confirmation' => 'newPass',
            ];

            $response = $this->actingAs(User::factory()->create(['role_id' => 1]))->put(route('password.update', $user->username), $form);
            $response->assertStatus(302);
            $response->assertSessionHas(['message' => 'Password Successfully Updated']);
        }
}
