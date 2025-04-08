<?php

namespace Tests\Unit\Services\Maintenance;

use App\Models\FileUpload;
use App\Models\TechTipFile;
use App\Services\Maintenance\FileMaintenanceService;
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
        $testObj = new FileMaintenanceService;

        $res = $testObj->getDiskFreeSpace('local');

        $this->assertTrue($res > 0);
        $this->assertIsNumeric($res);
    }

    public function test_get_disk_free_space_as_string(): void
    {
        $testObj = new FileMaintenanceService;

        $res = $testObj->getDiskFreeSpace('local', true);

        $this->assertIsString($res);
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

        $testObj = new FileMaintenanceService;

        $res = $testObj->getStorageDiskSize('local');

        $this->assertEquals(22, $res);
        $this->assertIsNumeric($res);
    }

    public function test_get_storage_disk_size_as_string(): void
    {
        Storage::fake('local');
        Storage::put('test.txt', 'this is some test text');

        $testObj = new FileMaintenanceService;

        $res = $testObj->getStorageDiskSize('local', true);

        $this->assertIsString($res);
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
            Storage::put($file, 'Test File For ' . $file);
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

        $testObj = new FileMaintenanceService;

        $res = $testObj->getEmptyDirectories(Storage::disk('local')->path(''));

        $this->assertCount(2, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getMissingFiles()
    |---------------------------------------------------------------------------
    */
    public function test_get_missing_files(): void
    {
        Storage::fake('local');
        Storage::put('file_1.txt', 'test file one');
        Storage::put('file_2.txt', 'test file one');

        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => '',
            'file_name' => 'file_1.txt'
        ]);
        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => '',
            'file_name' => 'file_2.txt'
        ]);
        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => '',
            'file_name' => 'file_3.txt'
        ]);
        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => '',
            'file_name' => 'file_4.txt'
        ]);

        $testObj = new FileMaintenanceService;
        $res = $testObj->getMissingFiles();

        $this->assertCount(2, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getOrphanedFiles()
    |---------------------------------------------------------------------------
    */
    public function test_get_orphaned_files(): void
    {
        Storage::fake('local');
        Storage::disk('local')->makeDirectory('test');
        Storage::disk('local')->makeDirectory('public/images');
        Storage::disk('local')->put('public/images/test.txt', 'test image');
        Storage::disk('local')->put('test/file_1.txt', 'test file one');
        Storage::disk('local')->put('test/file_2.txt', 'test file one');
        Storage::disk('local')->put('test/file_3.txt', 'test file one');
        Storage::disk('local')->put('test/file_4.txt', 'test file one');

        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => 'app/storage/framework/testing/disks/local/test',
            'file_name' => 'file_1.txt'
        ]);
        FileUpload::factory()->create([
            'disk' => 'local',
            'folder' => 'app/storage/framework/testing/disks/local/test',
            'file_name' => 'file_2.txt'
        ]);

        $testObj = new FileMaintenanceService;
        $res = $testObj->getOrphanedFiles();

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

    /*
    |---------------------------------------------------------------------------
    | forceDeleteFileUpload()
    |---------------------------------------------------------------------------
    */
    public function test_force_delete_file_upload(): void
    {
        $testFile = TechTipFile::factory()->create();

        $testObj = new FileMaintenanceService;
        $testObj->forceDeleteFileUpload(FileUpload::find($testFile->file_id));

        $this->assertDatabaseMissing('tech_tip_files', $testFile->toArray());
        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $testFile->file_id,
        ]);
    }
}
