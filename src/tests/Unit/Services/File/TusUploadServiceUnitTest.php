<?php

namespace Tests\Unit\Services\File;

use App\Services\File\TusUploadService;
use ArthurPatriot\Tus\Exceptions\FileNotFoundException;
use ArthurPatriot\Tus\Facades\Tus;
use ArthurPatriot\Tus\Helpers\TusFile;
use ArthurPatriot\Tus\Helpers\TusUploadMetadataManager;
use ErrorException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class TusUploadServiceUnitTest extends TestCase
{
    private TusUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TusUploadService;
    }

    public function test_get_completed_upload_returns_upload(): void
    {
        $metadata = Mockery::mock(TusUploadMetadataManager::class);
        $storage = Mockery::mock(Filesystem::class);

        $metadata
            ->shouldReceive('read')
            ->once()
            ->with('abc123')
            ->andReturn([
                'size' => 1024,
                'extension' => 'txt',
            ]);

        $storage
            ->shouldReceive('exists')
            ->once()
            ->with('abc123.txt')
            ->andReturnTrue();

        $storage
            ->shouldReceive('size')
            ->once()
            ->with('abc123.txt')
            ->andReturn(1024);

        Tus::shouldReceive('metadata')
            ->once()
            ->andReturn($metadata);

        Tus::shouldReceive('path')
            ->once()
            ->with('abc123', 'txt')
            ->andReturn('abc123.txt');

        Tus::shouldReceive('storage')
            ->twice()
            ->andReturn($storage);

        $result = $this->service->getCompletedUpload('abc123');

        $this->assertInstanceOf(TusFile::class, $result);
        $this->assertSame('abc123', $result->id);
        $this->assertSame('abc123.txt', $result->path);
        $this->assertSame(1024, $result->metadata['size']);
    }

    public function test_get_completed_upload_metadata_does_not_exist(): void
    {
        $metadata = Mockery::mock(TusUploadMetadataManager::class);

        $metadata
            ->shouldReceive('read')
            ->once()
            ->with('abc123')
            ->andThrow(new FileNotFoundException);

        Tus::shouldReceive('metadata')
            ->once()
            ->andReturn($metadata);

        $this->expectException(ValidationException::class);

        $this->service->getCompletedUpload('abc123');
    }

    public function test_get_completed_upload_file_does_not_exist(): void
    {
        $metadata = Mockery::mock(TusUploadMetadataManager::class);
        $storage = Mockery::mock(Filesystem::class);

        $metadata
            ->shouldReceive('read')
            ->once()
            ->with('abc123')
            ->andReturn([
                'size' => 1024,
                'extension' => 'txt',
            ]);

        $storage
            ->shouldReceive('exists')
            ->once()
            ->with('abc123.txt')
            ->andThrow(FileNotFoundException::class);

        Tus::shouldReceive('metadata')
            ->once()
            ->andReturn($metadata);

        Tus::shouldReceive('path')
            ->once()
            ->with('abc123', 'txt')
            ->andReturn('abc123.txt');

        Tus::shouldReceive('storage')
            ->once()
            ->andReturn($storage);

        $this->expectException(ValidationException::class);

        $this->service->getCompletedUpload('abc123');
    }

    public function test_get_completed_upload_expected_size_is_zero(): void
    {
        $metadata = Mockery::mock(TusUploadMetadataManager::class);
        $storage = Mockery::mock(Filesystem::class);

        $metadata
            ->shouldReceive('read')
            ->once()
            ->with('abc123')
            ->andReturn([
                'size' => 0,
                'extension' => 'txt',
            ]);

        $storage
            ->shouldReceive('exists')
            ->once()
            ->with('abc123.txt')
            ->andReturnTrue();

        $storage
            ->shouldReceive('size')
            ->once()
            ->with('abc123.txt')
            ->andReturn(0);

        Tus::shouldReceive('metadata')
            ->once()
            ->andReturn($metadata);

        Tus::shouldReceive('path')
            ->once()
            ->with('abc123', 'txt')
            ->andReturn('abc123.txt');

        Tus::shouldReceive('storage')
            ->twice()
            ->andReturn($storage);

        $this->expectException(ValidationException::class);

        $this->service->getCompletedUpload('abc123');
    }

    public function test_get_completed_upload_file_size_does_not_match(): void
    {
        $metadata = Mockery::mock(TusUploadMetadataManager::class);
        $storage = Mockery::mock(Filesystem::class);

        $metadata
            ->shouldReceive('read')
            ->once()
            ->with('abc123')
            ->andReturn([
                'size' => 1024,
                'extension' => 'txt',
            ]);

        $storage
            ->shouldReceive('exists')
            ->once()
            ->with('abc123.txt')
            ->andReturnTrue();

        $storage
            ->shouldReceive('size')
            ->once()
            ->with('abc123.txt')
            ->andReturn(512);

        Tus::shouldReceive('metadata')
            ->once()
            ->andReturn($metadata);

        Tus::shouldReceive('path')
            ->once()
            ->with('abc123', 'txt')
            ->andReturn('abc123.txt');

        Tus::shouldReceive('storage')
            ->twice()
            ->andReturn($storage);

        $this->expectException(ValidationException::class);

        $this->service->getCompletedUpload('abc123');
    }

    /*
    |---------------------------------------------------------------------------
    | validateMimeType()
    |---------------------------------------------------------------------------
    */
    public function test_validate_mime_type_allowed(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'tus-test-');

        file_put_contents($file, 'This is a test file.');

        $upload = new TusFile(
            'abc123',
            'abc123.txt',
            ['size' => filesize($file)]
        );

        $storage = Mockery::mock(Filesystem::class);

        $storage
            ->shouldReceive('path')
            ->once()
            ->with('abc123.txt')
            ->andReturn($file);

        Tus::shouldReceive('storage')
            ->once()
            ->andReturn($storage);

        $result = $this->service->validateMimeType(
            $upload,
            ['text/plain']
        );

        unlink($file);

        $this->assertTrue($result);
    }

    public function test_validate_mime_type_not_allowed(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'tus-test-');

        file_put_contents($file, 'This is a test file.');

        $upload = new TusFile(
            'abc123',
            'abc123.txt',
            ['size' => filesize($file)]
        );

        $storage = Mockery::mock(Filesystem::class);

        $storage
            ->shouldReceive('path')
            ->once()
            ->with('abc123.txt')
            ->andReturn($file);

        Tus::shouldReceive('storage')
            ->once()
            ->andReturn($storage);

        $result = $this->service->validateMimeType(
            $upload,
            ['application/pdf']
        );

        unlink($file);

        $this->assertFalse($result);
    }

    public function test_validate_mime_type_file_cannot_be_read(): void
    {
        $upload = new TusFile(
            'abc123',
            'abc123.txt',
            ['size' => 123]
        );

        $storage = Mockery::mock(Filesystem::class);

        $storage
            ->shouldReceive('path')
            ->once()
            ->with('abc123.txt')
            ->andReturn('/path/that/does/not/exist.txt');

        Tus::shouldReceive('storage')
            ->once()
            ->andReturn($storage);

        $this->expectException(ErrorException::class);

        $result = $this->service->validateMimeType(
            $upload,
            ['text/plain']
        );

        $this->assertFalse($result);
    }

    /*
    |---------------------------------------------------------------------------
    | finalizeUpload()
    |---------------------------------------------------------------------------
    */
    public function test_finalize_upload_moves_file_to_destination_and_deletes_upload(): void
    {
        Storage::fake('public');

        $file = tempnam(sys_get_temp_dir(), 'tus-test-');

        file_put_contents($file, 'This is a test file.');

        $upload = new TusFile(
            'abc123',
            'abc123.txt',
            ['size' => filesize($file)]
        );

        $storage = Mockery::mock(Filesystem::class);

        $storage
            ->shouldReceive('path')
            ->once()
            ->with('abc123.txt')
            ->andReturn($file);

        $storage
            ->shouldReceive('delete')
            ->once()
            ->with('abc123.txt');

        $storage
            ->shouldReceive('delete')
            ->once()
            ->with('abc123.json');

        Tus::shouldReceive('storage')
            ->andReturn($storage);

        Tus::shouldReceive('path')
            ->once()
            ->with('abc123', 'json')
            ->andReturn('abc123.json');

        $destination = 'abc123.txt';

        $this->service->finalizeUpload($upload, $destination);

        Storage::disk('public')->assertExists($destination);

        $this->assertFileDoesNotExist($file);
    }
}
