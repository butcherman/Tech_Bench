<?php

namespace Tests\Unit\Jobs\Customer;

use App\Jobs\Customer\ForceDeleteCustomerJob;
use App\Models\Customer;
use App\Models\CustomerFile;
use App\Services\Customer\CustomerAdministrationService;
use App\Services\File\FileUploadService;
use Mockery\MockInterface;
use Tests\TestCase;

class ForceDeleteCustomerJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $testCustomer = Customer::factory()->create();
        CustomerFile::factory()
            ->count(5)
            ->create(['cust_id' => $testCustomer->cust_id]);

        $this->mock(CustomerAdministrationService::class, function (MockInterface $mock) {
            $mock->shouldReceive('destroyCustomerSites')->once()->with(Customer::class);
            $mock->shouldReceive('destroyCustomer')->once()->with(Customer::class, 'Force Deleting Customer', true);
        });

        $this->mock(FileUploadService::class, function (MockInterface $mock) {
            $mock->shouldReceive('deleteFileById')->once();
        });

        ForceDeleteCustomerJob::dispatch($testCustomer);
    }
}
