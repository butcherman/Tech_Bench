<?php

namespace Tests\Unit\Actions\Socialite;

use App\Actions\Socialite\CheckSocialiteSecret;
use App\Mail\Admin\AzureCertificateExpiredMail;
use App\Mail\Admin\AzureCertificateExpiresSoonMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
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
        Mail::fake();

        config(['services.azure.secret_expires' => Carbon::now()->addDays(31)]);

        $testObj = new CheckSocialiteSecret;
        $testObj();

        Mail::assertQueued(AzureCertificateExpiresSoonMail::class);
    }

    public function test_handle_fifty_days_out(): void
    {
        Mail::fake();

        config(['services.azure.secret_expires' => Carbon::now()->addDays(51)]);

        $testObj = new CheckSocialiteSecret;
        $testObj();

        Mail::assertNotQueued(AzureCertificateExpiresSoonMail::class);
    }

    public function test_handle_expired(): void
    {
        Mail::fake();

        config(['services.azure.secret_expires' => Carbon::now()->subDays(51)]);

        $testObj = new CheckSocialiteSecret;
        $testObj();

        Mail::assertQueued(AzureCertificateExpiredMail::class);
    }
}
