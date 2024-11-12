<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerContactPhone;
use App\Models\CustomerSite;
use App\Services\Customer\CustomerContactPhoneService;
use App\Services\Customer\CustomerContactService;
use Tests\TestCase;

class CustomerContactUnitTest extends TestCase
{
    public function test_create_customer_contact(): void
    {
        $cust = Customer::factory()->hasCustomerSite(5)->createQuietly();
        $cont = CustomerContact::factory()->make();
        $data = [
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'site_list' => $cust->CustomerSite
                ->pluck('cust_site_id')
                ->toArray(),
            'local' => false,
            'decision_maker' => false,
            'phones' => [
                [
                    'type' => 'Mobile',
                    'number' => '(213)555-1212',
                    'ext' => '232',
                ],
            ],
        ];

        $testObj = new CustomerContactService(new CustomerContactPhoneService);
        $res = $testObj->createCustomerContact(collect($data), $cust);

        $this->assertEquals(
            collect($data)
                ->only(['name', 'title', 'local', 'decision_maker'])
                ->toArray(),
            $res->only(['name', 'title', 'local', 'decision_maker'])
        );

        $this->assertDatabaseHas('customer_contacts', [
            'cust_id' => $cust->cust_id,
            'name' => $cont->name,
            'title' => $cont->title,
            'email' => $cont->email,
            'local' => false,
            'decision_maker' => false,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number' => 2135551212,
            'extension' => 232,
        ]);
    }

    public function test_update_customer_contact(): void
    {
        $cust = Customer::factory()->createQuietly();
        $site = CustomerSite::factory()
            ->count(5)
            ->create(['cust_id' => $cust->cust_id]);
        $cont = CustomerContact::factory()->createQuietly([
            'cust_id' => $cust->cust_id,
        ]);
        $cont->CustomerSite()
            ->attach([$site[0]->cust_site_id, $site[1]->cust_site_id]);
        $mod = CustomerContact::factory()->make();
        $ph = CustomerContactPhone::factory()
            ->count(2)
            ->create(['cont_id' => $cont->cont_id]);

        $data = [
            'name' => $mod->name,
            'title' => $mod->title,
            'email' => $mod->email,
            'site_list' => [$site[1]->cust_site_id, $site[3]->cust_site_id],
            'local' => false,
            'decision_maker' => false,
            'phones' => [
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
            ],
        ];

        $testObj = new CustomerContactService(new CustomerContactPhoneService);
        $res = $testObj->updateCustomerContact(collect($data), $cont);

        $this->assertEquals(
            collect($data)
                ->only(['name', 'title', 'local', 'decision_maker'])
                ->toArray(),
            $res->only(['name', 'title', 'local', 'decision_maker'])
        );

        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_id' => $cust->cust_id,
            'name' => $mod->name,
            'title' => $mod->title,
            'email' => $mod->email,
            'local' => false,
            'decision_maker' => false,
        ]);
        $this->assertDatabaseHas('customer_contact_phones', [
            'phone_type_id' => 3,
            'phone_number' => 2135552121,
            'extension' => null,
        ]);
        $this->assertDatabaseHas('customer_site_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_site_id' => $site[1]->cust_site_id,
        ]);
        $this->assertDatabaseHas('customer_site_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_site_id' => $site[3]->cust_site_id,
        ]);
        $this->assertDatabaseMissing('customer_site_contacts', [
            'cont_id' => $cont->cont_id,
            'cust_site_id' => $site[0]->cust_site_id,
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

    public function test_destroy_contact(): void
    {
        $cont = CustomerContact::factory()->createQuietly();

        $testObj = new CustomerContactService(new CustomerContactPhoneService);
        $testObj->destroyContact($cont);

        $this->assertSoftDeleted(
            'customer_contacts',
            $cont->only(['cont_id', 'name', 'email'])
        );
    }

    public function test_destroy_contact_force(): void
    {
        $cont = CustomerContact::factory()->createQuietly();

        $testObj = new CustomerContactService(new CustomerContactPhoneService);
        $testObj->destroyContact($cont, true);

        $this->assertDatabaseMissing(
            'customer_contacts',
            $cont->only(['cont_id', 'name', 'email'])
        );
    }

    public function test_restore_contact(): void
    {
        $cont = CustomerContact::factory()->createQuietly();
        $cont->delete();

        $testObj = new CustomerContactService(new CustomerContactPhoneService);
        $testObj->restoreContact($cont);

        $this->assertDatabaseHas('customer_contacts', [
            'cont_id' => $cont->cont_id,
            'deleted_at' => null,
        ]);
    }
}
