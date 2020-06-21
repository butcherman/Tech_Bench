<?php

namespace Tests\Unit\Customers;

use App\CustomerNotes;
use App\Customers;
use App\Domains\Customers\GetCustomerNotes;
use Tests\TestCase;

class GetCustomerNotesTest extends TestCase
{
    public function test_execute()
    {
        $cust = factory(Customers::class)->create();
        $note = factory(CustomerNotes::class, 5)->create(['cust_id' => $cust->cust_id]);

        $res = (new GetCustomerNotes)->execute($cust->cust_id);
        $this->assertEquals($note->toArray(), $res->makeHidden('shared')->toArray());
    }

    public function test_execute_with_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        factory(CustomerNotes::class, 5)->create(['cust_id' => $cust->cust_id]);
        factory(CustomerNotes::class, 5)->create(['cust_id' => $parent->cust_id]);
        factory(CustomerNotes::class, 5)->create(['cust_id' => $parent->cust_id, 'shared' => true]);

        $res = (new GetCustomerNotes)->execute($cust->cust_id);
        $this->assertCount(10, $res);
    }
}
