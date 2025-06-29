<?php

namespace Tests\Unit\Listeners\File;

use App\Events\File\FileDataDeletedEvent;
use App\Jobs\File\DeleteFileDataJob;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Queue;
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
        Queue::fake();

        $file = FileUpload::factory()->create();

        event(new FileDataDeletedEvent($file->file_id));

        Queue::assertPushed(DeleteFileDataJob::class);
    }
}
