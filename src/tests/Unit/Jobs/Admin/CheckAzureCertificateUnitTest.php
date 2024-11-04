<?php

namespace Tests\Unit\Jobs\Admin;

use App\Actions\Socialite\CheckSocialiteSecret;
use App\Jobs\Admin\CheckAzureCertificateJob;
use App\Mail\Admin\AzureCertificateExpiredMail;
use App\Mail\Admin\AzureCertificateExpiresSoonMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckAzureCertificateUnitTest extends TestCase
{
    public function test_handle_thirty_days_out(): void
    {
        config(['services.azure.allow_login' => true]);
        config(['services.azure.secret_expires' => Carbon::now()->addDays(31)]);

        Mail::fake();

        $testObj = new CheckAzureCertificateJob;
        $testObj->handle(new CheckSocialiteSecret);

        Mail::assertQueued(AzureCertificateExpiresSoonMail::class);
    }

    public function test_handle_fifty_days_out(): void
    {
        config(['services.azure.allow_login' => true]);
        config(['services.azure.secret_expires' => Carbon::now()->addDays(51)]);

        Mail::fake();

        $testObj = new CheckAzureCertificateJob;
        $testObj->handle(new CheckSocialiteSecret);

        Mail::assertNotQueued(AzureCertificateExpiresSoonMail::class);
    }

    public function test_handle_expired(): void
    {
        config(['services.azure.allow_login' => true]);
        config(['services.azure.secret_expires' => Carbon::now()->subDays(51)]);

        Mail::fake();

        $testObj = new CheckAzureCertificateJob;
        $testObj->handle(new CheckSocialiteSecret);

        Mail::assertQueued(AzureCertificateExpiredMail::class);
    }

    public function test_handle_feature_disabled(): void
    {
        config(['services.azure.allow_login' => false]);

        Mail::fake();

        $testObj = new CheckAzureCertificateJob;
        $testObj->handle(new CheckSocialiteSecret);

        Mail::assertNotQueued(AzureCertificateExpiredMail::class);
    }
}
