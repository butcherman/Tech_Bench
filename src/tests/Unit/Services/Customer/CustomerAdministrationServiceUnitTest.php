<?php

namespace Tests\Unit\Services\Customer;

use App\Models\Customer;
use App\Models\CustomerSite;
use App\Services\Customer\CustomerAdministrationService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CustomerAdministrationServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getCustomerSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_customer_settings(): void
    {
        $shouldBe = [
            'select_id' => config('customer.select_id'),
            'update_slug' => config('customer.update_slug'),
            'default_state' => config('customer.default_state'),
            'auto_purge' => config('customer.auto_purge'),
        ];

        $testObj = new CustomerAdministrationService;
        $res = $testObj->getCustomerSettings();

        $this->assertEquals(collect($shouldBe), $res);
    }

    /*
    |---------------------------------------------------------------------------
    | updateCustomerSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_customer_settings(): void
    {
        $data = [
            'select_id' => false,
            'update_slug' => false,
            'default_state' => 'AZ',
            'auto_purge' => false,
        ];

        $testObj = new CustomerAdministrationService;
        $testObj->updateCustomerSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.select_id',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.update_slug',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.default_state',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'customer.auto_purge',
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | getDisabledCustomers()
    |---------------------------------------------------------------------------
    */
    public function test_get_disabled_customers(): void
    {
        $custList = Customer::factory()
            ->count(5)
            ->createQuietly(['deleted_at' => now()]);

        Cache::put('queued-customers', [
            $custList[0]->cust_id,
            $custList[1]->cust_id,
        ]);

        $testObj = new CustomerAdministrationService;
        $res = $testObj->getDisabledCustomers();

        $this->assertCount(3, $res);

        Cache::forget('queued-customers');
    }

    /*
    |---------------------------------------------------------------------------
    | addToWorkingJobs()
    |---------------------------------------------------------------------------
    */
    public function test_add_to_working_jobs(): void
    {
        Cache::put('queued-customers', [1, 2, 3]);

        $cust = Customer::factory()->create(['cust_id' => 99]);

        $testObj = new CustomerAdministrationService;
        $testObj->addToWorkingJobs($cust);

        $res = Cache::pull('queued-customers');

        $this->assertEquals($res, [1, 2, 3, 99]);
    }

    /*
    |---------------------------------------------------------------------------
    | destroyCustomerSites()
    |---------------------------------------------------------------------------
    */
    public function test_destroy_customer_sites(): void
    {
        $cust = Customer::factory()
            ->has(CustomerSite::factory()->count(5), 'sites')
            ->create();

        $testObj = new CustomerAdministrationService;
        $testObj->destroyCustomerSites($cust);

        $this->assertEmpty(CustomerSite::where('cust_id', $cust->cust_id)->get());

        $cust->refresh();

        $this->assertNull($cust->primary_site_id);
    }
}
