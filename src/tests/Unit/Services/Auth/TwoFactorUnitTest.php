<?php

namespace Tests\Unit\Services\Auth;

use App\Models\DeviceToken;
use App\Models\User;
use App\Models\UserVerificationCode;
use App\Services\Auth\TwoFactorService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TwoFactorUnitTest extends TestCase
{
    /** @var string */
    protected $httpUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36';

    /*
    |---------------------------------------------------------------------------
    | processVerificationResponse()
    |---------------------------------------------------------------------------
    */
    public function test_process_verification_response_remember_on(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $data = collect(['remember' => true]);
        $code = UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => '1234',
        ]);

        $this->actingAs($user);

        $testObj = new TwoFactorService;
        $response = $testObj->processVerificationResponse(
            $data,
            $user,
            $this->httpUserAgent
        );

        $this->assertNotNull($response);
        $this->assertDatabaseMissing(
            'user_verification_codes',
            $code->only(['user_id', 'code'])
        );
    }

    public function test_process_verification_response_remember_off(): void
    {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        $data = collect(['remember' => false]);
        $code = UserVerificationCode::create([
            'user_id' => $user->user_id,
            'code' => '1234',
        ]);

        $this->actingAs($user);

        $testObj = new TwoFactorService;
        $response = $testObj->processVerificationResponse(
            $data,
            $user,
            $this->httpUserAgent
        );

        $this->assertNull($response);
        $this->assertDatabaseMissing(
            'user_verification_codes',
            $code->only(['user_id', 'code'])
        );
    }

    /*
    |---------------------------------------------------------------------------
    | generateRememberDeviceToken()
    |---------------------------------------------------------------------------
    */
    public function test_generate_remember_device_token(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $testObj = new TwoFactorService;
        $response = $testObj->generateRememberDeviceToken(
            $user,
            $this->httpUserAgent
        );

        $this->assertNotNull($response);

        $this->assertDatabaseHas('device_tokens', [
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
}
