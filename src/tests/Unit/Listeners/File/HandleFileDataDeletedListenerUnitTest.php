<?php

namespace Tests\Unit\Listeners\File;

use App\Events\File\FileDataDeletedEvent;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandleFileDataDeletedListenerUnitTest extends TestCase
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

        event(new FileDataDeletedEvent($file->file_id));

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $file->file_id,
        ]);
    }

    public function test_handle_in_use(): void
    {
        $file = FileUpload::factory()->create();
        CustomerFile::factory()->create(['file_id' => $file->file_id]);

        Log::shouldReceive('notice')->once();

        event(new FileDataDeletedEvent($file->file_id));

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $file->file_id,
        ]);
    }
}
