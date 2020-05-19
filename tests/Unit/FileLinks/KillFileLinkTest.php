<?php

namespace Tests\Unit\FileLinks;

use App\Domains\FileLinks\KillFileLink;
use Carbon\Carbon;
use Tests\TestCase;
use App\FileLinks;

class KillFileLinkTest extends TestCase
{
    protected $testLink, $testUser, $testObj;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testUser   = $this->getTech();
        $this->testLink   = factory(FileLinks::class)->create([
            'user_id' => $this->testUser->user_id,
        ])->makeVisible('link_id')->makeHidden(['created_at', 'updated_at', 'allow_upload']);
        $this->testObj = new KillFileLink;

    }

    public function test_disable_link()
    {
        // dd($this->testLink);
        $response = $this->actingAs($this->testUser)->testObj->disableLink($this->testLink->link_id);
        $updatedData = $this->testLink->toArray();
        $updatedData['expire'] = Carbon::yesterday()->toDateString();

        $this->assertTrue($response);
        $this->assertDatabaseHas('file_links', $updatedData);
    }

    public function test_remove_link_files()
    {
        $response = $this->actingAs($this->testUser)->testObj->deleteFileLink($this->testLink->link_id);

        $this->assertTrue($response);
        $this->assertDatabaseMissing('file_links', ['link_id' => $this->testLink->link_id]);
    }
}
