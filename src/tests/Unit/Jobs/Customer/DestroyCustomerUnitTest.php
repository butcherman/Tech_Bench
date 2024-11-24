<?php

namespace Tests\Unit\Jobs\Customer;

use App\Jobs\Customer\DestroyCustomerJob;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Services\Customer\CustomerFileService;
use App\Services\Customer\CustomerService;
use Tests\TestCase;

class DestroyCustomerUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_job(): void
    {
        $cust = Customer::factory()
            ->has(CustomerFile::factory())
            ->has(CustomerNote::factory())
            ->has(CustomerContact::factory())
            ->createQuietly();
        $cust->delete();

        $jobObj = new DestroyCustomerJob($cust);
        $jobObj->handle(new CustomerFileService, new CustomerService);

        $this->assertDatabaseMissing('customers', [
            'cust_id' => $cust->cust_id,
        ]);

        $this->assertDatabaseMissing('customer_sites', [
            'cust_id' => $cust->cust_id,
        ]);

        $this->assertDatabaseMissing('customer_equipment', [
            'cust_id' => $cust->cust_id,
        ]);

        $this->assertDatabaseMissing('customer_files', [
            'cust_id' => $cust->cust_id,
        ]);

        $this->assertDatabaseMissing('customer_notes', [
            'cust_id' => $cust->cust_id,
        ]);

        $this->assertDatabaseMissing('customer_contacts', [
            'cust_id' => $cust->cust_id,
        ]);
    }
}
