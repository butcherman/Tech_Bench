<?php

namespace Tests\Unit\Customers;

use App\CustomerContactPhones;
use App\Customers;
use App\CustomerContacts;
use App\Domains\Customers\SetCustomerContacts;
use App\Http\Requests\Customers\CustomerContactRequest;
use Tests\TestCase;

class SetCustomerContactsTest extends TestCase
{
    public function test_create_new_contact()
    {
        $cust = factory(Customers::class)->create();
        $cont = factory(CustomerContacts::class)->make();
        $data = new CustomerContactRequest([
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => false,
            'customer_contact_phones' => [[
                'phone_type_id' => 1,
                'readable'      => '(541) 555-1212',
                'extension'     => 123,
            ]]
        ]);

        $res = (new SetCustomerContacts)->createNewContact($data, $cust->cust_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_contacts', ['cust_id' => $cust->cust_id, 'name' => $cont->name, 'email' => $cont->email]);
        $this->assertDatabaseHas('customer_contact_phones', ['phone_type_id' => 1, 'phone_number' => 5415551212, 'extension' => 123]);
    }

    public function test_create_new_contact_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $cont = factory(CustomerContacts::class)->make();
        $data = new CustomerContactRequest([
            'cust_id' => $cust->cust_id,
            'name'    => $cont->name,
            'email'   => $cont->email,
            'shared'  => true,
            'customer_contact_phones' => [[
                'phone_type_id' => 1,
                'readable'      => '(541) 555-1212',
                'extension'     => 123,
            ]]
        ]);

        $res = (new SetCustomerContacts)->createNewContact($data, $cust->cust_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_contacts', ['cust_id' => $parent->cust_id, 'name' => $cont->name, 'email' => $cont->email]);
        $this->assertDatabaseHas('customer_contact_phones', ['phone_type_id' => 1, 'phone_number' => 5415551212, 'extension' => 123]);
    }

    public function test_update_existing_contact()
    {
        $cust = factory(Customers::class)->create();
        $cont = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        $phone = factory(CustomerContactPhones::class, 2)->create(['cont_id' => $cont->cont_id]);
        $data = new CustomerContactRequest([
            'cust_id' => $cust->cust_id,
            'name'    => 'New Name',
            'email'   => 'newEm@em.com',
            'shared'  => false,
            'customer_contact_phones' => [
                [
                    'phone_type_id' => 1,
                    'readable'      => '(541) 555-1212',
                    'extension'     => 123,
                ],
                [
                    'id'            => $phone[0]->id,
                    'phone_type_id' => 2,
                    'readable'      => '(816) 444-2121',
                    'extension'     => null,
                ]
            ],
        ]);

        $res = (new SetCustomerContacts)->updateExistingContact($data, $cont->cont_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_contacts', ['cust_id' => $cust->cust_id, 'name' => 'New Name', 'email' => 'newEm@em.com']);
        $this->assertDatabaseHas('customer_contact_phones', ['phone_type_id' => 1, 'phone_number' => 5415551212, 'extension' => 123]);
        $this->assertDatabaseHas('customer_contact_phones', ['id' => $phone[0]->id, 'phone_number' => 8164442121]);
    }

    public function test_update_existing_contact_parent()
    {
        $parent = factory(Customers::class)->create();
        $cust = factory(Customers::class)->create(['parent_id' => $parent->cust_id]);
        $cont = factory(CustomerContacts::class)->create(['cust_id' => $cust->cust_id]);
        $data = new CustomerContactRequest([
            'cust_id' => $cust->cust_id,
            'name'    => 'New Name',
            'email'   => 'newEm@em.com',
            'shared'  => true,
            'customer_contact_phones' => [[
                'phone_type_id' => 1,
                'readable'      => '(541) 555-1212',
                'extension'     => 123,
            ]]
        ]);

        $res = (new SetCustomerContacts)->updateExistingContact($data, $cont->cont_id);
        $this->assertTrue($res);
        $this->assertDatabaseHas('customer_contacts', ['cust_id' => $parent->cust_id, 'name' => 'New Name', 'email' => 'newEm@em.com']);
        $this->assertDatabaseHas('customer_contact_phones', ['phone_type_id' => 1, 'phone_number' => 5415551212, 'extension' => 123]);
    }

    public function test_delete_contact()
    {
        $cont = factory(CustomerContacts::class)->create();
        $res = (new SetCustomerContacts)->deleteContact($cont->cont_id);

        $this->assertTrue($res);
        $this->assertDatabaseMissing('customer_contacts', $cont->toArray());
    }
}
