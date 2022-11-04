<?php

namespace App\Actions;

use App\Jobs\UpdateCustomerDataFieldsJob;
use App\Models\DataField;
use App\Models\DataFieldType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderEquipDataTypes
{
    /**
     * Cycle through a list of data field types (for customers) to see what options have been added
     * to an equipment type and what options have been removed
     */
    public function build($dataList, $equipId)
    {
        $order          = 0;
        $fieldList      = [];
        $existingFields = DataField::where('equip_id', $equipId)->get()->pluck('field_id')->toArray();

        //  Cycle through all submitted fields
        foreach($dataList as $field)
        {
            //  Only accept fields that have a value
            if($field !== null)
            {
                $fieldObj     = $this->getFieldObj($field);
                $dataFieldObj = $this->updateDataField($equipId, $fieldObj->type_id, $order);

                //  Put together a full list of the data fields to compare to customer fields later
                $fieldList[] = $dataFieldObj->field_id;

                //  Remove the updated field from the existing fields list
                $index = array_search($dataFieldObj->field_id, $existingFields);

                if($index !== false)
                {
                    unset($existingFields[$index]);
                }

                $order++;
            }
        }

        //  The remaining fields are removed from the database
        $this->removeExtraFields($equipId, $existingFields);

        //  Dispatch a new job to update all customers to add any missing fields
        UpdateCustomerDataFieldsJob::dispatch($equipId, $fieldList);
    }

    /**
     * Determine if this is a new or existing field
     */
    protected function getFieldObj($fieldName)
    {
        $fieldObj = DataFieldType::where('name', $fieldName)->first();
        if(!$fieldObj)
        {
            $fieldObj = DataFieldType::create(['name' => $fieldName]);
        }

        return $fieldObj;
    }

    /**
     * Create or update the data field in the DB
     */
    protected function updateDataField($equipId, $fieldTypeId, $order)
    {
        $dataField = DataField::updateOrCreate(
        [
            'equip_id' => $equipId,
            'type_id'  => $fieldTypeId,
        ],
        [
            'order'    => $order,
        ]);

        return $dataField;
    }

    /**
     * Remove a list of data fields
     */
    protected function removeExtraFields($equipId, $delArray)
    {
        foreach($delArray as $fieldId)
        {
            $dataFieldObj = DataField::find($fieldId);
            Log::notice('Data Field ID '.$fieldId.' was deleted for Equipment ID '.$equipId.' by '.Auth::user()->username, $dataFieldObj->toArray());
            $dataFieldObj->delete();
        }
    }
}
