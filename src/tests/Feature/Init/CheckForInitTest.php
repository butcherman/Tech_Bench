<?php

namespace Tests\Feature\Init;

use App\Http\Middleware\CheckForInit;
use App\Models\User;
use Tests\TestCase;

class CheckForInitTest extends TestCase
{
    /**
     * Test the Check for Init Middleware
     */
    public function test_as_guest()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_as_regular_user()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->withMiddleware(CheckForInit::class)
            ->actingAs($user)
            ->get(route('dashboard'));

        $response->assertForbidden();
    }

    public function test_as_installer()
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->withMiddleware(CheckForInit::class)
            ->actingAs(User::find(1))
            ->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('init.welcome'));
    }

    public function test_second_run()
    {
        config(['app.first_time_setup' => false]);
        config(['app.env' => 'local']);

        $response = $this->withMiddleware(CheckForInit::class)
            ->actingAs(User::find(1))
            ->get(route('init.welcome'));

        $response->assertForbidden();
    }
}
