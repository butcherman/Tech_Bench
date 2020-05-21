<?php

namespace Tests\Unit\Customers;

use Tests\TestCase;

use App\Customers;
use App\CustomerContacts;

use App\Domains\Customers\GetCustomerContacts;

class GetCustomerContactsTest extends TestCase
{
    protected $testObj, $testCust, $testContacts;

    public function setUp():void
    {
        Parent::setup();

        //  Setup test data
        $this->testCust = factory(Customers::class)->create();
        $this->testObj = new GetCustomerContacts($this->testCust->cust_id);
        $this->testContacts = factory(CustomerContacts::class, 5)->create([
            'cust_id' => $this->testCust->cust_id,
        ]);
    }

    public function test_execute()
    {
        $result = $this->testObj->execute();
        $this->assertEquals($result->makeHidden(['shared', 'CustomerContactPhones'])->toArray(), $this->testContacts->toArray());
    }

    public function test_get_one_contact()
    {
        $cont = $this->testObj->getOneContact($this->testContacts[0]->cont_id);
        $data = [
            'firstName'   => explode(' ', $this->testContacts[0]->name)[0],
            'lastName'    => explode(' ', $this->testContacts[0]->name)[1],
            'email'       => null, // $this->testContacts[0]->email,
            'additional'  => '',
            'prefix'      => '',
            'suffix'      => '',
            'cust_id'     => $this->testContacts[0]->cust_id,
            'numbers'     => [],
        ];

        $this->assertEquals($data, $cont->toArray());
    }

    public function test_get_phone_number_types()
    {
        $defaultData = [
            ['phone_type_id' => 1, 'description'   => 'Home',   'icon_class'    => 'fas fa-home',],
            ['phone_type_id' => 2, 'description'   => 'Work',   'icon_class'    => 'fas fa-briefcase',],
            ['phone_type_id' => 3, 'description'   => 'Mobile', 'icon_class'    => 'fas fa-mobile-alt',],
        ];

        $data = $this->testObj->getPhoneNumberTypes();
        $this->assertEquals($defaultData, $data->makeVisible('phone_type_id')->toArray());
    }
}
