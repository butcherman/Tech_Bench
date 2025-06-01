<?php

namespace Tests\Unit\Services\File;

use App\Exceptions\File\FileMissingException;
use App\Services\File\FileStorageService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | moveDiskFile()
    |---------------------------------------------------------------------------
    */
    public function test_move_disk_file(): void
    {
        Storage::fake('local');

        $file = 'test.txt';
        $oldFolder = 'tmp_folder';
        $newFolder = 'newFolder';

        Storage::disk('local')
            ->put($oldFolder . DIRECTORY_SEPARATOR . $file, 'This is a test file');

        $testObj = new FileStorageService;
        $testObj->moveDiskFile(
            'local',
            $oldFolder . DIRECTORY_SEPARATOR . $file,
            $newFolder . DIRECTORY_SEPARATOR . $file
        );

        Storage::disk('local')
            ->assertMissing($oldFolder . DIRECTORY_SEPARATOR . $file);
        Storage::disk('local')
            ->assertExists($newFolder . DIRECTORY_SEPARATOR . $file);
    }

    public function test_move_disk_file_duplicate_file(): void
    {
        Storage::fake('local');

        $file = 'test.txt';
        $oldFolder = 'tmp_folder';
        $newFolder = 'newFolder';

        Storage::disk('local')
            ->put($oldFolder . DIRECTORY_SEPARATOR . $file, 'This is a test file');
        Storage::disk('local')
            ->put($newFolder . DIRECTORY_SEPARATOR . $file, 'This is a test file');

        $testObj = new FileStorageService;
        $testObj->moveDiskFile(
            'local',
            $oldFolder . DIRECTORY_SEPARATOR . $file,
            $newFolder . DIRECTORY_SEPARATOR . $file
        );

        Storage::disk('local')
            ->assertMissing($oldFolder . DIRECTORY_SEPARATOR . $file);
        Storage::disk('local')
            ->assertExists($newFolder . DIRECTORY_SEPARATOR . $file);
        Storage::disk('local')
            ->assertExists($newFolder . DIRECTORY_SEPARATOR . 'test(1).txt');
    }

    public function test_move_disk_file_new_disk(): void
    {
        Storage::fake('local');
        Storage::fake('customers');

        $file = 'test.txt';
        $oldFolder = 'tmp_folder2';
        $newFolder = 'newFolder2';

        Storage::disk('local')
            ->put($oldFolder . DIRECTORY_SEPARATOR . $file, 'This is a test file');

        $testObj = new FileStorageService;
        $testObj->moveDiskFile(
            'local',
            $oldFolder . DIRECTORY_SEPARATOR . $file,
            $newFolder . DIRECTORY_SEPARATOR . $file,
            'customers'
        );

        Storage::disk('local')
            ->assertMissing($oldFolder . DIRECTORY_SEPARATOR . $file);
        Storage::disk('customers')
            ->assertExists($newFolder . DIRECTORY_SEPARATOR . $file);
    }

    public function test_move_disk_file_file_missing(): void
    {
        Storage::fake('local');

        $file = 'test.txt';
        $oldFolder = 'tmp_folder';
        $newFolder = 'newFolder';

        $this->expectException(FileMissingException::class);

        $testObj = new FileStorageService;
        $testObj->moveDiskFile(
            'local',
            $oldFolder . DIRECTORY_SEPARATOR . $file,
            $newFolder . DIRECTORY_SEPARATOR . $file
        );
    }

    /*
    |---------------------------------------------------------------------------
    | deleteDiskFile()
    |---------------------------------------------------------------------------
    */
    public function test_delete_disk_file(): void
    {
        Storage::fake('local');

        $file = 'tmp_folder/test.txt';

        Storage::disk('local')
            ->put($file, 'This is a test file');

        $testObj = new FileStorageService;
        $testObj->deleteDiskFile('local', $file);

        Storage::disk('local')->assertMissing($file);
    }
}
