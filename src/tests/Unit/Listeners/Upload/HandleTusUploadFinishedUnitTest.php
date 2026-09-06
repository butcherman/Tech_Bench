<?php

namespace Tests\Unit\Listeners\Upload;

use App\Listeners\Upload\HandleTusUploadFinished;
use ArthurPatriot\Tus\Events\FileUploadFinished;
use ArthurPatriot\Tus\Helpers\TusFile;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class HandleTusUploadFinishedUnitTest extends TestCase
{
    public function test_logo_upload_is_processed(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'tus/test-upload.png',
            metadata: [
                'name' => 'logo.png',
                'purpose' => 'logo',
                'extension' => 'png',
            ],
        );

        Log::shouldReceive('debug')->once();

        $listener = new HandleTusUploadFinished;
        $listener->handle(new FileUploadFinished($tusFile));
    }
}
