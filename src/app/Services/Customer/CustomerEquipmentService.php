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
        $newEquip = new CustomerEquipment([
            'equip_id' => $requestData->get('equip_id'),
        ]);

        $customer->Equipment()->save($newEquip);

        $this->updateEquipmentSites($requestData, $newEquip);

        CreateCustomerDataFieldsJob::dispatch($newEquip);

        return $newEquip;
    }

    /**
     * Update the sites attached to a piece of equipment
     */
    public function updateEquipmentSites(
        Collection $requestData,
        CustomerEquipment $equipment
    ): void {
        $equipment->Sites()->sync($requestData->get('site_list'));
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
    // public function restoreEquipment(CustomerEquipment $equipment): void
    // {
    //     $equipment->restore();
    // }
}
