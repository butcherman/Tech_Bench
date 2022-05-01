<?php

namespace App\Jobs;

use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateCustomerDataFieldsJob implements ShouldQueue
{
    use Queueable;
    use Dispatchable;
    use SerializesModels;
    use InteractsWithQueue;

    protected $equipId;
    protected $fieldList;

    /**
     * Cycle through all customers with the attached Equip_id and update their data fields
     * to add any new fields that were recently added
     */
    public function __construct($equipId, $fieldList)
    {
        $this->equipId   = $equipId;
        $this->fieldList = $fieldList;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        Log::info('Adding Updated Customer Data Fields to Customers Equipment');
        //  Get the list of customers with the equipment
        $customerList = CustomerEquipment::where('equip_id', $this->equipId)->get();

        foreach($customerList as $cust)
        {
            Log::debug('Checking Customer Equipment for Customer Equipment ID '.$cust->cust_equip_id);
            //  Get the customers data fields
            $dataFields = CustomerEquipmentData::where('cust_equip_id', $cust->cust_equip_id)->get()->pluck('field_id');
            $difference = array_diff($this->fieldList, $dataFields->toArray());

            //  For each difference in the arrays, add the missing data field to the customer equipment type
            foreach($difference as $dif)
            {
                CustomerEquipmentData::create([
                    'cust_equip_id' => $cust->cust_equip_id,
                    'field_id'      => $dif,
                    'value'         => null,
                ]);

                Log::debug('Added New Field ID '.$dif.' to Customer Equipment ID '.$this->equipId);
            }
        }
    }
}
