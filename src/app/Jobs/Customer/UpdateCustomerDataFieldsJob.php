<?php

namespace App\Jobs\Customer;

use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use App\Models\DataField;
use App\Models\EquipmentType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateCustomerDataFieldsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected EquipmentType $equipment)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Updating Customer Data Fields for Customers with '.
            $this->equipment->name);

        $equipDataFields = DataField::where('equip_id', $this->equipment->equip_id)
            ->get()
            ->pluck('field_id');
        $customerList = CustomerEquipment::where('equip_id', $this->equipment->equip_id)
            ->get();

        // Cycle through customer list and see if any are missing, or need to add a field
        foreach ($customerList as $customer) {
            Log::debug('Checking customer equipment for Customer Equipment ID '.$customer->cust_equip_id);

            // Get the customers existing Data Fields
            $dataFields = CustomerEquipmentData::where('cust_equip_id', $customer->cust_equip_id)
                ->get()
                ->pluck('field_id');

            $fieldsToAdd = $equipDataFields->diff($dataFields);

            foreach ($fieldsToAdd as $fieldId) {
                CustomerEquipmentData::create([
                    'cust_equip_id' => $customer->cust_equip_id,
                    'field_id' => $fieldId,
                    'value' => null,
                ]);
            }

        }
    }
}
