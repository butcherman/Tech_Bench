<?php

namespace Tests\Unit\FileLinks;

use Tests\TestCase;

use App\Domains\FileLinks\GetFileLinkDetails;

use App\Customers;
use App\FileLinks;
use Carbon\Carbon;

class GetFileLinkDetailsTest extends TestCase
{
    protected $testObj, $testLink;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testLink = factory(FileLinks::class)->create([
            'cust_id'      => factory(Customers::class)->create()->cust_id,
            'allow_upload' => true,
        ]);
        $this->testObj  = new GetFileLinkDetails($this->testLink->link_id);
    }

    public function test_execute()
    {
        $data = $this->testObj->execute();

        $this->assertEquals($data->toArray(), $this->testLink->toArray());
    }

    //  Verify that a link is valid, meening it actually exists in the databse
    public function test_is_link_valid()
    {
        $this->assertTrue($this->testObj->isLinkValid());
    }

    //  Verify that a bad link returns false
    public function test_is_link_valid_false()
    {
        $newObj = new GetFileLinkDetails(999999);
        $this->assertFalse($newObj->isLinkValid());
    }

    //  Get the ID of the customer that is attached to the link
    public function test_get_link_customer()
    {
        $custID = $this->testObj->getLinkCustomer();
        $this->assertEquals($this->testLink->cust_id, $custID);
    }

    //  Get the instructions attached to the link
    public function test_get_link_instructions()
    {
        $note = $this->testObj->getLinkInstructions();
        $this->assertEquals($this->testLink->note, $note);
    }

    //  Get the link ID from the hash used as the guest ID
    public function test_get_link_id()
    {
        $id = $this->testObj->getLinkID($this->testLink->link_hash);
        $this->assertEquals($this->testLink->link_id, $id);
    }

    //  Get the link ID from a random hash that does not exist
    public function test_link_link_id_false()
    {
        $this->assertFalse($this->testObj->getLinkID('HelloWorld'));
    }

    //  Verify that the test link has not expired
    public function test_is_link_expired()
    {
        $this->assertFalse($this->testObj->isLinkExpired());
    }

    //  Create an expired link and test again
    public function test_is_link_expired_true()
    {
        $newLink = factory(FileLinks::class)->create([
            'expire' => Carbon::now()->subDays(20),
        ]);
        $this->assertTrue((new GetFileLinkDetails($newLink->link_id))->isLInkExpired());
    }

    //  Verfiy if the gues can upload a file or now
    public function test_can_guest_upload()
    {
        $allow = $this->testObj->canGuestUpload();
        $this->assertTrue($allow);
    }

    //  Turn off ability to upload file and test again
    public function test_can_guest_upload_false()
    {
        $newLink = factory(FileLinks::class)->create([
            'allow_upload' => false,
        ]);

        $newObj = new GetFileLinkDetails($newLink->link_id);
        $this->assertFalse($newObj->canGuestUpload());
    }

    //  Create a link that a guest cannot do anything with
    public function test_is_link_dead()
    {
        $newLink = factory(FileLinks::class)->create([
            'allow_upload' => false,
        ]);
        $this->assertTrue((new GetFileLinkDetails($newLink->link_id))->isLinkDead());
    }

    //  Verify that our test link is not dead
    public function test_is_link_dead_false()
    {
        $this->assertFalse($this->testObj->isLinkDead());
    }
}
