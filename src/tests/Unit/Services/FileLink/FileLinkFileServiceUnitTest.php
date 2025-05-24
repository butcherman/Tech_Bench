<?php

namespace Tests\Unit\Services\FileLink;

use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use App\Services\FileLink\FileLinkFileService;
use Mockery\MockInterface;
use Tests\TestCase;

class FileLinkFileServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | checkLinkFileFolder()
    |---------------------------------------------------------------------------
    */
    public function test_check_link_file_folder(): void
    {
        $link = FileLink::factory()->create();
        $fileList = FileUpload::factory()->count(5)->create([
            'disk' => 'fileLinks',
            'folder' => 'tmp',
        ]);
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'yer mom',
        ]);

        $link->Files()->attach($fileList->pluck('file_id')->toArray(), [
            'timeline_id' => $timeline->timeline_id,

        ]);

        /** @var FileLinkFileService */
        $testObj = $this->partialMock(
            FileLinkFileService::class,
            function (MockInterface $mock) use ($link) {
                $mock->shouldReceive('moveUploadedFile')
                    ->times(5)
                    ->with(FileUpload::class, $link->link_id);
            }
        );

        $testObj->checkLinkFileFolder($link);
    }

    /*
    |---------------------------------------------------------------------------
    | checkLinkFilePermission()
    |---------------------------------------------------------------------------
    */
    public function test_check_link_file_permissions(): void
    {
        $link = FileLink::factory()->create();
        $fileList = FileUpload::factory()->count(5)->create([
            'disk' => 'fileLinks',
            'folder' => 'tmp',
            'public' => false,
        ]);
        $timeline = FileLinkTimeline::create([
            'link_id' => $link->link_id,
            'added_by' => 'yer mom',
        ]);

        $link->Files()->attach($fileList->pluck('file_id')->toArray(), [
            'timeline_id' => $timeline->timeline_id,

        ]);

        $testObj = new FileLinkFileService;
        $testObj->checkLinkFilePermission($link);

        foreach ($fileList as $file) {
            $this->assertDatabaseHas('file_uploads', [
                'file_id' => $file->file_id,
                'public' => true,
            ]);
        }
    }
}
