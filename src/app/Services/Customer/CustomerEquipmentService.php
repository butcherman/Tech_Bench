<?php

namespace App\Services\Customer;

use App\Jobs\Customer\CreateCustomerDataFieldsJob;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Support\Collection;

class CustomerEquipmentService
{
    /**
     * Store a new piece of equipment for customer
     */
    public function createEquipment(
        Collection $requestData,
        Customer $customer
    ): CustomerEquipment {
        $newEquip = new CustomerEquipment(
            $requestData->only('equip_id')->toArray()
        );

        $customer->CustomerEquipment()->save($newEquip);

        $this->updateEquipmentSites($requestData, $newEquip);

        dispatch(new CreateCustomerDataFieldsJob($newEquip));

        return $newEquip;
    }

    /**
     * Update the sites attached to a piece of equipment
     */
    public function updateEquipmentSites(
        Collection $requestData,
        CustomerEquipment $equipment
    ): void {
        $equipment->CustomerSite()->sync($requestData->get('site_list'));
    }

    /**
     * Delete a piece of equipment
     */
    public function destroyEquipment(
        CustomerEquipment $equipment,
        ?bool $force = false
    ): void {
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