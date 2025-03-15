<?php

namespace Tests\Unit\Models;

use App\Models\FileLink;
use App\Models\FileLinkFile;
use App\Models\FileLinkNote;
use App\Models\FileLinkTimeline;
use App\Models\FileUpload;
use Tests\TestCase;

class FileLinkTimelineUnitTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        parent::setUp();

        $link = FileLink::factory()->create()->link_id;

        $this->model = FileLinkTimeline::create([
            'link_id' => $link,
            'added_by' => 'Some Dude',
        ]);

        FileLinkFile::factory()->count(5)->create([
            'link_id' => $link,
            'timeline_id' => $this->model->timeline_id,
            'upload' => true,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Model Relationships
    |---------------------------------------------------------------------------
    */
    public function test_file_upload_relationship(): void
    {
        $fileData = FileLinkFile::where(['link_id' => $this->model->link_id])
            ->get()
            ->pluck('file_id')
            ->toArray();

        $this->assertEquals(
            FileUpload::find($fileData)->toArray(),
            $this->model
                ->FileUpload
                ->makeHidden('laravel_through_key')
                ->toArray()
        );
    }

    public function test_file_link_note_relationship(): void
    {
        $note = FileLinkNote::create([
            'timeline_id' => $this->model->timeline_id,
            'note' => 'This is a note',
        ]);

        $this->assertEquals(
            $note->toArray(),
            $this->model->FileLinkNote->toArray()
        );
    }
}
