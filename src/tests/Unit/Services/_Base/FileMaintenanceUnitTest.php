<?php

namespace Tests\Unit\Services\_Base;

use App\Services\_Base\FileMaintenanceService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileMaintenanceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getDiskFreeSpace()
    |---------------------------------------------------------------------------
    */
    public function test_get_disk_free_space(): void
    {
        $testObj = new class extends FileMaintenanceService
        {
            public function __invoke(): int
            {
                return $this->getDiskFreeSpace('local');
            }
        };

        $res = $testObj();
        $this->assertTrue($res > 0);
    }

    /*
    |---------------------------------------------------------------------------
    | getStorageDiskSize()
    |---------------------------------------------------------------------------
    */
    public function test_get_storage_disk_size(): void
    {
        Storage::fake('local');
        Storage::put('test.txt', 'this is some test text');

        $testObj = new class extends FileMaintenanceService
        {
            public function __invoke(): int
            {
                return $this->getStorageDiskSize('local');
            }
        };

        $res = $testObj();
        $this->assertEquals(22, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getFileList()
    |---------------------------------------------------------------------------
    */
    public function test_get_file_list(): void
    {
        Storage::fake('local');

        $fileList = ['.gitignore', 'testOne.txt', 'testTwo.txt'];

        foreach ($fileList as $file) {
            Storage::put($file, 'Test File For '.$file);
        }

        $testObj = new class extends FileMaintenanceService
        {
            public function __invoke(): array
            {
                return $this->getFileList(Storage::disk('local')->path(''));
            }
        };

        $res = $testObj();

        $this->assertEquals($res[0]->getFilename(), 'testOne.txt');
        $this->assertEquals($res[1]->getFilename(), 'testTwo.txt');
    }

    /*
    |---------------------------------------------------------------------------
    | getEmptyDirectories()
    |---------------------------------------------------------------------------
    */
    public function test_get_empty_directories(): void
    {
        Storage::fake('local');
        Storage::makeDirectory('empty_one');
        Storage::makeDirectory('empty_two');
        Storage::makeDirectory('not_empty');
        Storage::put('not_empty/test_file.txt', 'test file stuff');

        $testObj = new class extends FileMaintenanceService
        {
            public function __invoke(): array
            {
                return $this->getEmptyDirectories(Storage::disk('local')->path(''));
            }
        };

        $res = $testObj();

        $this->assertCount(2, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | wipeDirectory()
    |---------------------------------------------------------------------------
    */
    public function test_wipe_directory(): void
    {
        Storage::fake('local');
        Storage::makeDirectory('empty_one');
        Storage::makeDirectory('empty_two');
        Storage::makeDirectory('not_empty');
        Storage::put('not_empty/test_file.txt', 'test file stuff');
        Storage::put('empty_two/.gitignore', 'git ignore file');

        $testObj = new class extends FileMaintenanceService
        {
            public function __invoke(): void
            {
                $this->wipeDirectory(Storage::disk('local')->path(''));
            }
        };

        $testObj();

        Storage::assertExists('empty_two/.gitignore');
        Storage::assertMissing('not_empty/test_file.txt');
        Storage::assertMissing('empty_one');
        Storage::assertMissing('not_empty');
    }
}
