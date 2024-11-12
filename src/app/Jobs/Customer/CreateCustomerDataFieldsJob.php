<?php

namespace App\Jobs\Customer;

use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateCustomerDataFieldsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected CustomerEquipment $equipment) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerEquipmentDataService $svc): void
    {
        $svc->createEquipmentDataFields($this->equipment);
    }
}
