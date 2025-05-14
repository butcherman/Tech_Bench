<?php

namespace App\Jobs\Customer;

use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| Job is triggered when a new piece of equipment is added to a customer profile
|-------------------------------------------------------------------------------
*/

class CreateCustomerDataFieldsJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected CustomerEquipment $equipment) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerEquipmentDataService $svc): void
    {
        Log::info(
            'Starting Job - Create Customer Equipment Data Fields',
            $this->equipment->toArray()
        );

        $svc->createCustomerEquipmentDataFields($this->equipment);
    }
}
