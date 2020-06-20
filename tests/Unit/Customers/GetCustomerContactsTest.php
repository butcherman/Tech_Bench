<?php

namespace Tests\Unit\Customers;

use App\CustomerContactPhones;
use App\CustomerContacts;
use App\Customers;
use App\Domains\Customers\GetCustomerContacts;
use Tests\TestCase;

class GetCustomerContactsTest extends TestCase
{
    public function test_execute_with_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust   = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $cont   = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        factory(CustomerContacts::class)->create(['cust_id' => $parent->cust_id, 'shared' => true]);
        factory(CustomerContacts::class)->create(['cust_id' => $parent->cust_id, 'shared' => false]);
        factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont->cont_id]);

        $res = (new GetCustomerContacts)->execute($cust->cust_id);
        $this->assertCount(2, $res);
    }

    public function test_get_one_contact()
    {
        $cont = factory(CustomerContacts::class, 10)->create();
        factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont[0]->cont_id, 'extension' => 123]);
        $res  = (new GetCustomerContacts)->getOneContact($cont[0]->cont_id);

        $this->assertInstanceOf('JeroenDesloovere\VCard\VCard', $res);
    }
}
