<?php

namespace App\Http\Requests\Equipment;

use App\Models\DataField;
use App\Models\DataFieldType;
use App\Models\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class EquipmentTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->equipment) {
            return $this->user()->can('update', $this->equipment);
        }

        return $this->user()->can('create', EquipmentType::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'cat_id' => 'required|exists:equipment_categories',
            'name' => [
                'required',
                Rule::unique('equipment_types')->ignore($this->equipment),
            ],
            'custData' => 'required|array',
        ];
    }

    /**
     * Process the Customer Equipment Entries, add or remove any necessary
     */
    public function processCustomerFields(EquipmentType $equipment)
    {
        $order = 0;
        $fieldList = [];
        $existingFields = DataField::where('equip_id', $equipment->equip_id)
            ->pluck('field_id')
            ->toArray();

        //  Cycle through all submitted fields
        foreach ($this->custData as $field) {
            //  Only accept fields that have a value
            if ($field !== null && $field !== '') {
                $fieldObj = $this->getFieldObj($field);
                $dataFieldObj = $this->updateDataField($equipment->equip_id, $fieldObj->type_id, $order);

                //  Put together a full list of the data fields to compare to customer fields later
                $fieldList[] = $dataFieldObj->field_id;

                //  Remove the updated field from the existing fields list
                $index = array_search($dataFieldObj->field_id, $existingFields);

                if ($index !== false) {
                    unset($existingFields[$index]);
                }

                $order++;
            }
        }

        //  The remaining fields are removed from the database
        $this->removeExtraFields($equipment->equip_id, $existingFields);
    }

    /**
     * Determine if this is a new or existing field
     */
    protected function getFieldObj(string $fieldName)
    {
        return DataFieldType::firstOrCreate(['name' => $fieldName]);
    }

    /**
     * Create or update the data field in the DB
     */
    protected function updateDataField(int $equipId, int $fieldTypeId, int $order)
    {
        return DataField::updateOrCreate(
            [
                'equip_id' => $equipId,
                'type_id' => $fieldTypeId,
            ],
            [
                'order' => $order,
            ]
        );
    }

    /**
     * Remove a list of data fields
     */
    protected function removeExtraFields(int $equipId, array $delArray)
    {
        foreach ($delArray as $fieldId) {
            $dataFieldObj = DataField::find($fieldId);
            if ($dataFieldObj) {

                Log::notice('Data Field ID ' . $fieldId . ' was deleted for Equipment ID ' .
                    $equipId . ' by ' . $this->user()->username, $dataFieldObj->toArray());
                $dataFieldObj->delete();
            }
        }
    }
}
