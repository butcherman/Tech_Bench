<?php

namespace Tests\Unit\Services\File;

use App\Exceptions\File\FileMissingException;
use App\Models\FileUpload;
use App\Services\File\HandleFileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandleFileServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | moveFile()
    |---------------------------------------------------------------------------
    */
    public function test_move_file(): void
    {
        Storage::fake();
        Storage::putFileAs(
            $folder = 'tmp',
            UploadedFile::fake()->image('test.png'),
            $filename = 'test.png'
        );

        $upload = FileUpload::factory()->create([
            'file_name' => $filename,
            'folder' => $folder,
        ]);

        $testObj = new HandleFileService;
        $testObj->moveFile($upload, 'testFolder');

        $upload->fresh();

        $this->assertEquals($upload->folder, 'testFolder');

        Storage::assertExists('testFolder'.DIRECTORY_SEPARATOR.$filename);
    }

    public function test_move_file_same_folder(): void
    {
        Storage::fake();
        Storage::putFileAs(
            $folder = 'tmp',
            UploadedFile::fake()->image('test.png'),
            $filename = 'test.png'
        );

        $upload = FileUpload::factory()->create([
            'file_name' => $filename,
            'folder' => $folder,
        ]);

        $testObj = new HandleFileService;
        $testObj->moveFile($upload, $folder);

        $upload->fresh();

        $this->assertEquals($upload->folder, $folder);

        Storage::assertExists($folder.DIRECTORY_SEPARATOR.$filename);
    }

    public function test_move_missing_file(): void
    {
        Storage::fake();

        $filename = 'testfile.png';
        $folder = 'testFolder';

        $upload = FileUpload::factory()->create([
            'file_name' => $filename,
            'folder' => $folder,
        ]);

        $this->expectException(FileMissingException::class);

        $testObj = new HandleFileService;
        $testObj->moveFile($upload, 'newFolder');
    }

    public function test_move_file_duplicate_file(): void
    {
        Storage::fake();
        Storage::putFileAs(
            'newFolder',
            UploadedFile::fake()->image('test.png'),
            $filename = 'test.png'
        );
        Storage::putFileAs(
            $folder = 'tmp',
            UploadedFile::fake()->image('test.png'),
            $filename = 'test.png'
        );

        $upload = FileUpload::factory()->create([
            'file_name' => $filename,
            'folder' => $folder,
        ]);

        $testObj = new HandleFileService;
        $testObj->moveFile($upload, 'newFolder');

        $upload->fresh();

        $this->assertEquals($upload->folder, 'newFolder');

        Storage::assertExists('newFolder'.DIRECTORY_SEPARATOR.'test(1).png');
    }

    /*
    |---------------------------------------------------------------------------
    | doesFileExist()
    |---------------------------------------------------------------------------
    */
    public function test_does_file_exist_good(): void
    {
        Storage::fake();
        Storage::putFileAs(
            $folder = 'tmp',
            UploadedFile::fake()->image('test.png'),
            $filename = 'test.png'
        );

        $upload = FileUpload::factory()->create([
            'file_name' => $filename,
            'folder' => $folder,
        ]);

        $testObj = new HandleFileService;
        $res = $testObj->doesFileExist($upload);

        $this->assertTrue($res);
    }

    public function test_does_file_exist_bad(): void
    {
        Storage::fake();
        Storage::putFileAs(
            $folder = 'tmp',
            UploadedFile::fake()->image('test.png'),
            $filename = 'test.png'
        );

        $upload = FileUpload::factory()->create([
            'file_name' => 'test999.png',
            'folder' => $folder,
        ]);

        $testObj = new HandleFileService;
        $res = $testObj->doesFileExist($upload);

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | setPublicFlag()
    |---------------------------------------------------------------------------
    */
    public function test_set_public_flag_true(): void
    {
        $upload = FileUpload::factory()->create([
            'public' => false,
        ]);

        $testObj = new HandleFileService;
        $testObj->setPublicFlag($upload, true);

        $upload->fresh();

        $this->assertTrue($upload->public);
    }

    public function test_set_public_flag_false(): void
    {
        $upload = FileUpload::factory()->create([
            'public' => true,
        ]);

        $testObj = new HandleFileService;
        $testObj->setPublicFlag($upload, false);

        $upload->fresh();

        $this->assertFalse($upload->public);
    }
}
