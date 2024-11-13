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
        $slug = Str::slug($data['name']);

        $testObj = new CustomerService;
        $res = $testObj->createCustomer(collect($data));

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->dba_name, $data['dba_name']);

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

    public function test_create_customer_duplicate_slug(): void
    {
        $existing = Customer::factory()->createQuietly();
        $cust = Customer::factory()->make();
        $site = CustomerSite::factory()->make();

        $data = [
            'name' => $existing->name,
            'dba_name' => $cust->dba_name,
            'address' => $site->address,
            'city' => $site->city,
            'state' => $site->state,
            'zip' => $site->zip,
        ];
        $slug = Str::slug($data['name'].'-'.$data['city']);

        $testObj = new CustomerService;
        $res = $testObj->createCustomer(collect($data));

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->dba_name, $data['dba_name']);

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
        $existing1 = Customer::factory()
            ->has(CustomerSite::factory())
            ->createQuietly();
        Customer::factory()->createQuietly([
            'name' => $existing1->name,
            'slug' => Str::slug(
                $existing1->slug.'-'.$existing1->CustomerSite[0]->city
            ),
        ]);

        $cust = Customer::factory()->make();

        $data = [
            'name' => $existing1->name,
            'dba_name' => $cust->dba_name,
            'address' => $existing1->CustomerSite[0]->address,
            'city' => $existing1->CustomerSite[0]->city,
            'state' => $existing1->CustomerSite[0]->state,
            'zip' => $existing1->CustomerSite[0]->zip,
        ];
        $slug = Str::slug($data['name'].'-'.$data['city'].'-1');

        $testObj = new CustomerService;
        $res = $testObj->createCustomer(collect($data));

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->dba_name, $data['dba_name']);

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

        $customer = Customer::factory()->createQuietly();
        $newSite = CustomerSite::factory()->createQuietly([
            'cust_id' => $customer->cust_id,
        ]);
        $updated = Customer::factory()->make();

        $data = [
            'name' => $updated->name,
            'dba_name' => 'Some Business',
            'primary_site_id' => $newSite->cust_site_id,
        ];

        $testObj = new CustomerService;
        $res = $testObj->updateCustomer(collect($data), $customer);

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->dba_name, $data['dba_name']);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'name' => $updated->name,
        ]);

        Event::assertDispatched(CustomerSlugChangedEvent::class);
    }

    public function test_update_customer_disable_change_slug(): void
    {
        config(['customer.update_slug' => false]);

        $customer = Customer::factory()->createQuietly();
        $newSite = CustomerSite::factory()->createQuietly([
            'cust_id' => $customer->cust_id,
        ]);
        $updated = Customer::factory()->make();
        $slug = $customer->slug;

        $data = [
            'name' => $updated->name,
            'dba_name' => 'Some Business',
            'primary_site_id' => $newSite->cust_site_id,
        ];

        $testObj = new CustomerService;
        $res = $testObj->updateCustomer(collect($data), $customer);

        $this->assertEquals($res->name, $data['name']);
        $this->assertEquals($res->dba_name, $data['dba_name']);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $customer->cust_id,
            'name' => $updated->name,
            'slug' => $slug,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomer()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer(): void
    {
        $cust = Customer::factory()->createQuietly();

        $testObj = new CustomerService;
        $testObj->destroyCustomer($cust, 'For Testing Purposes');

        $this->assertSoftDeleted('customers', $cust->only(['cust_id']));
    }

    public function test_destroy_customer_force(): void
    {
        $cust = Customer::factory()->createQuietly();
        $cust->primary_site_id = null;
        $cust->save();

        CustomerSite::withoutBroadcasting(function () use ($cust) {
            $cust->CustomerSite()->each(function ($site) {
                $site->forceDelete();
            });
        });

        $testObj = new CustomerService;
        $testObj->destroyCustomer($cust, 'For Testing Purposes', true);

        $this->assertDatabaseMissing('customers', $cust->only(['cust_id']));
    }

    public function test_destroy_customer_force_in_use(): void
    {
        $cust = Customer::factory()->createQuietly();

        $this->expectException(RecordInUseException::class);

        $testObj = new CustomerService;
        $testObj->destroyCustomer($cust, 'For Testing Purposes', true);

        $this->assertDatabaseHas('customers', $cust->only(['cust_id']));
    }

    /*
    |---------------------------------------------------------------------------
    | restoreCustomer()
    |---------------------------------------------------------------------------
    */
    public function test_restore_customer(): void
    {
        $cust = Customer::factory()->createQuietly();
        $cust->delete();

        $testObj = new CustomerService;
        $testObj->restoreCustomer($cust);

        $this->assertDatabaseHas('customers', [
            'cust_id' => $cust->cust_id,
            'name' => $cust->name,
            'deleted_at' => null,
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | Customer Sites
    |---------------------------------------------------------------------------
    */

    /*
    |---------------------------------------------------------------------------
    | createSite()
    |---------------------------------------------------------------------------
    */
    public function test_create_site(): void
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $testObj = new CustomerService;
        $res = $testObj->createSite(collect($site->toArray()), $customer);

        $this->assertEquals(
            $site->makeHidden('cust_name')->toArray(),
            $res->makeHidden('cust_site_id')->toArray()
        );

        $this->assertDatabaseHas(
            'customer_sites',
            $site->makeHidden(['is_primary', 'href'])->toArray()
        );
    }

    public function test_create_site_imbedded_parent(): void
    {
        $customer = Customer::factory()->create();
        $site = CustomerSite::factory()->make();

        $site->cust_name = $customer->name;
        $site->cust_id = $customer->cust_id;

        $testObj = new CustomerService;
        $res = $testObj->createSite(collect($site->toArray()));

        $this->assertEquals(
            $site->makeHidden('cust_name')->toArray(),
            $res->makeHidden('cust_site_id')->toArray()
        );

        $this->assertDatabaseHas(
            'customer_sites',
            $site->makeHidden(['is_primary', 'href'])->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | updateSite()
    |---------------------------------------------------------------------------
    */
    public function test_update_site(): void
    {
        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $data->cust_name = $site->Customer->name;
        $data->cust_id = $site->Customer->cust_id;

        $testObj = new CustomerService;
        $res = $testObj->updateSite(collect($data->toArray()), $site);

        $this->assertEquals(
            $site->makeHidden('cust_name')->toArray(),
            $res->toArray()
        );

        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $site->cust_site_id,
            'site_name' => $data->site_name,
        ]);
    }

    public function test_update_site_disabled_slug_change(): void
    {
        config(['customer.update_slug' => false]);

        $site = CustomerSite::factory()->create();
        $data = CustomerSite::factory()->make();

        $testObj = new CustomerService;
        $res = $testObj->updateSite(collect($data->toArray()), $site);

        $this->assertEquals(
            $site->makeHidden('cust_name')->toArray(),
            $res->toArray()
        );

        $this->assertDatabaseHas('customer_sites', [
            'cust_site_id' => $site->cust_site_id,
            'site_name' => $data->site_name,
            'site_slug' => $site->site_slug,
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

        $testObj = new CustomerService;
        $testObj->destroySite($site, 'Unit Testing');

        $this->assertSoftDeleted(
            'customer_sites',
            $site->makeHidden(['is_primary', 'href'])->toArray()
        );
    }

    public function test_destroy_force(): void
    {
        $site = CustomerSite::factory()->create();

        $testObj = new CustomerService;
        $testObj->destroySite($site, 'Unit Testing', true);

        $this->assertDatabaseMissing(
            'customer_sites',
            $site->makeHidden(['is_primary', 'href'])->toArray()
        );
    }

    public function test_destroy_all_sites(): void
    {
        $cust = Customer::factory()->has(CustomerSite::factory(5))->create();

        $testObj = new CustomerService;
        $testObj->destroyAllSites($cust);

        $this->assertCount(0, $cust->fresh()->CustomerSite->toArray());
    }
}
