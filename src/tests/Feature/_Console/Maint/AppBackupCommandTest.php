<?php

namespace Tests\Feature\_Console\Maint;

use App\Jobs\Maintenance\RunBackupJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AppBackupCommandTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Queue::fake();

        $this->artisan('app:backup')
            ->assertExitCode(0);

        Queue::assertPushed(RunBackupJob::class);
    }
}
