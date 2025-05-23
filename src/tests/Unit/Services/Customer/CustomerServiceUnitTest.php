<?php

namespace Tests\Unit\Services\Customer;

use App\Events\Customer\CustomerSlugChangedEvent;
use App\Exceptions\Database\RecordInUseException;
use App\Models\Customer;
use App\Models\CustomerSite;
use App\Services\Customer\CustomerService;
use Illuminate\Support\Facades\Event;
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
        CustomerSite::factory()->make();

        Customer::factory()->create([
            'name' => $existing->name,
            'slug' => Str::slug($existing->slug.'-'.$existing->Sites[0]->city),
        ]);

        $data = [
            'name' => $existing->name,
            'dba_name' => $cust->dba_name,
            'address' => $existing->Sites[0]->address,
            'city' => $existing->Sites[0]->city,
            'state' => $existing->Sites[0]->state,
            'zip' => $existing->Sites[0]->zip,
        ];
        $slug = Str::slug($data['name'].'-'.$existing->Sites[0]->city.'-1');

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
    | updateCustomer()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer(): void
    {
        Event::fake();

        $existing = Customer::factory()->create();
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()
            ->create(['cust_id' => $existing->cust_id]);

        $data = [
            'name' => $cust->name,
            'dba_name' => $cust->dba_name,
            'primary_site_id' => $site->cust_site_id,
        ];

        $testObj = new CustomerService;
        $res = $testObj->updateCustomer(collect($data), $existing);

        $this->assertEquals($cust->name, $res->name);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $existing->cust_id,
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
            'primary_site_id' => $site->cust_site_id,
        ]);

        Event::assertDispatched(CustomerSlugChangedEvent::class);
    }

    public function test_update_customer_duplicate_slug(): void
    {
        Event::fake();

        $updating = Customer::factory()->create();
        $cust = Customer::factory()->make();
        $existing = Customer::factory()->createQuietly();
        $site = CustomerSite::factory()
            ->create(['cust_id' => $updating->cust_id]);

        $updating->primary_site_id = $site->cust_site_id;
        $updating->save();

        $data = [
            'name' => $existing->name,
            'dba_name' => $cust->dba_name,
            'primary_site_id' => $site->cust_site_id,
        ];
        $slug = Str::slug($data['name'].'-'.$updating->Sites[0]->city);

        $testObj = new CustomerService;
        $res = $testObj->updateCustomer(collect($data), $updating);

        $this->assertEquals($existing->name, $res->name);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $updating->cust_id,
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
            'slug' => $slug,
            'primary_site_id' => $site->cust_site_id,
        ]);

        Event::assertDispatched(CustomerSlugChangedEvent::class);
    }

    public function test_update_customer_without_modifying_slug(): void
    {
        config(['customer.update_slug' => false]);

        $existing = Customer::factory()->create();
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()
            ->create(['cust_id' => $existing->cust_id]);

        $data = [
            'name' => $cust->name,
            'dba_name' => $cust->dba_name,
            'primary_site_id' => $site->cust_site_id,
        ];

        $testObj = new CustomerService;
        $res = $testObj->updateCustomer(collect($data), $existing);

        $this->assertEquals($cust->name, $res->name);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $existing->cust_id,
            'name' => $data['name'],
            'dba_name' => $data['dba_name'],
            'primary_site_id' => $site->cust_site_id,
            'slug' => $existing->slug,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomer()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_soft_delete(): void
    {
        $customer = Customer::factory()->create();
        $reason = 'Unit Testing';

        $testObj = new CustomerService;
        $testObj->destroyCustomer($customer, $reason);

        $this->assertSoftDeleted(
            'customers',
            $customer->makeHidden(['site_count', 'Sites'])->toArray()
        );

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'deleted_reason' => $reason,
        ]);
    }

    public function test_destroy_customer_force_delete(): void
    {
        $customer = Customer::factory()->create();
        $customer->primary_site_id = null;
        $customer->save();
        $customer->delete();

        CustomerSite::where('cust_id', $customer->cust_id)->forceDelete();

        $reason = 'Unit Testing';

        $testObj = new CustomerService;
        $testObj->destroyCustomer($customer, $reason, true);

        $this->assertDatabaseMissing(
            'customers',
            $customer->makeHidden(['site_count', 'Sites'])->toArray()
        );
    }

    public function test_destroy_customer_force_delete_in_use(): void
    {
        $customer = Customer::factory()->create();
        $customer->delete();

        $reason = 'Unit Testing';

        $this->expectException(RecordInUseException::class);

        $testObj = new CustomerService;
        $testObj->destroyCustomer($customer, $reason, true);

        $this->assertDatabaseMissing(
            'customers',
            $customer->makeHidden(['site_count', 'Sites'])->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | restoreCustomer()
    |---------------------------------------------------------------------------
    */
    public function test_restore_customer(): void
    {
        $customer = Customer::factory()->create();
        $customer->delete();

        $testObj = new CustomerService;
        $testObj->restoreCustomer($customer);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'deleted_at' => null,
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

    /*
    |---------------------------------------------------------------------------
    | updateSite()
    |---------------------------------------------------------------------------
    */
    public function test_update_site(): void
    {
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()
            ->make()
            ->makeHidden(['cust_id', 'is_primary'])
            ->toArray();

        $testObj = new CustomerService;
        $res = $testObj->updateSite(collect($data), $site);

        $this->assertEquals($data['site_name'], $res->site_name);

        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $site->cust_site_id,
            'site_name' => $data['site_name'],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroySite()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_site(): void
    {
        $site = CustomerSite::factory()->create();
        $reason = 'Unit Testing';

        $testObj = new CustomerService;
        $testObj->destroySite($site, $reason);

        $this->assertSoftDeleted(
            'customer_sites',
            $site->makeHidden(['is_primary'])->toArray()
        );
    }

    public function test_destroy_site_force_delete(): void
    {
        $site = CustomerSite::factory()->create();
        $site->delete();
        $reason = 'Unit Testing';

        $testObj = new CustomerService;
        $testObj->destroySite($site, $reason, true);

        $this->assertDatabaseMissing(
            'customer_sites',
            $site->makeHidden(['is_primary'])->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | restoreSite()
    |---------------------------------------------------------------------------
    */
    public function test_restore_site(): void
    {
        $site = CustomerSite::factory()->create();
        $site->delete();

        $testObj = new CustomerService;
        $testObj->restoreSite($site);

        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $site->cust_site_id,
            'deleted_at' => null,
        ]);
    }
}
