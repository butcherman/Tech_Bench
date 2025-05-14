<?php

namespace Tests\Unit\Jobs\Maintenance;

use App\Jobs\Maintenance\CheckAzureCertificateJob;
use App\Mail\Admin\AzureCertificateExpiredMail;
use App\Mail\Admin\AzureCertificateExpiresSoonMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckAzureCertificateJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle_thirty_days_out(): void
    {
        Mail::fake();

        config(['services.azure.allow_login' => true]);
        config(['services.azure.secret_expires' => Carbon::now()->addDays(31)]);

        CheckAzureCertificateJob::dispatch();

        Mail::assertQueued(AzureCertificateExpiresSoonMail::class);
    }

    public function test_handle_fifty_days_out(): void
    {
        Mail::fake();

        config(['services.azure.allow_login' => true]);
        config(['services.azure.secret_expires' => Carbon::now()->addDays(51)]);

        CheckAzureCertificateJob::dispatch();

        Mail::assertNotQueued(AzureCertificateExpiresSoonMail::class);
    }

    public function test_handle_expired(): void
    {
        Mail::fake();

        config(['services.azure.allow_login' => true]);
        config(['services.azure.secret_expires' => Carbon::now()->subDays(51)]);

        CheckAzureCertificateJob::dispatch();

        Mail::assertQueued(AzureCertificateExpiredMail::class);
    }

    public function test_handle_feature_disabled(): void
    {
        Mail::fake();

        config(['services.azure.allow_login' => false]);

        CheckAzureCertificateJob::dispatch();

        Mail::assertNothingQueued();
    }
}
