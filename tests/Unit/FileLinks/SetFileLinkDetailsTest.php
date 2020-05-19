<?php

namespace Tests\Unit\FileLinks;

use Carbon\Carbon;
use Tests\TestCase;

use App\Domains\FileLinks\SetFileLinkDetails;

use App\FileLinks;

use App\Http\Requests\FileLinkUpdateRequest;
use App\Http\Requests\FileLinkCreateRequest;
use App\Http\Requests\FileLinkInstructionsRequest;

class SetFileLinkDetailsTest extends TestCase
{
    protected $testObj, $testUser;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testObj = new SetFileLinkDetails;
    }

    public function test_process_new_link()
    {
        $link = factory(FileLinks::class)->make();
        $link->allow_upload = $link->getOriginal('allow_upload');
        $data = [
            'name'         => $link->link_name,
            'expire'       => Carbon::now()->addDays(120),
            'allowUp'      => true,
            'instructions' => $link->note,
            'customerID'   => null,
        ];
        $result = $this->actingAs($this->getTech())->testObj->processNewLink(new FileLinkCreateRequest($data));

        $this->assertTrue($result > 0);
        $this->assertDatabaseHas('file_links', [
            'link_id'      => $result,
            'link_name'    => $data['name'],
            'allow_upload' => $data['allowUp'],
            'note'         => $data['instructions'],
            'cust_id'      => $data['customerID'],
        ]);
    }

    public function test_update_link()
    {
        $link = factory(FileLinks::class)->create();
        // $link->allow_upload = $link->getOriginal('allow_upload');
        $data = [
            'name'         => $link->link_name,
            'expire'       => Carbon::now()->addDays(120),
            'allow_upload' => true,
            'cust_id'      => null,
            'cust_name'    => null,
        ];
        $result = $this->actingAs($this->getTech())->testObj->updateLink(new FileLinkUpdateRequest($data), $link->link_id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('file_links', [
            'link_id'      => $link->link_id,
            'link_name'    => $data['name'],
            'allow_upload' => $data['allow_upload'],
            'cust_id'      => $data['cust_id'],
        ]);
    }

    public function test_set_link_instructions()
    {
        $link = factory(FileLinks::class)->create();

        $instructions = 'Here are some new instructions for the link';

        $result = $this->actingAs($this->getTech())->testObj->setLinkInstructions(new FileLinkInstructionsRequest(['instructions' => $instructions]), $link->link_id);

        $this->assertTrue($result);
        $this->assertDatabaseHas('file_links', [
            'link_id' => $link->link_id,
            'note'    => $instructions,
        ]);
    }
}
