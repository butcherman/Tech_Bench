<?php

namespace Tests\Unit\Services\Customer;

use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Services\Customer\CustomerContactPhoneService;
use Tests\TestCase;

class CustomerContactPhoneUnitTest extends TestCase
{
    public function test_process_customer_contact_phones_new(): void
    {
        $cont = CustomerContact::factory()->create();
        $data = [
            [
                'type' => 'Mobile',
                'number' => '(213)555-1212',
                'ext' => '232',
            ],
        ];

        $testObj = new CustomerContactPhoneService;
        $testObj->processCustomerContactPhones($data, $cont);

        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number' => 2135551212,
            'extension' => 232,
        ]);
    }

    public function test_process_customer_contact_phones_update(): void
    {
        $cont = CustomerContact::factory()->createQuietly();
        $ph = CustomerContactPhone::factory()
            ->count(2)
            ->create(['cont_id' => $cont->cont_id]);

        $data = [
            [
                'id' => $ph[0]->id,
                'type' => 'Mobile',
                'number' => $ph[0]->phone_number,
                'ext' => null,
            ],
            [
                'type' => 'Mobile',
                'number' => '(213)555-2121',
                'ext' => null,
            ],
            [
                'type' => 'Mobile',
                'number' => null,
                'ext' => null,
            ],
        ];

        $testObj = new CustomerContactPhoneService;
        $testObj->processCustomerContactPhones($data, $cont);

        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number' => 2135552121,
            'extension' => null,
        ]);

        $this->assertDatabaseMissing(
            'customer_contact_phones',
            $ph[1]->only(
                'id',
                'phone_number',
                'phone_type_id',
                'extension'
            )
        );
    }
}
