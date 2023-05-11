<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerEquipmentData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CustomerEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        if ($this->equipment) {
            return $this->user()->can('update', $this->equipment);
        }

        return $this->user()->can('create', CustomerEquipment::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'cust_id' => Rule::requiredIf(fn () => ! $this->equipment),
            'equip_id' => Rule::requiredIf(fn () => ! $this->equipment),
            'shared' => 'nullable|boolean',
        ];
    }

    /**
     * Build the individual data fields for the equipment
     */
    public function buildEquipData(CustomerEquipment $equipment)
    {
        $this->updateShared($equipment);
        $equipData = $this->except(['shared', 'type', 'cust_id', 'equip_id']);

        foreach ($equipData as $key => $value) {
            $equipId = str_replace('fieldId-', '', $key);
            CustomerEquipmentData::create([
                'cust_equip_id' => $equipment->cust_equip_id,
                'field_id' => $equipId,
                'value' => $value,
            ]);
            Log::stack(['daily', 'cust'])->debug('Customer Equip Field ID '.$equipId.' created as '.$value);
        }

        Log::stack(['daily', 'cust'])->info('Customer Equipment Data for Customer ID '.
                                            $equipment->cust_id.' and Equipment ID '.
                                            $equipment->cust_equip_id.' created by '.
                                            $this->user()->username, $this->toArray());
    }

    /**
     * Update the individual data fields for the equipment
     */
    public function updateEquipData(CustomerEquipment $equipment)
    {
        $this->updateShared($equipment);
        $equipData = $this->except(['shared']);

        foreach ($equipData as $key => $value) {
            $equipId = str_replace('fieldId-', '', $key);
            $equipField = CustomerEquipmentData::find($equipId);
            $equipField->update(['value' => $value]);
            Log::stack(['daily', 'cust'])->debug('Customer Equip Field ID '.$equipId.' updated to '.$value);
        }

        Log::stack(['daily', 'cust'])->info('Customer Equipment Data for Customer ID '.
                                            $equipment->cust_id.' and Equipment ID '.
                                            $equipment->cust_equip_id.' updated by '.
                                            $this->user()->username, $this->toArray());
    }

    /**
     * Update the shared data field
     */
    protected function updateShared($equipment)
    {
        $equipment->update($this->only(['shared']));
        //  Determine if the equipment should be moved to the parent site
        if ($this->shared) {
            $cust = Customer::find($equipment->cust_id);
            if ($cust->parent_id) {
                $equipment->update(['cust_id' => $cust->parent_id]);
            }
        }
    }
}
