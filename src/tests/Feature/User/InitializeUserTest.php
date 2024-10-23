<?php

namespace Tests\Feature\User;

use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class InitializeUserTest extends TestCase
{
    protected $password = 'ChangeMe$secure1';

    /**
     * Show Method
     */
    public function test_show_while_logged_in()
    {
        $user = User::factory()->createQuietly();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->actingAs(User::factory()->createQuietly())
            ->get(route('initialize', $token));
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_show_invalid_token()
    {
        $user = User::factory()->createQuietly();
        $token = Str::uuid();
        UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));
        $response->assertStatus(404);
    }

    public function test_show()
    {
        $user = User::factory()->createQuietly();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_set_while_logged_in()
    {
        $user = User::factory()->createQuietly();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);
        $data = [
            'username' => $user->username,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs(User::factory()->createQuietly())
            ->put(route('initialize.update', $token), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_set_invalid_token()
    {
        $user = User::factory()->createQuietly();
        UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);
        $token = Str::uuid();
        $data = [
            'username' => $user->username,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('initialize.update', $token), $data);
        $response->assertStatus(404);
    }

    public function test_set()
    {
        Event::fake();

        $user = User::factory()->createQuietly();
        $link = UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);
        $data = [
            'username' => $user->username,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('initialize.update', $token), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', __('user.initialized'));
        $this->assertDatabaseMissing('user_initializes', $link->toArray());

        Event::assertDispatched(Login::class);
    }
}
