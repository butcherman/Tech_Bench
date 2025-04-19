<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerSite;
use App\Services\Customer\CustomerService;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | createCustomer()
    |---------------------------------------------------------------------------
    */
    public function test_create_customer(): void
    {
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $cust->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];

        $testObj = new CustomerService;
        $res = $testObj->createCustomer(collect($data));

        $this->assertEquals($cust->name, $res->name);

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);
    }

    public function test_create_customer_duplicate_slug(): void
    {
        $cust = Customer::factory()->make();
        $existing = Customer::factory()->createQuietly();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $existing->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];
        $slug = Str::slug($data['name'].'-'.$site->city);

        $testObj = new CustomerService;
        $res = $testObj->createCustomer(collect($data));

        $this->assertEquals($existing->name, $res->name);

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
            'slug' => $slug,
        ]);
        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);
    }

    public function test_create_customer_second_duplicate_slug(): void
    {
        $cust = Customer::factory()->make();
        $existing = Customer::factory()->createQuietly();
        $site = CustomerSite::factory()->make();

        $duplicate = Customer::factory()->create([
            'name' => $existing->name,
            'slug' => Str::slug($existing->slug.'-'.$existing->CustomerSite[0]->city),
        ]);

        $data = [
            'name' => $existing->name,
            'dba_name' => $cust->dba_name,
            'address' => $existing->CustomerSite[0]->address,
            'city' => $existing->CustomerSite[0]->city,
            'state' => $existing->CustomerSite[0]->state,
            'zip' => $existing->CustomerSite[0]->zip,
        ];
        $slug = Str::slug($data['name'].'-'.$existing->CustomerSite[0]->city.'-1');

        $testObj = new CustomerService;
        $res = $testObj->createCustomer(collect($data));

        $this->assertEquals($existing->name, $res->name);

        $this->assertDatabaseHas('customers', [
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
            'slug' => $slug,
        ]);

        $this->assertDatabaseHas('customer_sites', [
            'site_name' => $data['name'],
            'address' => $data['address'],
            'city' => $data['city'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | createSite()
    |---------------------------------------------------------------------------
    */
    public function test_create_site(): void
    {
        $cust = Customer::factory()->create();
        $site = CustomerSite::factory()
            ->make()
            ->makeHidden(['cust_id', 'is_primary'])
            ->toArray();

        $testObj = new CustomerService;
        $res = $testObj->createSite(collect($site), $cust);

        $this->assertEquals($site['site_name'], $res->site_name);

        $this->assertDatabaseHas('customer_sites', $site);
    }

    public function test_create_site_without_cust(): void
    {
        $cust = Customer::factory()->create();
        $site = CustomerSite::factory()
            ->make()
            ->makeHidden(['cust_id', 'is_primary'])
            ->toArray();

        $site['cust_id'] = $cust->cust_id;

        $testObj = new CustomerService;
        $res = $testObj->createSite(collect($site));

        $this->assertEquals($site['site_name'], $res->site_name);

        $this->assertDatabaseHas('customer_sites', $site);
    }
}
