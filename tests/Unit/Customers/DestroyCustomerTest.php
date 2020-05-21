<?php

namespace Tests\Unit\Customers;

use App\Customers;
use App\Domains\Customers\DestroyCustomer;
use Tests\TestCase;

class DestroyCustomerTest extends TestCase
{
    protected $testObj, $testCust;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testObj = new DestroyCustomer;
        $this->testCust = factory(Customers::class)->create();
    }

    public function test_soft_delete()
    {
        $result = $this->actingAs($this->getTech())->testObj->softDelete($this->testCust->cust_id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('customers', $this->testCust->makeHidden('child_count')->toArray());
    }
}
