<?php

namespace Tests\Unit\Customers;

use App\Domains\Customers\GetCustomerDetails;
use Tests\TestCase;
use App\Customers;

class GetCustomerDetailsTest extends TestCase
{
    protected $testObj, $testCust;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $parent = factory(Customers::class)->create();
        $this->testCust = factory(Customers::class)->create([
            'parent_id' => $parent->cust_id,
        ]);
        $this->testObj = new GetCustomerDetails;
    }

    public function test_get_details()
    {
        $res = $this->testObj->getDetails($this->testCust->cust_id);

        $this->assertEquals($this->testCust->toArray(), $res->toArray());
    }
}
