<?php

namespace Tests\Feature\User;

use Tests\TestCase;

use App\Models\User;
use App\Models\UserInitialize;

use Illuminate\Support\Str;

class UserInitializeTest extends TestCase
{
    protected $user;
    protected $token;

    public function setup():void
    {
        Parent::setup();

        $this->user  = User::factory()->create();
        $this->token = UserInitialize::create(['username' => $this->user->username, 'token' => Str::uuid(),]);
    }

    /*
    *   Show route
    */
    public function test_show_logged_in()
    {
        $response = $this->actingAs($this->user)->get(route('initialize', $this->token->token));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_show_invalid_token()
    {
        $response = $this->get(route('initialize', 'completely-random-string'));
        $response->assertStatus(404);
    }

    public function test_show()
    {
        $response = $this->get(route('initialize', $this->token->token));
        $response->assertSuccessful();
    }

    /*
    *   Update Function
    */
    public function test_update_logged_in()
    {
        $form = [
            'email'                 => $this->user->email,
            'password'              => 'CoolNewPassword',
            'password_confirmation' => 'CoolNewPassword',
        ];
        $response = $this->actingAs($this->user)->put(route('initialize.update', $this->token->token), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_update_invalid_token()
    {
        $form = [
            'email'                 => $this->user->email,
            'password'              => 'CoolNewPassword',
            'password_confirmation' => 'CoolNewPassword',
        ];
        $response = $this->put(route('initialize.update', 'random-token'), $form);
        $response->assertStatus(404);
    }

    public function test_update()
    {
        $form = [
            'email'                 => $this->user->email,
            'password'              => 'CoolNewPassword',
            'password_confirmation' => 'CoolNewPassword',
        ];
        $response = $this->put(route('initialize.update', $this->token->token), $form);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas(['message' => 'Your account is setup']);
        $this->assertDatabaseMissing('user_initializes', ['username' => $this->token->username, 'token' => $this->token->token]);
    }

    public function test_update_wrong_link()
    {
        $token2 = UserInitialize::create(['username' => User::factory()->create(), 'token' => Str::uuid(),]);
        $form = [
            'email'                 => $this->user->email,
            'password'              => 'CoolNewPassword',
            'password_confirmation' => 'CoolNewPassword',
        ];
        $response = $this->put(route('initialize.update', $token2->token), $form);
        $response->assertStatus(403);
    }
}
