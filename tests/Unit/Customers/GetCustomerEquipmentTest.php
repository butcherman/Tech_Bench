<?php

namespace Tests\Unit\Customers;

use App\Customers;
use App\CustomerSystems;
use App\Domains\Customers\GetCustomerEquipment;
use Tests\TestCase;

class GetCustomerEquipmentTest extends TestCase
{
    public function test_execute()
    {
        $cust  = factory(Customers::class)->create();
        $equip = factory(CustomerSystems::class)->create(['cust_id' => $cust->cust_id]);

        $res = (new GetCustomerEquipment)->execute($cust->cust_id)->makeHidden(['shared', 'CustomerSystemData']);
        $this->assertEquals($equip->toArray(), $res->toArray()[0]);
    }

    public function test_execute_with_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $equip  = factory(CustomerSystems::class)->create(['cust_id' => $parent->cust_id, 'shared' => 1]);

        $res = (new GetCustomerEquipment)->execute($cust->cust_id)->makeHidden(['CustomerSystemData']);
        $this->assertEquals($equip->toArray(), $res->toArray()[0]);
    }
}
