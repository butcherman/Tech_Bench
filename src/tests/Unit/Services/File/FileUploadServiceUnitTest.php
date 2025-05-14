<?php

namespace Tests\Unit\Services\File;

use App\Exceptions\Database\RecordInUseException;
use App\Exceptions\File\FileMissingException;
use App\Models\FileUpload;
use App\Models\TechTipFile;
use App\Services\File\FileUploadService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | deleteFileUpload()
    |---------------------------------------------------------------------------
    */
    public function test_delete_file_upload(): void
    {
        Storage::fake('local');

        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        Storage::disk('local')
            ->put($upload->folder.DIRECTORY_SEPARATOR.'test.txt', 'This is a test file');

        $testObj = new FileUploadService;
        $res = $testObj->deleteFileUpload($upload);

        $this->assertTrue($res);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);

        Storage::disk('local')
            ->assertMissing($upload->folder.DIRECTORY_SEPARATOR.'test.txt');
    }

    public function test_delete_file_upload_missing_file(): void
    {
        Storage::fake('local');

        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        $this->expectException(FileMissingException::class);

        $testObj = new FileUploadService;
        $res = $testObj->deleteFileUpload($upload);

        $this->assertTrue($res);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);

        Storage::disk('local')
            ->assertMissing($upload->folder.DIRECTORY_SEPARATOR.'test.txt');
    }

    public function test_delete_file_upload_still_in_use(): void
    {
        Storage::fake('local');

        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        TechTipFile::factory()->create(['file_id' => $upload->file_id]);

        Storage::disk('local')
            ->put($upload->folder.DIRECTORY_SEPARATOR.'test.txt', 'This is a test file');

        $this->expectException(RecordInUseException::class);

        $testObj = new FileUploadService;
        $res = $testObj->deleteFileUpload($upload);

        $this->assertTrue($res);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);

        Storage::disk('local')
            ->assertMissing($upload->folder.DIRECTORY_SEPARATOR.'test.txt');
    }

    /*
    |---------------------------------------------------------------------------
    | deleteFileById()
    |---------------------------------------------------------------------------
    */
    public function test_delete_file_by_id(): void
    {
        Storage::fake('local');

        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);
        $upload2 = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test2.txt',
        ]);

        Storage::disk('local')
            ->put($upload->folder.DIRECTORY_SEPARATOR.'test.txt', 'This is a test file');

        Storage::disk('local')
            ->put($upload->folder.DIRECTORY_SEPARATOR.'test2.txt', 'This is a test file');

        $testObj = new FileUploadService;
        $testObj->deleteFileByID([$upload->file_id, $upload2->file_id]);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);
        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload2->file_id,
        ]);

        Storage::disk('local')
            ->assertMissing($upload->folder.DIRECTORY_SEPARATOR.'test.txt');

        Storage::disk('local')
            ->assertMissing($upload->folder.DIRECTORY_SEPARATOR.'test2.txt');
    }
}
