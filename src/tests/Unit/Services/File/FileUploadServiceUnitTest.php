<?php

namespace Tests\Unit\Services\File;

use App\Exceptions\Database\RecordInUseException;
use App\Models\FileUpload;
use App\Models\TechTipFile;
use App\Services\File\FileUploadService;
use Mockery\MockInterface;
use Tests\TestCase;

class FileUploadServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | moveUploadedFile()
    |---------------------------------------------------------------------------
    */
    public function test_move_uploaded_file(): void
    {
        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        /** @var FileUploadService */
        $testObj = $this->partialMock(FileUploadService::class, function (MockInterface $mock) {
            $mock->shouldReceive('moveDiskFile')->once();
        });

        $testObj->moveUploadedFile($upload, 'new_folder');

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $upload->file_id,
            'folder' => 'new_folder',
        ]);
    }

    public function test_move_uploaded_file_new_disk(): void
    {
        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        /** @var FileUploadService */
        $testObj = $this->partialMock(FileUploadService::class, function (MockInterface $mock) {
            $mock->shouldReceive('moveDiskFile')->once();
        });

        $testObj->moveUploadedFile($upload, 'new_folder', 'tips');

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $upload->file_id,
            'disk' => 'tips',
            'folder' => 'new_folder',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | deleteFileUpload()
    |---------------------------------------------------------------------------
    */
    public function test_delete_file_upload(): void
    {
        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        /** @var FileUploadService */
        $testObj = $this->partialMock(FileUploadService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteDiskFile')->once();
        });

        $res = $testObj->deleteFileUpload($upload);

        $this->assertTrue($res);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);
    }

    public function test_delete_file_upload_missing_file(): void
    {
        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        /** @var FileUploadService */
        $testObj = $this->partialMock(FileUploadService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteDiskFile')->once();
        });

        $res = $testObj->deleteFileUpload($upload);

        $this->assertTrue($res);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);
    }

    public function test_delete_file_upload_still_in_use(): void
    {
        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);

        TechTipFile::factory()->create(['file_id' => $upload->file_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new FileUploadService;
        $res = $testObj->deleteFileUpload($upload);

        $this->assertTrue($res);

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $upload->file_id,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | deleteFileById()
    |---------------------------------------------------------------------------
    */
    public function test_delete_file_by_id(): void
    {
        $upload = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test.txt',
        ]);
        $upload2 = FileUpload::factory()->create([
            'disk' => 'local',
            'file_name' => 'test2.txt',
        ]);

        /** @var FileUploadService */
        $testObj = $this->partialMock(FileUploadService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteDiskFile')->twice();
        });

        $testObj->deleteFileByID([$upload->file_id, $upload2->file_id]);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload->file_id,
        ]);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $upload2->file_id,
        ]);
    }
}
