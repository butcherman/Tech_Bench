<?php

namespace Tests\Feature\Init;

use App\Exceptions\Init\FirstTimeSetupAlreadyCompletedException;
use App\Exceptions\Init\InvalidUserAccessingSetupException;
use App\Http\Middleware\CheckForInit;
use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Tests\TestCase;

class CheckForInitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Test the Check for Init Middleware
    |---------------------------------------------------------------------------
    */
    public function test_as_guest(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_as_regular_user(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        Exceptions::fake();

        $this->expectException(InvalidUserAccessingSetupException::class);

        /** @var User $user */
        $user = User::factory()->createQuietly();

        $response = $this->withoutExceptionHandling()
            ->withMiddleware(CheckForInit::class)
            ->actingAs($user)
            ->get(route('dashboard'));

        $response->assertForbidden();

        Exceptions::assertReported(InvalidUserAccessingSetupException::class);
    }

    public function test_as_installer(): void
    {
        config(['app.first_time_setup' => true]);
        config(['app.env' => 'local']);

        $response = $this->withMiddleware(CheckForInit::class)
            ->actingAs(User::find(1))
            ->get(route('dashboard'));

        $response->assertStatus(302)
            ->assertRedirect(route('init.welcome'));
    }

    public function test_second_run(): void
    {
        config(['app.first_time_setup' => false]);
        config(['app.env' => 'local']);

        Exceptions::fake();

        $this->expectException(FirstTimeSetupAlreadyCompletedException::class);

        $response = $this->withMiddleware(CheckForInit::class)
            ->withoutExceptionHandling()
            ->actingAs(User::find(1))
            ->get(route('init.welcome'));

        $response->assertForbidden();

        Exceptions::assertReported(FirstTimeSetupAlreadyCompletedException::class);
    }
}
