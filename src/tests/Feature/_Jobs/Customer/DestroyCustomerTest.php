<?php

namespace Tests\_Jobs\Customer;

use App\Jobs\Customer\DestroyCustomerJob;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerFile;
use App\Models\CustomerNote;
use App\Service\Customer\CustomerFileService;
use App\Service\Customer\CustomerService;
use Tests\TestCase;

class DestroyCustomerTest extends TestCase
{
    public function test_job()
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
