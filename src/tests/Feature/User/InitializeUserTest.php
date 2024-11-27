<?php

namespace Tests\Feature\User;

use App\Events\User\UserInitializeComplete;
use App\Models\User;
use App\Models\UserInitialize;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
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
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
        $user = User::factory()->createQuietly();
        UserInitialize::create([
            'username' => $user->username,
            'token' => $token = Str::uuid(),
        ]);

        $response = $this->actingAs($actingAs)
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

        $response->assertSuccessful()
            ->assertInertia(fn (Assert $page) => $page
                ->component('User/Initialize')
                ->has('token')
                ->has('user')
                ->has('rules')
            );
    }

    /*
    |---------------------------------------------------------------------------
    | Update Method
    |---------------------------------------------------------------------------
    */
    public function test_set_while_logged_in(): void
    {
        /** @var User $actingAs */
        $actingAs = User::factory()->createQuietly();
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

        $response = $this->actingAs($actingAs)
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
            'username' => $user->username,
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

        $response->assertStatus(302)
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', __('user.initialized'));

        Event::assertDispatched(UserInitializeComplete::class);
    }
}
