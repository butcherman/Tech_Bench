<?php

namespace Tests\Feature\User;

use App\Events\User\UserInitializeComplete;
use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class InitializeUserTest extends TestCase
{
    /** @var string */
    protected $password = 'ChangeMe$secure1';

    /*
    |---------------------------------------------------------------------------
    | Show Method
    |---------------------------------------------------------------------------
    */
    public function test_show_while_logged_in(): void
    {
        /** @var User */
        $admin = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();

        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('initialize', $token));

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    public function test_show_invalid_token(): void
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

    public function test_show(): void
    {
        $user = User::factory()->createQuietly();

        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->get(route('initialize', $token));

        $response->assertSuccessful();
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_set_while_logged_in(): void
    {
        /** @var User */
        $admin = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();

        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $data = [
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->actingAs($admin)
            ->put(route('initialize.update', $token), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'));
    }

    public function test_set_invalid_token(): void
    {
        $user = User::factory()->createQuietly();

        UserInitialize::create([
            'username' => $user->username,
            'token' => Str::uuid(),
        ]);

        $token = Str::uuid();
        $data = [
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('initialize.update', $token), $data);

        $response->assertStatus(404);
    }

    public function test_set(): void
    {
        Event::fake();

        $user = User::factory()->createQuietly();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);
        $data = [
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ];

        $response = $this->put(route('initialize.update', $token), $data);

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success', __('user.initialized'));

        $this->assertAuthenticatedAs($user);

        Event::assertDispatched(UserInitializeComplete::class);
        Event::assertDispatched(Login::class);
    }
}
