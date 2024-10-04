<?php

namespace Tests\Feature\Commands\Maint;

use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AppBackupTest extends TestCase
{
    /**
     * Handle Method
     */
    public function test_handle()
    {
        Queue::fake();
        Bus::fake();

        $this->artisan('app:backup')->assertExitCode(0);

        Queue::assertNothingPushed();
        Bus::assertDispatched(RunBackupJob::class);
    }
}
