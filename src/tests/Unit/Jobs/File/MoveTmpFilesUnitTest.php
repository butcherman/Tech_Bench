<?php

namespace Tests\Unit\Jobs\File;

use App\Jobs\File\MoveTmpFilesJob;
use App\Models\FileUpload;
use App\Services\File\HandleFileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MoveTmpFilesUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        Storage::fake();

        $fileList = FileUpload::factory()->count(5)->create();

        foreach ($fileList as $file) {
            Storage::putFileAs(
                $file->folder,
                UploadedFile::fake()->image($file->file_name),
                $file->file_name
            );
        }

        $idList = $fileList->pluck('file_id')->toArray();

        $svc = new HandleFileService;
        $job = new MoveTmpFilesJob($idList, 'test_folder', false);
        $job->handle($svc);

        foreach ($fileList as $file) {
            $refetch = FileUpload::find($file->file_id);

            $this->assertEquals('test_folder', $refetch->folder);
            Storage::assertExists(
                'test_folder'.DIRECTORY_SEPARATOR.$refetch->file_name
            );
        }
    }
}
