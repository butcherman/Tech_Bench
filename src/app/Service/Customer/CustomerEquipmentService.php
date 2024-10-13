<?php

namespace App\Service\Customer;

use App\Http\Requests\Customer\CustomerEquipmentRequest;
use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentService
{
    /**
     * Store a new piece of equipment for customer
     */
    public function createEquipment(
        CustomerEquipmentRequest $requestData,
        Customer $customer
    ): CustomerEquipment {
        $newEquip = new CustomerEquipment($requestData->only('equip_id'));
        $customer->CustomerEquipment()->save($newEquip);

        $this->updateEquipmentSites($requestData, $newEquip);

        dispatch(new CreateCustomerDataFieldsJob($newEquip));

        return $newEquip;
    }

    /**
     * Update the sites attached to a piece of equipment
     */
    public function updateEquipmentSites(
        CustomerEquipmentRequest $requestData,
        CustomerEquipment $equipment
    ): void {
        $equipment->CustomerSite()->sync($requestData->site_list);

        Log::info('Customer Sites updated for Customer Equipment by '.
            $requestData->user()->username, $equipment->toArray());
    }

    /**
     * Delete a piece of equipment
     */
    public function destroyEquipment(CustomerEquipment $equipment, ?bool $force = false): void
    {
        if ($force) {
            $equipment->forceDelete();

            return;
        }

        $equipment->delete();
    }

    /**
     * Restore equipment
     */
    public function restoreEquipment(CustomerEquipment $equipment): void
    {
        $equipment->restore();
    }
}
