<?php

namespace Tests\Feature\Auth;

use App\Jobs\User\CreateUserSettingsEntriesJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;

class SocialiteTest extends TestCase
{
    /**
     * RedirectAuth Method
     */
    public function test_redirect_auth_disabled()
    {
        config(['services.azure.allow_login' => false]);

        $response = $this->get(route('azure-login'));

        $response->assertStatus(404);
    }

    public function test_redirect_auth()
    {
        config(['services.azure.allow_login' => true]);

        $response = $this->get(route('azure-login'));

        $response->assertStatus(302);
    }

    /**
     * Callback Method
     */
    public function test_callback_feature_disabled()
    {
        config(['services.azure.allow_login' => false]);

        $response = $this->get(route('azure-callback'));

        $response->assertStatus(404);
    }

    public function test_callback_invalid_state()
    {
        config(['services.azure.allow_login' => true]);

        $response = $this->get(route('azure-callback'));

        $response->assertStatus(500);
    }

    public function test_callback_new_user()
    {
        Bus::fake();

        config(['services.azure.allow_login' => true]);
        config(['services.azure.allow_register' => true]);
        config(['services.azure.allow_bypass_2fa' => true]);

        $user = User::factory()->make();
        $user->username = $user->email;

        // Mock the Socialite Response
        $abstractUser = $this->mock(
            'SocialiteProviders\Azure\User',
            function (MockInterface $mock) use ($user) {
                $mock->id = Str::uuid();
                $mock->nickname = null;
                $mock->email = $user->email;
                $mock->avatar = null;
                $mock->user = [
                    'id' => Str::uuid(),
                    'displayName' => $user->full_name,
                    'givenName' => $user->first_name,
                    'jobTitle' => null,
                    'mail' => $user->email,
                    'surname' => $user->last_name,
                    'userPrincipalName' => $user->email,
                ];
                $mock->token = Str::uuid();
                $mock->principalName = $user->email;
                $mock->mail = $user->email;
            });

        $provider = $this->mock(
            'Laravel\Socialite\Contracts\Provider',
            function (MockInterface $mock) use ($abstractUser) {
                $mock
                    ->shouldReceive('user')
                    ->andReturn($abstractUser);
            });

        Socialite::shouldReceive('driver')
            ->with('azure')
            ->andReturn($provider);

        $response = $this->get(route('azure-callback'));

        $response->assertStatus(302)
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('2fa_verified', true);

        $this->assertAuthenticatedAs(User::where('email', $user->email)->first());
        $this->assertDatabaseHas('users', $user->only([
            'first_name',
            'last_name',
            'email',
        ]));

        Bus::assertDispatched(CreateUserSettingsEntriesJob::class);
        Bus::assertNotDispatched(SendWelcomeEmailJob::class);
    }

    public function test_callback_new_user_two_fa_bypass_disabled()
    {
        Bus::fake();

        config(['services.azure.allow_login' => true]);
        config(['services.azure.allow_register' => true]);
        config(['services.azure.allow_bypass_2fa' => false]);

        $user = User::factory()->make();
        $user->username = $user->email;

        // Mock the Socialite Response
        $abstractUser = $this->mock(
            'SocialiteProviders\Azure\User',
            function (MockInterface $mock) use ($user) {
                $mock->id = Str::uuid();
                $mock->nickname = null;
                $mock->email = $user->email;
                $mock->avatar = null;
                $mock->user = [
                    'id' => Str::uuid(),
                    'displayName' => $user->full_name,
                    'givenName' => $user->first_name,
                    'jobTitle' => null,
                    'mail' => $user->email,
                    'surname' => $user->last_name,
                    'userPrincipalName' => $user->email,
                ];
                $mock->token = Str::uuid();
                $mock->principalName = $user->email;
                $mock->mail = $user->email;
            });

        $provider = $this->mock(
            'Laravel\Socialite\Contracts\Provider',
            function (MockInterface $mock) use ($abstractUser) {
                $mock
                    ->shouldReceive('user')
                    ->andReturn($abstractUser);
            });

        Socialite::shouldReceive('driver')
            ->with('azure')
            ->andReturn($provider);

        $response = $this->get(route('azure-callback'));
        $response->assertStatus(302);
        $response->assertSessionMissing('2fa_verified', true);

        $this->assertAuthenticatedAs(User::where('email', $user->email)->first());
        $this->assertDatabaseHas('users', $user->only([
            'first_name',
            'last_name',
            'email',
        ]));

        Bus::assertDispatched(CreateUserSettingsEntriesJob::class);
        Bus::assertNotDispatched(SendWelcomeEmailJob::class);
    }

    public function test_callback_new_user_register_disabled()
    {
        config(['services.azure.allow_login' => true]);
        config(['services.azure.allow_register' => false]);

        $user = User::factory()->make();
        $user->username = $user->email;

        // Mock the Socialite Response
        $abstractUser = $this->mock(
            'SocialiteProviders\Azure\User',
            function (MockInterface $mock) use ($user) {
                $mock->id = Str::uuid();
                $mock->nickname = null;
                $mock->email = $user->email;
                $mock->avatar = null;
                $mock->user = [
                    'id' => Str::uuid(),
                    'displayName' => $user->full_name,
                    'givenName' => $user->first_name,
                    'jobTitle' => null,
                    'mail' => $user->email,
                    'surname' => $user->last_name,
                    'userPrincipalName' => $user->email,
                ];
                $mock->token = Str::uuid();
                $mock->principalName = $user->email;
                $mock->mail = $user->email;
            });

        $provider = $this->mock(
            'Laravel\Socialite\Contracts\Provider',
            function (MockInterface $mock) use ($abstractUser) {
                $mock
                    ->shouldReceive('user')
                    ->andReturn($abstractUser);
            });

        Socialite::shouldReceive('driver')
            ->with('azure')
            ->andReturn($provider);

        $response = $this->get(route('azure-callback'));

        $response->assertStatus(302)
            ->assertRedirect(route('login'))
            ->assertSessionHas('warning', 'You do not have permission to Login.  Please'.
                   ' contact your system administrator');

        $this->assertDatabaseMissing('users', $user->only([
            'first_name',
            'last_name',
            'email',
        ]));
    }
}
