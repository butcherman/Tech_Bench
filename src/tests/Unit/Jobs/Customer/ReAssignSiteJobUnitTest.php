<?php

namespace Tests\Unit\Jobs\Customer;

use App\Actions\Customer\ReAssignCustomerSite;
use App\Jobs\Customer\ReAssignSiteJob;
use App\Models\Customer;
use App\Models\CustomerSite;
use Mockery\MockInterface;
use Tests\TestCase;

class ReAssignSiteJobUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $fromCustomer = Customer::factory()->create();
        $toCustomer = Customer::factory()->create();

        $this->mock(ReAssignCustomerSite::class, function (MockInterface $mock) {
            $mock->shouldReceive('__invoke')
                ->with(CustomerSite::class, Customer::class)->once();
        });

        ReAssignSiteJob::dispatch(
            $fromCustomer->CustomerSite[0]->cust_site_id,
            $toCustomer->cust_id
        );
    }
}
