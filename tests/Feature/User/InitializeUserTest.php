<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Support\Str;
use Tests\TestCase;

class InitializeUserTest extends TestCase
{
    protected $password = 'ChangeMe$secure1';

    /**
     * Get Method
     */
    public function test_get_while_logged_in()
    {
        $user = User::factory()->create();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->actingAs(User::factory()->create())->get(route('initialize', $token));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_get_invalid_token()
    {
        $user = User::factory()->create();
        $token = Str::uuid();
        UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));
        $response->assertStatus(404);
    }

    public function test_get()
    {
        $user = User::factory()->create();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));
        $response->assertSuccessful();
    }

    /**
     * Set Method
     */
    public function test_set_while_logged_in()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);
        $data = [
            'username' => $user->username,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('initialize.submit', $token), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_set_invalid_token()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);
        $token = Str::uuid();
        $data = [
            'username' => $user->username,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->post(route('initialize.submit', $token), $data);
        $response->assertStatus(404);
    }

    public function test_set()
    {
        $user = User::factory()->create();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);
        $data = [
            'username' => $user->username,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->post(route('initialize.submit', $token), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', __('user.initialized'));
        $this->assertDatabaseMissing('user_initializes', $link->toArray());
    }
}
