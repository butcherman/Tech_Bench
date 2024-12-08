<?php

namespace Tests\Unit\Services\File;

use App\Models\FileUpload;
use App\Services\File\FileMaintenanceService;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileMaintenanceServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getDiskSpace()
    |---------------------------------------------------------------------------
    */
    public function test_get_disk_space(): void
    {
        $testObj = new FileMaintenanceService;
        $res = $testObj->getDiskSpace();

        $this->assertArrayHasKey('free_space', $res);
        $this->assertArrayHasKey('used_space', $res);
        $this->assertArrayHasKey('total_space', $res);
        $this->assertArrayHasKey('percentage', $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getDirectoryList()
    |---------------------------------------------------------------------------
    */
    public function test_get_directory_list(): void
    {
        Storage::fake();

        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_2');

        $shouldBe = Storage::allDirectories();

        $testObj = new FileMaintenanceService;
        $res = $testObj->getDirectoryList();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getEmptyDirectories
    |---------------------------------------------------------------------------
    */
    public function test_get_empty_directories(): void
    {
        Storage::fake();

        Storage::makeDirectory('chunks');
        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_2');
        Storage::putFile('test_2', UploadedFile::fake()->image('image.png'));

        $shouldBe = ['test_one'];

        $testObj = new FileMaintenanceService;
        $res = $testObj->getEmptyDirectories(Storage::allDirectories(), false);

        $this->assertEquals($shouldBe, $res);

        Storage::assertExists('test_one');
    }

    public function test_get_empty_directories_fix_on(): void
    {
        Storage::fake();

        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_2');
        Storage::putFile('test_2', UploadedFile::fake()->image('image.png'));

        $shouldBe = ['test_one'];

        $testObj = new FileMaintenanceService;
        $res = $testObj->getEmptyDirectories(Storage::allDirectories(), true);

        $this->assertEquals($shouldBe, $res);

        Storage::assertMissing('test_one');
    }

    /*
    |---------------------------------------------------------------------------
    | getFileList()
    |---------------------------------------------------------------------------
    */
    public function test_get_file_list(): void
    {
        Storage::fake();

        Storage::makeDirectory('public');
        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_2');
        Storage::putFileAs('public', UploadedFile::fake()->image('image2.png'), 'image2.png');
        Storage::putFileAs('test_2', UploadedFile::fake()->image('image.png'), 'image.png');
        Storage::putFileAs('test_2', new File('.gitignore'), '.gitignore');

        $testObj = new FileMaintenanceService;
        $res = $testObj->getFileList();

        // dd($res);

        $this->assertCount(1, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | findMissingFiles()
    |---------------------------------------------------------------------------
    */
    public function test_find_missing_files(): void
    {
        FileUpload::factory()->count(10)->create();

        $testObj = new FileMaintenanceService;
        $res = $testObj->findMissingFiles(false, null);

        $this->assertCount(10, $res);
    }

    public function test_find_missing_files_fix_on(): void
    {
        FileUpload::factory()->count(10)->create();

        $testObj = new FileMaintenanceService;
        $res = $testObj->findMissingFiles(true, null);

        $this->assertCount(10, $res);

        $this->assertDatabaseCount('file_uploads', 0);
    }

    /*
    |---------------------------------------------------------------------------
    | findOrphanedFiles()
    |---------------------------------------------------------------------------
    */
    public function test_find_orphaned_files(): void
    {
        Storage::fake();

        Storage::makeDirectory('public');
        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_2');
        Storage::putFileAs(
            'test_2',
            UploadedFile::fake()->image('image.png'),
            'image.png'
        );

        $testObj = new FileMaintenanceService;
        $res = $testObj->findOrphanedFiles(Storage::allFiles(), false, null);

        $this->assertCount(1, $res);

        Storage::assertExists('test_2/image.png');
    }

    public function test_find_orphaned_files_fix_on(): void
    {
        Storage::fake();

        Storage::makeDirectory('public');
        Storage::makeDirectory('test_one');
        Storage::makeDirectory('test_2');
        Storage::putFileAs(
            'test_2',
            UploadedFile::fake()->image('image.png'),
            'image.png'
        );

        $fileList = FileUpload::factory()->count(5)->create();
        $fileList->each(function ($file) {
            Storage::putFileAs(
                $file->folder,
                UploadedFile::fake()->image($file->file_name),
                $file->file_name
            );
        });

        $testObj = new FileMaintenanceService;
        $res = $testObj->findOrphanedFiles(Storage::allFiles(), true, null);

        $this->assertCount(1, $res);

        Storage::assertMissing('test_2/image.png');
    }
}
