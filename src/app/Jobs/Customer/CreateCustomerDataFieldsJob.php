<?php

namespace App\Jobs\Customer;

use App\Models\CustomerEquipment;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateCustomerDataFieldsJob implements ShouldQueue
{
    use Queueable;

    /*
    |---------------------------------------------------------------------------
    | When Equipment is added to a customer, create the data fields to allow
    | the user to add information about the equipment.
    |---------------------------------------------------------------------------
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
