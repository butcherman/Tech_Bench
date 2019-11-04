<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    public $user;

    public function setUp():void
    {
        Parent::setUp();

        $this->user = $this->getTech();
    }

    //  Verify a guest cannot view the account page
    public function test_view_page_as_guest()
    {
        $response = $this->get(route('account'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify a logged in user can view the account page
    public function test_view_page()
    {
        $response = $this->actingAs($this->user)->get(route('account'));

        $response->assertSuccessful();
        $response->assertViewIs('account.index');
    }

    //  Verify that a guest cannot submit the change settings form
    public function test_submit_form_as_guest()
    {
        $data = [
            'username'   => $this->user->username,
            'first_name' => 'Bobby',
            'last_name'  => 'ishkabibble',
            'email'      => 'newEmail@em.com',
        ];

        $response = $this->post(route('account'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a user can submit the change settings form
    public function test_submit_form()
    {
        $data = [
            'username'   => $this->user->username,
            'first_name' => 'Bobby',
            'last_name'  => 'ishkabibble',
            'email'      => 'newEmail@em.com',
        ];

        $response = $this->actingAs($this->user)->from(route('account'))->post(route('account'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));
        $response->assertSessionHas('success', 'User Settings Updated');
    }

    //  Verify a guest cannot view the change password page
    public function test_change_password_as_guest()
    {
        $response = $this->get(route('changePassword'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify a logged in user can view the change password page
    public function test_change_password()
    {
        $response = $this->actingAs($this->user)->get(route('changePassword'));

        $response->assertSuccessful();
        $response->assertViewIs('account.changePassword');
    }

    //  Verify that a guest cannot submit the change password form
    public function test_submit_password_change_as_guest()
    {
        $data = [
            'oldPass'               => 'password',
            'newPass'               => 'Bobby',
            'newPass_confirmation'  => 'Bobby'
        ];

        $response = $this->from(route('changePassword'))->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a user can submit the change password form
    public function test_submit_password_change()
    {
        $data = [
            'oldPass'               => 'password',
            'newPass'               => 'BobbyNewPass',
            'newPass_confirmation'  => 'BobbyNewPass'
        ];

        $response = $this->actingAs($this->user)->from(route('changePassword'))->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));
        $response->assertSessionHas('success', 'Password Changed Successfully');
    }

    //  Verify that a use cannot submit password if the old password is invalid
    public function test_submit_password_bad_old_password()
    {
        $data = [
            'oldPass'               => 'secretlyBadPassword',
            'newPass'               => 'Bobby',
            'newPass_confirmation'  => 'Bobby'
        ];

        $response = $this->actingAs($this->user)->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['oldPass']);
    }

    //  Verify that a use cannot submit password if the confirmation password does not match
    public function test_submit_password_wrong_confirmed()
    {
        $data = [
            'oldPass'               => 'password',
            'newPass'               => 'BobbyNewPass',
            'newPass_confirmation'  => 'BobbyUnconfirmed'
        ];

        $response = $this->actingAs($this->user)->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['newPass']);
    }

    //  Verify that a use cannot submit password if the new password is the same as the old one
    public function test_submit_password_new_and_old_same()
    {
        $data = [
            'oldPass'               => 'password',
            'newPass'               => 'password',
            'newPass_confirmation'  => 'password'
        ];

        $response = $this->actingAs($this->user)->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['newPass']);
    }

    //  Verfiy that a user can update their notification settings
    public function test_update_notifications()
    {
        $data = [
            'em_tech_tip'     => 'on',
            'em_file_link'    => 'on',
            'em_notification' => 'on',
            'auto_del_link'   => 'on',
        ];

        $response = $this->actingAs($this->user)->put(route('account'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));
        $response->assertSessionHas('success', 'User Notifications Updated');
    }

    //  Verify that a guest cannot update a user's notification settings
    public function test_update_notifications_as_guest()
    {
        $data = [
            'em_tech_tip'     => 'on',
            'em_file_link'    => 'on',
            'em_notification' => 'on',
            'auto_del_link'   => 'on',
        ];

        $response = $this->put(route('account'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
