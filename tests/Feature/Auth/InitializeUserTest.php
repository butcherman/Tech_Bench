<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\UserInitialize;

class InitializeUserTest extends TestCase
{
    protected $testCase;

    public function setUp(): void
    {
        Parent::setUp();

        $this->testCase = factory(UserInitialize::class)->create();
    }

    //  Visit the Initialize user page
    public function test_visit_initalize_user_page()
    {
        $response = $this->get(route('initialize', $this->testCase->token));

        // dd($response);

        $response->assertSuccessful();
        $response->assertViewIs('account.initializeUser');
        $this->assertGuest();
    }

    //  Try to visit the Initialize user page with an invalid hash
    public function test_visit_initialize_user_invalid_hash()
    {
        $hash = factory(UserInitialize::class)->make()->token;

        $response = $this->get(route('initialize', $hash));

        $response->assertStatus(404);
        $this->assertGuest();
    }

    //  Submit the new user setup invalid password
    public function test_submit_initialize_user_invalid_password()
    {
        $data = [
            'username' => $this->testCase->username,
            'newPass'  => 'mySuperSecretePassword',
            'newPass_confirmation' => 'mySuperSecretePassword99',
        ];

        $response = $this->post(route('initialize', $this->testCase->token), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['newPass']);
        $this->assertGuest();
    }

    //  Submit the new user setup
    public function test_submit_initialize_user()
    {
        $data = [
            'username' => $this->testCase->username,
            'newPass'  => 'mySuperSecretePassword',
            'newPass_confirmation' => 'mySuperSecretePassword',
        ];

        $response = $this->post(route('initialize', $this->testCase->token), $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    //  Submit the new user setup with an invalid hash
    public function test_submit_initialize_user_invalid_hash()
    {
        $hash = factory(UserInitialize::class)->make()->token;
        $data = [
            'username' => $this->testCase->username,
            'newPass'  => 'mySuperSecretePassword',
            'newPass_confirmation' => 'mySuperSecretePassword',
        ];

        $response = $this->post(route('initialize', $hash), $data);

        $response->assertStatus(404);
        $this->assertGuest();
    }
}
