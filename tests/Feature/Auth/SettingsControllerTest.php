<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class SettingsControllerTest extends TestCase
{
    protected $user;

    //  Seed the database with some default data and setup for testing
    public function setUp():void
    {
        Parent::setup();

        $this->user = $this->getTech();
    }

    //  Verify a guest cannot view the account page
    public function test_index_guest()
    {
        $response = $this->get(route('settings'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Verify a logged in user can view the account page
    public function test_index()
    {
        $response = $this->actingAs($this->user)->get(route('settings'));

        $response->assertSuccessful();
        $response->assertViewIs('auth.settings');
    }

    //  Submit new user account settings
    public function test_update_account()
    {
        $newData = factory(User::class)->make();
        $data = [
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];

        $response = $this->actingAs($this->user)->post(route('update_account'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Submit new user account settings as a guest
    public function test_update_account_guest()
    {
        $newData = factory(User::class)->make();
        $data = [
            'first_name' => $newData->first_name,
            'last_name'  => $newData->last_name,
            'email'      => $newData->email,
        ];
        $response = $this->post(route('update_account'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Submit the new user account settings with validation errors
    public function test_update_account_validation_error()
    {
        $newData = factory(User::class)->make();
        $data = [
            'first_name' => null,
            'last_name'  => null,
            'email'      => null,
        ];

        $response = $this->actingAs($this->user)->post(route('update_account'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['first_name', 'last_name', 'email']);
    }

    //  Submit new user settings
    public function test_update_settings()
    {
        $data = [
            'em_tech_tip'     => false,
            'em_file_link'    => false,
            'em_notification' => false,
            'auto_del_link'   => false,
        ];

        $response = $this->actingAs($this->user)->put(route('update_settings'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Submit new user settings as a guest
    public function test_update_settings_guest()
    {
        $data = [
            'em_tech_tip'     => false,
            'em_file_link'    => false,
            'em_notification' => false,
            'auto_del_link'   => false,
        ];
        $response = $this->put(route('update_settings'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Submit the new user settings with validation errors
    public function test_update_settings_validation_error()
    {
        $data = [
            'em_tech_tip'     => null,
            'em_file_link'    => 'Bobby Ray',
            'em_notification' => ['some', 'entry'],
            'auto_del_link'   => null,
        ];

        $response = $this->actingAs($this->user)->put(route('update_settings'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['em_tech_tip', 'em_file_link', 'em_notification', 'auto_del_link']);
    }

    //  Submit new user settings
    public function test_change_password()
    {
        $data = [
            'current'               => 'password',
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];

        $response = $this->actingAs($this->user)->post(route('submit_password'), $data);
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    //  Submit new user settings as a guest
    public function test_change_password_guest()
    {
        $data = [
            'current'               => 'password',
            'password'              => 'newPassword',
            'password_confirmation' => 'newPassword',
        ];
        $response = $this->post(route('submit_password'), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    //  Submit the new user settings with validation errors
    public function test_change_password_validation_error()
    {
        $data = [
            'current'               => 'newPassword',
            'password'              => 'newPassword',
            'password_confirmation' => 'randomBob',
        ];

        $response = $this->actingAs($this->user)->post(route('submit_password'), $data);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['current', 'password']);
    }
}
