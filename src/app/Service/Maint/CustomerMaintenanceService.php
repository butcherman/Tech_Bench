<?php

namespace App\Service\Maint;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\EquipmentType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Helper\ProgressBar;

class CustomerMaintenanceService
{
    public function __construct(protected bool $fix = false)
    {
        //
    }

    /**
     * Cycle through all customers and make sure that they have at least one child site
     */
    public function verifyCustomerChildren(ProgressBar $progressBar)
    {
        Log::debug('Verifying all Customers have at least one site');

        $failed = [];
        $customerList = Customer::withTrashed()->get();
        foreach ($customerList as $customer) {
            if ($customer->site_count === 0) {
                Log::debug('Customer ' . $customer->name . ' has no sites attached');
                $failed[] = $customer->only(['cust_id', 'name']);

                if ($this->fix) {
                    Log::notice('Deleting Customer without any attached sites', $customer->toArray());
                    $customer->forceDelete();
                }
            }

            $progressBar->advance();
        }

        return $failed;
    }

    /**
     * Cycle through all equipment and customers to validate all Equipment Data 
     * Fields exist
     */
    public function verifyEquipmentDataFields(ProgressBar $progressBar)
    {
        Log::debug('Verifying Customer Equipment Data Fields for all equipment');
        $equipList = EquipmentType::all();
        $missingData = [];

        foreach ($equipList as $equip) {
            Log::debug('Verifying Customer Equipment Data Fields for ' . $equip->name);
            $missing = $this->verifyCustomerData($equip);
            if (count($missing) > 0) {
                $missingData = array_merge($missingData, $missing);
            }
            $progressBar->advance();
        }

        return $missingData;
    }

    /**
     * Cycle through all customers with equipment and verify data fields
     */
    public function verifyCustomerData(EquipmentType $equip)
    {
        $missingData = [];
        $equipDataFieldIdList = $this->getEquipDataFieldIds($equip);
        $customerEquipList = $this->getCustomerWithEquip($equip);

        foreach ($customerEquipList as $custEquip) {
            $dataFields = $custEquip->CustomerEquipmentData->pluck('field_id');
            $missingFields = $equipDataFieldIdList->diff($dataFields);

            if (count($missingFields) > 0) {
                $missingData[] = [
                    'cust_equip_id' => $custEquip->cust_equip_id,
                    'missing_field_id' => $missingFields->flatten()
                ];

                if ($this->fix) {
                    $this->fixMissingField($custEquip, $missingFields);
                }
            }
        }

        return $missingData;
    }

    protected function fixMissingField(CustomerEquipment $custEquip, Collection $fieldIdList)
    {
        foreach ($fieldIdList as $missingId) {
            $equipData = CustomerEquipmentData::create([
                'cust_equip_id' => $custEquip->cust_equip_id,
                'field_id' => $missingId,
            ]);

            Log::notice('Added Missing Customer Equipment Field ID', $equipData->toArray());
        }
    }

    /**
     * Return a list of Field IDs for the selected equipment
     */
    protected function getEquipDataFieldIds(EquipmentType $equip)
    {
        return DataField::where('equip_id', $equip->equip_id)->get()->pluck('field_id');
    }

    /**
     * Return a list of customer equipment with the specified equipment
     */
    protected function getCustomerWithEquip(EquipmentType $equip)
    {
        return CustomerEquipment::where('equip_id', $equip->equip_id)->get();
    }
}