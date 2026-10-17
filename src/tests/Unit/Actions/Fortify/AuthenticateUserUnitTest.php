<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\AuthenticateUser;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuthenticateUserUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | authenticate()
    |---------------------------------------------------------------------------
    */
    public function test_authenticate_with_username(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $request = $this->request($user->username, 'password');

        $testObj = new AuthenticateUser;
        $response = $testObj->authenticate($request);

        $this->assertEquals(
            $user->toArray(),
            $response->makeHidden('two_factor_confirmed_at')->toArray()
        );
    }

    public function test_authenticate_with_email(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $request = $this->request($user->email, 'password');

        $testObj = new AuthenticateUser;
        $response = $testObj->authenticate($request);

        $this->assertEquals(
            $user->toArray(),
            $response->makeHidden('two_factor_confirmed_at')->toArray()
        );
    }

    public function test_authenticate_with_invalid_password(): void
    {
        /** @var User */
        $user = User::factory()->create();

        $request = $this->request($user->email, 'passord');

        $testObj = new AuthenticateUser;
        $response = $testObj->authenticate($request);

        $this->assertNull($response);
    }

    public function test_authenticate_with_invalid_username(): void
    {
        /** @var User */
        $user = User::factory()->make();

        $request = $this->request($user->username, 'password');

        $testObj = new AuthenticateUser;
        $response = $testObj->authenticate($request);

        $this->assertNull($response);
    }

    public function test_authenticate_with_invalid_email(): void
    {
        /** @var User */
        $user = User::factory()->make();

        $request = $this->request($user->email, 'password');

        $testObj = new AuthenticateUser;
        $response = $testObj->authenticate($request);

        $this->assertNull($response);
    }

    /*
    |---------------------------------------------------------------------------
    | Testing Methods
    |---------------------------------------------------------------------------
    */
    private function request(string $username, string $password): Request
    {
        $request = Request::create('/login', 'POST', [
            'username' => $username,
            'password' => $password,
        ]);

        $request->setLaravelSession(
            app('session')->driver()
        );

        return $request;
    }
}
