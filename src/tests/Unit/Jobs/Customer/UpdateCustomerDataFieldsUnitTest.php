<?php

namespace Tests\Unit\Jobs\Customer;

use App\Jobs\Customer\UpdateCustomerDataFieldsJob;
use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentDataService;
use Mockery\MockInterface;
use Tests\TestCase;

class UpdateCustomerDataFieldsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | handle()
    |---------------------------------------------------------------------------
    */
    public function test_handle(): void
    {
        $testEquip = EquipmentType::factory()->create();

        $this->mock(CustomerEquipmentDataService::class, function (MockInterface $mock) use ($testEquip) {
            $mock->shouldReceive('updateEquipmentDataFieldsForEquipment')
                ->once()
                ->with(EquipmentType::class);
        });

        UpdateCustomerDataFieldsJob::dispatch($testEquip);
    }
}
