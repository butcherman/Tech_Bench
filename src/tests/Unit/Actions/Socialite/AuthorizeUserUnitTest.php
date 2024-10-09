<?php

namespace Tests\Unit\Actions\Socialite;

use App\Actions\Socialite\AuthorizeUser;
use App\Exceptions\Auth\UnableToCreateAzureUserException;
use App\Jobs\User\CreateUserSettingsEntriesJob;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;

class AuthorizeUserUnitTest extends TestCase
{
    public function test_with_existing_user()
    {
        $user = User::factory()->create();

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
                    'givenName' => $user->full_name,
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

        $testObject = new AuthorizeUser;
        $response = $testObject->handle();

        $this->assertEquals($user->toArray(), $response->toArray());
    }

    public function test_with_new_user_registration_enabled()
    {
        Bus::fake();

        config(['services.azure.allow_register' => true]);

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

        $testObject = new AuthorizeUser;
        $response = $testObject->handle();

        $this->assertEquals($user->toArray(), $response->toArray());
        $this->assertDatabaseHas('users', $user->only([
            'first_name',
            'last_name',
            'email',
        ]));

        Bus::assertDispatched(CreateUserSettingsEntriesJob::class);
    }

    public function test_with_new_user_registration_disabled()
    {
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

        $testObject = new AuthorizeUser;

        $this->expectException(UnableToCreateAzureUserException::class);
        $response = $testObject->handle();

        $this->assertFalse($response);
        $this->assertDatabaseMissing('users', $user->only([
            'first_name',
            'last_name',
            'email',
        ]));
    }
}
