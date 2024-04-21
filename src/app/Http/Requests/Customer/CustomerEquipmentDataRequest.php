<?php

namespace App\Http\Requests\Customer;

use App\Models\CustomerEquipmentData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CustomerEquipmentDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'saveData' => 'array|required',
        ];
    }

    /**
     * Save each of the fields that were modified
     */
    public function processDataChanges()
    {
        foreach ($this->saveData as $data) {
            $newData = CustomerEquipmentData::find($data['fieldId']);
            Log::channel('cust')->info('Customer Data for '.$this->customer->name.
                ' has been updated by '.$this->user()->username, [
                    'cust_id' => $this->customer->cust_id,
                    'data-field-id' => $newData->id,
                    'old-value' => $newData->value,
                    'new-value' => $data['value'],
                ]);

            $newData->update(['value' => $data['value']]);
        }
    }
}
