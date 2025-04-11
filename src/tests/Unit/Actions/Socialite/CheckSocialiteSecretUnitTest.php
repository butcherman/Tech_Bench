<?php

namespace Tests\Unit\Actions\Socialite;

use App\Actions\Socialite\CheckSocialiteSecret;
use Carbon\Carbon;
use Tests\TestCase;

class CheckSocialiteSecretUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_thirty_days_out(): void
    {
        config(['services.azure.secret_expires' => Carbon::now()->addDays(31)]);

        $testObj = new CheckSocialiteSecret;
        $res = $testObj();

        $this->assertEquals($res, 30);
    }
}
