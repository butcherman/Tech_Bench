<?php

namespace Tests\Unit\Actions\Misc;

use App\Actions\Misc\ValidateFileForDownload;
use App\Exceptions\File\FileMissingException;
use App\Exceptions\File\IncorrectFilenameException;
use App\Exceptions\File\PrivateFileException;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ValidateFileForDownloadUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
            ]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $testObj = new ValidateFileForDownload;
        $res = $testObj($fileUpload, $fileUpload->file_name, $user);

        $this->assertTrue($res);
    }

    public function test_handle_incorrect_filename(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
            ]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $this->expectException(IncorrectFilenameException::class);

        $testObj = new ValidateFileForDownload;
        $testObj($fileUpload, 'randomName.png', $user);
    }

    public function test_handle_file_missing(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $fileUpload = FileUpload::create([
            'disk' => 'local',
            'file_name' => 'testRandomPhoto.png',
            'folder' => 'random_folder',
            'file_size' => 1,
            'public' => false,
        ]);

        $this->expectException(FileMissingException::class);

        $testObj = new ValidateFileForDownload;
        $testObj($fileUpload, $fileUpload->file_name, $user);
    }

    public function test_handle_private_file_ok(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
                'public' => false,
            ]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $testObj = new ValidateFileForDownload;
        $res = $testObj($fileUpload, $fileUpload->file_name, $user);

        $this->assertTrue($res);
    }

    public function test_handle_private_file_fail(): void
    {
        Storage::fake('local');

        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
                'public' => false,
            ]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $this->expectException(PrivateFileException::class);

        $testObj = new ValidateFileForDownload;
        $testObj($fileUpload, $fileUpload->file_name);
    }

    public function test_handle_public_file_as_user(): void
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
                'public' => true,
            ]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $testObj = new ValidateFileForDownload;
        $res = $testObj($fileUpload, $fileUpload->file_name, $user);

        $this->assertTrue($res);
    }

    public function test_handle_public_file_as_guest(): void
    {
        Storage::fake('local');

        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
                'public' => true,
            ]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $testObj = new ValidateFileForDownload;
        $res = $testObj($fileUpload, $fileUpload->file_name);

        $this->assertTrue($res);
    }
}
