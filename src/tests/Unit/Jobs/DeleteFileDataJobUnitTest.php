<?php

namespace Tests\Unit\Jobs;

use App\Jobs\File\DeleteFileDataJob;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteFileDataJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Storage::fake();

        $file = FileUpload::factory()->create();

        Storage::shouldReceive('disk->delete')->once();

        DeleteFileDataJob::dispatch($file);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $file->file_id,
        ]);
    }

    public function test_handle_in_use(): void
    {
        $file = FileUpload::factory()->create();
        CustomerFile::factory()->create(['file_id' => $file->file_id]);

        Log::shouldReceive('notice')->once();

        DeleteFileDataJob::dispatch($file);

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $file->file_id,
        ]);
    }
}
