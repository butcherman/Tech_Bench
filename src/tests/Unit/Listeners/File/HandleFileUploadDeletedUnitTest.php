<?php

namespace Tests\Unit\Listeners\File;

use App\Actions\File\DeleteFileData;
use App\Events\File\FileUploadDeletedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Listeners\File\HandleFileUploadedDeletedListener;
use App\Models\CustomerFile;
use App\Models\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HandleFileUploadDeletedUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Storage::fake('local');

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

        $event = new FileUploadDeletedEvent($fileUpload->file_id);

        $svc = new DeleteFileData;
        $listener = new HandleFileUploadedDeletedListener;
        $listener->handle($event, $svc);

        $this->assertDatabaseMissing(
            'file_uploads',
            $fileUpload->makeHidden(['href', 'created_stamp'])->toArray()
        );

        Storage::disk('local')
            ->assertMissing(
                $fileUpload->folder.DIRECTORY_SEPARATOR.$fileUpload->file_name
            );
    }

    public function test_handle_in_use(): void
    {
        Storage::fake('local');

        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
            ]);
        CustomerFile::factory()->create(['file_id' => $fileUpload->file_id]);

        Storage::disk($fileUpload->disk)
            ->putFileAs(
                $fileUpload->folder,
                UploadedFile::fake()->image('testPhoto.png'),
                $fileUpload->file_name
            );

        $event = new FileUploadDeletedEvent($fileUpload->file_id);

        $this->expectException(RecordInUseException::class);

        $svc = new DeleteFileData;
        $listener = new HandleFileUploadedDeletedListener;
        $listener->handle($event, $svc);

        $this->assertDatabaseHas(
            'file_uploads',
            $fileUpload->makeHidden(['href', 'created_stamp'])->toArray()
        );

        Storage::disk('local')
            ->assertExists(
                $fileUpload->folder.DIRECTORY_SEPARATOR.$fileUpload->file_name
            );
    }

    public function test_handle_missing_file(): void
    {
        Storage::fake('local');

        $fileUpload = FileUpload::factory()
            ->create([
                'disk' => 'local',
                'file_name' => 'testPhoto.png',
            ]);

        $event = new FileUploadDeletedEvent($fileUpload->file_id);

        $svc = new DeleteFileData;
        $listener = new HandleFileUploadedDeletedListener;
        $listener->handle($event, $svc);

        $this->assertDatabaseMissing(
            'file_uploads',
            $fileUpload->makeHidden(['href', 'created_stamp'])->toArray()
        );

        Storage::disk('local')
            ->assertMissing(
                $fileUpload->folder.DIRECTORY_SEPARATOR.$fileUpload->file_name
            );
    }

    public function test_handle_array(): void
    {
        $fileUpload = FileUpload::factory()->count(5)->create();

        $event = new FileUploadDeletedEvent(
            $fileUpload->pluck('file_id')->toArray()
        );

        $svc = new DeleteFileData;
        $listener = new HandleFileUploadedDeletedListener;
        $listener->handle($event, $svc);

        foreach ($fileUpload as $fileData) {
            $this->assertDatabaseMissing(
                'file_uploads',
                $fileData->makeHidden(['href', 'created_stamp'])->toArray()
            );
        }
    }
}
