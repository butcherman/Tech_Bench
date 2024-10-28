<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\UserVerificationCode;
use App\Services\Auth\TwoFactorService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class TwoFactorUnitTest extends TestCase
{
    /** @var string */
    protected $httpUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36';

    public function test_process_verification_response_remember_on()
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
        $response = $testObj->processVerificationResponse($data, $user, $this->httpUserAgent);

        $this->assertNotNull($response);
        $this->assertDatabaseMissing(
            'user_verification_codes',
            $code->toArray()
        );
    }

    public function test_process_verification_response_remember_off()
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
            $data, $user,
            $this->httpUserAgent
        );

        $this->assertNull($response);
        $this->assertDatabaseMissing(
            'user_verification_codes',
            $code->toArray()
        );
    }

    public function test_generate_remember_device_token()
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
}
