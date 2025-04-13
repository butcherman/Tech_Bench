<?php

namespace App\Jobs\Customer;

use App\Models\EquipmentType;
use App\Services\Customer\CustomerEquipmentDataService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/*
|-------------------------------------------------------------------------------
| Job is triggered after Equipment Data has been updated for a selected piece
| of equipment.  Job will cycle through all customer with this equipment to
| verify that any new fields are added, and any removed fields are deleted.
|-------------------------------------------------------------------------------
*/

class UpdateCustomerDataFieldsJob implements ShouldQueue
{
    use Queueable;

    /**
     * @codeCoverageIgnore
     */
    public function __construct(protected EquipmentType $equipment) {}

    /**
     * Execute the job.
     */
    public function handle(CustomerEquipmentDataService $svc): void
    {
        $svc->updateEquipmentDataFieldsForEquipment($this->equipment);
    }
}
