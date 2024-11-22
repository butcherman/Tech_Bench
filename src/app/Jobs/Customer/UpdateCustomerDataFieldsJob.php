<?php

namespace App\Jobs\Customer;

use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateCustomerDataFieldsJob implements ShouldQueue
{
    use Queueable;

    /*
    |---------------------------------------------------------------------------
    | Add or remove any data fields needed for Customer Equipment.
    |---------------------------------------------------------------------------
    */
    public function __construct(protected EquipmentType $equipment) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerEquipmentDataService $svc): void
    {
        $svc->updateEquipmentDataFields($this->equipment);
    }
}
