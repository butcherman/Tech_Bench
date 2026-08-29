<?php

namespace Tests\Unit\Services\Auth;

use App\Enums\TwoFactorMethod;
use App\Models\DeviceToken;
use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class TwoFactorUnitTest extends TestCase
{
    /** @var string */
    protected $httpUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36';

    /*
    |---------------------------------------------------------------------------
    | getChallengedUser()
    |---------------------------------------------------------------------------
    */
    public function test_get_challenged_user(): void
    {
        $user = User::factory()->create();
        $request = $this->request();
        $request->session()->put('login.id', $user->user_id);

        $testObj = new TwoFactorService;
        $response = $testObj->getChallengedUser($request);

        $this->assertEquals(
            $response->makeHidden('two_factor_confirmed_at')->toArray(),
            $user->toArray()
        );
    }

    public function test_get_challenged_user_invalid(): void
    {
        $request = $this->request();
        $request->session()->put('login.id', 9999);

        $testObj = new TwoFactorService;
        $response = $testObj->getChallengedUser($request);

        $this->assertNull($response);
    }

    /*
    |---------------------------------------------------------------------------
    | getMfaMethod()
    |---------------------------------------------------------------------------
    */
    public function test_get_mfa_method_null(): void
    {
        $user = User::factory()->create();

        $testObj = new TwoFactorService;
        $response = $testObj->getMfaMethod($user);

        $this->assertNull($response);
    }

    public function test_get_mfa_method_email(): void
    {
        $user = User::factory()->create([
            'two_factor_via' => 'email',
        ]);

        $testObj = new TwoFactorService;
        $response = $testObj->getMfaMethod($user);

        $this->assertEquals(TwoFactorMethod::Email, $response);
    }

    public function test_get_mfa_method_authenticator(): void
    {
        $user = User::factory()->create([
            'two_factor_via' => 'authenticator',
        ]);

        $testObj = new TwoFactorService;
        $response = $testObj->getMfaMethod($user);

        $this->assertEquals(TwoFactorMethod::Authenticator, $response);
    }

    /*
    |---------------------------------------------------------------------------
    | provideChallenge()
    |---------------------------------------------------------------------------
    */
    public function test_provide_challenge_via_authenticator(): void
    {
        $user = User::factory()->create([
            'two_factor_via' => 'authenticator',
        ]);

        /** @var mixed */
        $spy = Mockery::spy($user);

        $testObj = new TwoFactorService;
        $testObj->provideChallenge($spy);

        $spy->shouldNotHaveReceived('generateVerificationCode');

        $this->assertDatabaseMissing('user_verification_codes', [
            'user_id' => $user->user_id,
        ]);
    }

    public function test_provide_challenge_via_email(): void
    {
        $user = User::factory()->create([
            'two_factor_via' => 'email',
        ]);

        /** @var mixed */
        $spy = Mockery::spy($user);

        $testObj = new TwoFactorService;
        $testObj->provideChallenge($spy);

        $spy->shouldHaveReceived('generateVerificationCode');

        $this->assertDatabaseHas('user_verification_codes', [
            'user_id' => $user->user_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyDeviceToken()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_device_token(): void
    {
        /** @var User $user */
        $user = User::factory()->has(DeviceToken::factory()->count(3))->create();
        $token = $user->DeviceTokens[0];

        $testObj = new TwoFactorService;
        $testObj->destroyDeviceToken($token);

        $this->assertDatabaseMissing(
            'device_tokens',
            $token->only(['user_id', 'device_id'])
        );
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
