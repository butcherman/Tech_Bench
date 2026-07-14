<?php

namespace Tests\Feature\_Console\Customer;

use App\Jobs\File\DeleteFileDataJob;
use App\Models\FileUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class DataTableImportCleanupTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | Handle Method
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Bus::fake();

        FileUpload::factory()->count(10)->create([
            'disk' => 'customers',
            'folder' => 'table_upload',
            'updated_at' => Carbon::now()->subHours(5),
        ]);
        FileUpload::factory()->count(2)->create([
            'disk' => 'customers',
            'folder' => 'table_upload',
        ]);

        $this->artisan('app:import-cleanup')
            ->expectsOutput('Deleted 10 upload files')
            ->assertExitCode(0);

        Bus::assertDispatchedTimes(DeleteFileDataJob::class, 10);

    }
}
