<?php

namespace Tests\Unit\Customers;

use App\Customers;
use App\Domains\Customers\SetCustomerDetails;
use App\Http\Requests\Customers\LinkParentRequest;
use App\Http\Requests\Customers\NewCustomerRequest;
use Tests\TestCase;

class SetCustomerDetailsTest extends TestCase
{
    public function test_create_customer()
    {
        $testData = factory(Customers::class)->make();
        $data = new NewCustomerRequest([
            'cust_id'     => $testData->cust_id,
            'parent_id'   => null,
            'name'        => $testData->name,
            'dba_name'    => $testData->dba_name,
            'address'     => $testData->address,
            'city'        => $testData->city,
            'zip'         => $testData->zip,
            'state'       => $testData->state,
        ]);

        $res = (new SetCustomerDetails)->createCustomer($data);
        $this->assertEquals($res, $data['cust_id']);
        $this->assertDatabaseHas('customers', $data->toArray());
    }

    public function test_create_customer_with_parent()
    {
        $parent1 = factory(Customers::class)->create();
        $parent2 = factory(Customers::class)->create(['parent_id' => $parent1->cust_id]);
        $testData = factory(Customers::class)->make();
        $data = new NewCustomerRequest([
            'cust_id'     => $testData->cust_id,
            'parent_id'   => $parent2->cust_id,
            'name'        => $testData->name,
            'dba_name'    => $testData->dba_name,
            'address'     => $testData->address,
            'city'        => $testData->city,
            'zip'         => $testData->zip,
            'state'       => $testData->state,
        ]);

        $res = (new SetCustomerDetails)->createCustomer($data);
        $data->parent_id = $parent1->cust_id;
        $this->assertEquals($res, $data['cust_id']);
        $this->assertDatabaseHas('customers', $data->toArray());
    }

    public function test_update_customer()
    {
        $testData = factory(Customers::class)->create();
        $newData  = factory(Customers::class)->make();
        $data = new NewCustomerRequest([
            'cust_id'     => $newData->cust_id,
            'parent_id'   => null,
            'name'        => $newData->name,
            'dba_name'    => $newData->dba_name,
            'address'     => $newData->address,
            'city'        => $newData->city,
            'zip'         => $newData->zip,
            'state'       => $newData->state,
        ]);

        $res = (new SetCustomerDetails)->updateCustomer($testData->cust_id, $data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customers', $data->toArray());
    }

    public function test_deactivate_customer()
    {
        $cust = factory(Customers::class)->create();

        $res = (new SetCustomerDetails)->deactivateCustomer($cust->cust_id);
        $this->assertTrue($res);
        $this->assertSoftDeleted('customers', ['cust_id' => $cust->cust_id]);
    }

    public function test_link_parent_add()
    {
        $customer = factory(Customers::class)->create();
        $parent = factory(Customers::class)->create();

        $data = new LinkParentRequest([
            'cust_id' => $customer->cust_id,
            'parent_id' => $parent->cust_id,
        ]);

        $res = (new SetCustomerDetails)->linkParent($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customers', ['cust_id' => $customer->cust_id, 'parent_id' => $parent->cust_id]);
    }

    public function test_link_parent_remove()
    {
        $parent   = factory(Customers::class)->create();
        $customer = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);

        $data = new LinkParentRequest([
            'cust_id' => $customer->cust_id,
        ]);

        $res = (new SetCustomerDetails)->linkParent($data);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customers', ['cust_id' => $customer->cust_id, 'parent_id' => null]);
    }
}
