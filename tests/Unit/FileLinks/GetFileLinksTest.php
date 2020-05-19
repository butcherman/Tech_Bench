<?php

namespace Tests\Unit\FileLinks;

use App\Domains\FileLinks\GetFileLinks;
use Tests\TestCase;
use App\FileLinks;

class GetFileLinksTest extends TestCase
{
    protected $testLink, $testUser;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testUser   = $this->getTech();
        $this->testLink   = factory(FileLinks::class, 10)->create([
            'user_id' => $this->testUser->user_id,
        ]);

    }

    //  Test get the users file links from admin standpoint
    public function test_execute()
    {
        $testObj = new GetFileLinks($this->testUser->user_id);
        $data    = $testObj->execute();

        // $this->assertEquals($data->makeHidden(['link_id', 'FileLinkFilesCount'])->toJson(), $this->testLink->toJson());
    }
}
