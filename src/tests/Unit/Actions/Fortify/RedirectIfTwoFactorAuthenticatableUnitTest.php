<?php

namespace Tests\Unit\Actions\Fortify;

use App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable;
use App\Exceptions\Auth\InvalidMultiFactorAuthException;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class RedirectIfTwoFactorAuthenticatableUnitTest extends TestCase
{
    public function test_handle_two_fa_disabled(): void
    {
        config([
            'auth.twoFa.enabled' => false,
        ]);

        $user = User::factory()->make();
        $request = $this->request();
        $next = fn ($request) => 'next';
        $action = $this->action($user);

        $result = $action->handle($request, $next);

        $this->assertSame('next', $result);
    }

    public function test_handle_two_fa_not_required_or_setup(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => false,
        ]);

        $user = User::factory()->make();
        $request = $this->request();
        $next = fn ($request) => 'next';
        $action = $this->action($user);

        $result = $action->handle($request, $next);

        $this->assertSame('next', $result);
    }

    public function test_handle_with_device_token(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => 'email',
        ]);

        $token = DeviceToken::factory()->create([
            'user_id' => $user->user_id,
        ]);

        $request = $this->request();
        $request->cookies->set('remember_device', $token->token);
        $next = fn ($request) => 'next';

        $result = $this->action($user)->handle($request, $next);

        $this->assertSame('next', $result);
    }

    public function test_handle_with_device_token_but_feature_disabled(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => false,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => 'email',
        ]);

        $token = DeviceToken::factory()->create([
            'user_id' => $user->user_id,
        ]);

        $request = $this->request();
        $request->cookies->set('remember_device', $token->token);

        $result = $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );

        $this->assertSame(
            route('two-factor.login'),
            $result->getTargetUrl()
        );
    }

    public function test_handle_with_authenticator_allowed_but_not_setup(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => false,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => null,
        ]);

        $request = $this->request();

        $result = $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );

        $this->assertSame(
            route('two-factor.setup.authenticator'),
            $result->getTargetUrl()
        );
    }

    public function test_handle_with_email_allowed_but_not_setup(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => false,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => null,
        ]);

        $request = $this->request();

        $result = $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );

        $this->assertSame(
            route('two-factor.setup.email'),
            $result->getTargetUrl()
        );
    }

    public function test_handle_with_multiple_methods_allowed_but_not_setup(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => null,
        ]);

        $request = $this->request();

        $result = $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );

        $this->assertSame(
            route('two-factor.setup.index'),
            $result->getTargetUrl()
        );
    }

    public function test_handle_throws_exception_when_no_two_factor_methods_are_enabled(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => false,
            'auth.twoFa.methods.email' => false,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => null,
        ]);

        $request = $this->request();

        $this->expectException(InvalidMultiFactorAuthException::class);

        $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );
    }

    public function test_handle_throws_exception_for_invalid_two_factor_method(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => 'something_invalid',
        ]);

        $request = $this->request();

        $this->expectException(InvalidMultiFactorAuthException::class);

        $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );
    }

    public function test_handle_redirect_to_email(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => 'email',
        ]);

        $request = $this->request();

        $result = $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );

        $this->assertSame(
            route('two-factor.login'),
            $result->getTargetUrl()
        );
    }

    public function test_handle_redirect_to_auth(): void
    {
        config([
            'auth.twoFa.enabled' => true,
            'auth.twoFa.required' => true,
            'auth.twoFa.allow_save_device' => true,
            'auth.twoFa.methods.authenticator' => true,
            'auth.twoFa.methods.email' => true,
        ]);

        $user = User::factory()->create([
            'two_factor_via' => 'authenticator',
        ]);

        $request = $this->request();

        $result = $this->action($user)->handle(
            $request,
            fn ($request) => 'next'
        );

        $this->assertSame(
            route('two-factor.login'),
            $result->getTargetUrl()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | Testing Methods
    |---------------------------------------------------------------------------
    */

    private function action(User $user): RedirectIfTwoFactorAuthenticatable
    {
        $action = $this->getMockBuilder(RedirectIfTwoFactorAuthenticatable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validateCredentials'])
            ->getMock();

        $action->method('validateCredentials')
            ->willReturn($user);

        return $action;
    }

    private function request(): Request
    {
        $request = Request::create('/login', 'POST');

        $request->setLaravelSession(
            app('session')->driver()
        );

        return $request;
    }
}
