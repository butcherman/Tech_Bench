<?php

namespace Tests\Unit\Services\Auth;

use App\Models\DeviceToken;
use App\Models\User;
use App\Services\Auth\TwoFactorService;
use Tests\TestCase;

class TwoFactorUnitTest extends TestCase
{
    /** @var string */
    protected $httpUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36';

    // TODO - Finish Me

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
