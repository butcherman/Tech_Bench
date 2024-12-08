<?php

namespace Tests\Unit\Actions\File;

use App\Actions\File\DeleteFileData;
use App\Exceptions\Database\RecordInUseException;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DeleteFileDataUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Storage::fake();

        $fileData = Storage::putFileAs(
            'test',
            UploadedFile::fake()->image('test.png'),
            'test.png'
        );
        $fileModel = FileUpload::factory()->create([
            'folder' => 'test',
            'file_name' => 'test.png',
        ]);

        $testObj = new DeleteFileData;
        $testObj($fileModel);

        $this->assertDatabaseMissing('file_uploads', [
            'file_id' => $fileModel->file_id,
        ]);

        Storage::assertMissing($fileData);
    }

    public function test_invoke_in_use(): void
    {
        Storage::fake();

        $fileData = Storage::putFileAs(
            'test',
            UploadedFile::fake()->image('test.png'),
            'test.png'
        );
        $fileModel = FileUpload::factory()->create([
            'folder' => 'test',
            'file_name' => 'test.png',
        ]);

        CustomerFile::factory()->create(['file_id' => $fileModel->file_id]);

        $this->expectException(RecordInUseException::class);

        $testObj = new DeleteFileData;
        $testObj($fileModel);

        $this->assertDatabaseHas('file_uploads', [
            'file_id' => $fileModel->file_id,
        ]);

        Storage::assertExists($fileData);
    }
}
