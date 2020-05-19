<?php

namespace Tests\Unit\FileLinks;

use App\Domains\FileLinks\GetFileLinkFiles;
use App\FileLinkFiles;
use App\FileLinks;
use Tests\TestCase;

class GetFileLinkFilesTest extends TestCase
{
    protected $testObj, $testLink, $testFiles, $guestFiles;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testObj    = new GetFileLinkFiles;
        $this->testLink   = factory(FileLinks::class)->create();
        $this->testFiles  = factory(FileLinkFiles::class, 5)->create(['link_id' => $this->testLink->link_id]);
        $this->guestFiles = factory(FileLinkFiles::class, 5)->create([
            'link_id'  => $this->testLink->link_id,
            'added_by' => 'Billy Bob',
            'upload'   => 1,
        ]);
    }

    public function test_execute()
    {
        $data = $this->testObj->execute($this->testLink->link_id);
        $allFiles = $this->testFiles->merge($this->guestFiles);
        $this->assertEquals($allFiles->toArray(), $data->makeHidden(['Files', 'User'])->toArray());
    }

    public function test_get_guest_files()
    {
        $data = $this->testObj->getGuestFiles($this->testLink->link_id);
        $this->assertEquals($data->count(), 5);
    }
}
