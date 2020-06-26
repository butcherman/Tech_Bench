<?php

namespace App\Domains\Equipment;

use App\SystemDataFields;
use App\CustomerSystemData;
use App\SystemDataFieldTypes;

use Illuminate\Support\Facades\Log;

class SetEquipmentDataFields extends GetEquipDataFields
{
    protected $sysID, $fields;

    //  Create a new field for customer data to be gathered for that equipment
    public function createEquipmentFields($fieldArr, $sysID)
    {
        $this->sysID = $sysID;
        $this->fields = collect($fieldArr);

        $this->findNewFields();
        $this->processFields();

        return true;
    }

    //  Update an existing field
    public function updateEquipFields($fieldArr, $sysID)
    {
        $this->sysID = $sysID;
        $this->fields = collect($fieldArr);

        $removedList = $this->findRemovedFields();
        //  Loop will check to see if any customers have a value filled for this field type.
        foreach($removedList as $rem)
        {
            $inUse = $this->isFieldInUse($rem->field_id);
            if(!$inUse->isEmpty())
            {
                //  Will fail on first error
                return $inUse->toArray();
            }
        }
        //  Remove the fields if no errors
        $removeSuccess = $this->removeFields($removedList);
        $this->findNewFields();
        $this->processFields();

        return $removeSuccess;
    }

    //  Update the equipemnt fields
    protected function processFields()
    {
        foreach($this->fields as $index => $field)
        {
            SystemDataFields::firstOrCreate(
                [
                    'sys_id'       => $this->sysID,
                    'data_type_id' => $field['data_type_id'],
                ],
                [
                    'sys_id'       => $this->sysID,
                    'data_type_id' => $field['data_type_id'],
                    'order'        => $index,
                ]
            )->update([
                'sys_id'       => $this->sysID,
                'data_type_id' => $field['data_type_id'],
                'order'        => $index,
            ]);
        }
    }

    //  Find any fields that have been removed from the list
    protected function findRemovedFields()
    {
        $curFields = $this->getFieldsForEquip($this->sysID);    //SystemDataFields::where('sys_id', $this->sysID)->get();

        $removedList = [];
        foreach($curFields as $field)
        {
            if(!$this->fields->contains('field_id', $field->field_id))
            {
                $removedList[] = $field;
            }
        }

        return $removedList;
    }

    //  Determine if any customers have the field populated
    protected function isFieldInUse($fieldID)
    {
        $data = CustomerSystemData::where('field_id', $fieldID)->get();

        return $data;
    }

    //  Remove the fields from the equipment
    protected function removeFields($remArr)
    {
        foreach($remArr as $rem)
        {
            SystemDataFields::find($rem->field_id)->delete();
        }

        return true;
    }

    //  Find any new fields that have been added to the array
    protected function findNewFields()
    {
        foreach($this->fields as $key => $field)
        {
            if(!is_array($field))
            {
                $dataID = $this->processNewField($field);
                $this->fields[$key] = [
                    'data_type_id' => $dataID,
                    'sys_id' => $this->sysID,
                    'data_field_name' => $field,
                ];
            }
        }
    }

    //  Determine if a field is actually new, or already exists in the database
    public function processNewField($field, $hidden = false)
    {
        $fieldData = SystemDataFieldTypes::where('name', $field)->first();

        if(!$fieldData)
        {
            $dataID = SystemDataFieldTypes::create(['name' => $field, 'hidden' => $hidden])->data_type_id;
        }
        else
        {
            $dataID = $fieldData->data_type_id;
        }

        return $dataID;
    }

    //  Update an existing field
    public function editExistingField($fieldID, $name, $hidden)
    {
        Log::emergency('data type id - '.$fieldID);
        SystemDataFieldTypes::find($fieldID)->update(['name' => $name, 'hidden' => $hidden]);
        return true;
    }

    //  Delete a field - note cannot delete the field if it is in use
    public function deleteField($fieldID)
    {
        try
        {
            SystemDataFieldTypes::find($fieldID)->delete();
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            Log::emergency('Tried to delete a field that is still in use.  Field ID - '.$fieldID);
            return false;
        }

        return true;
    }
}
