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
    public function testViewPageGuest()
    {
        $response = $this->get(route('account'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify a logged in user can view the account page
    public function testViewPage()
    {
        $response = $this->actingAs($this->user)->get(route('account'));

        $response->assertSuccessful();
        $response->assertViewIs('account.index');
    }

    //  Verify that a guest cannot submit the change settings form
    public function testSubmitFormGuest()
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
    public function testSubmitForm()
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
    public function testChangePasswordGuest()
    {
        $response = $this->get(route('changePassword'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify a logged in user can view the change password page
    public function testChangePassword()
    {
        $response = $this->actingAs($this->user)->get(route('changePassword'));

        $response->assertSuccessful();
        $response->assertViewIs('account.changePassword');
    }

    //  Verify that a guest cannot submit the change password form
    public function testSubmitPasswordGuest()
    {
        $data = [
            'oldPass'               => 'ThisIsAPassword',
            'newPass'               => 'Bobby',
            'newPass_confirmation'  => 'Bobby'
        ];

        $response = $this->from(route('changePassword'))->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify that a user can submit the change password form
    public function testSubmitPassword()
    {
        $data = [
            'oldPass'               => 'ThisIsAPassword',
            'newPass'               => 'BobbyNewPass',
            'newPass_confirmation'  => 'BobbyNewPass'
        ];

        $response = $this->actingAs($this->user)->from(route('changePassword'))->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('account'));
        $response->assertSessionHas('success', 'Password Changed Successfully');
    }

    //  Verify that a use cannot submit password if the old password is invalid
    public function testSubmitPasswordBadOld()
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
    public function testSubmitPasswordBadConfirm()
    {
        $data = [
            'oldPass'               => 'ThisIsAPassword',
            'newPass'               => 'BobbyNewPass',
            'newPass_confirmation'  => 'BobbyUnconfirmed'
        ];

        $response = $this->actingAs($this->user)->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['newPass']);
    }

    //  Verify that a use cannot submit password if the new password is the same as the old one
    public function testSubmitPasswordSame()
    {
        $data = [
            'oldPass'               => 'ThisIsAPassword',
            'newPass'               => 'ThisIsAPassword',
            'newPass_confirmation'  => 'ThisIsAPassword'
        ];

        $response = $this->actingAs($this->user)->post(route('changePassword'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['newPass']);
    }
}
