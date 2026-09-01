<?php

namespace Tests\Unit\Listeners\Upload;

use App\Listeners\Upload\HandleTusUploadFinished;
use App\Services\Admin\ApplicationSettingsService;
use ArthurPatriot\Tus\Events\FileUploadFinished;
use ArthurPatriot\Tus\Helpers\TusFile;
use Mockery;
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

        $service = Mockery::mock(ApplicationSettingsService::class);

        $service->shouldReceive('updateLogo')
            ->once()
            ->with($tusFile);

        $listener = new HandleTusUploadFinished($service);

        $listener->handle(new FileUploadFinished($tusFile));
    }

    public function test_non_logo_upload_is_ignored(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'tus/test-upload.txt',
            metadata: [
                'name' => 'test.txt',
                'purpose' => 'customer-file',
                'extension' => 'txt',
            ],
        );

        $service = Mockery::mock(ApplicationSettingsService::class);

        $service->shouldNotReceive('updateLogo');

        $listener = new HandleTusUploadFinished($service);

        $listener->handle(new FileUploadFinished($tusFile));
    }

    public function test_upload_without_purpose_is_ignored(): void
    {
        $tusFile = new TusFile(
            id: 'test-upload',
            path: 'tus/test-upload.png',
            metadata: [
                'name' => 'test.png',
                'extension' => 'png',
            ],
        );

        $service = Mockery::mock(ApplicationSettingsService::class);

        $service->shouldNotReceive('updateLogo');

        $listener = new HandleTusUploadFinished($service);

        $listener->handle(new FileUploadFinished($tusFile));
    }
}
