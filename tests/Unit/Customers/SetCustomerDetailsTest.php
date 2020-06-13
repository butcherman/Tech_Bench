<?php

namespace Tests\Unit\Customers;

use App\Customers;
use App\Domains\Customers\SetCustomerDetails;
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
}
