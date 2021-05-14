<?php

namespace App\Http\Requests\Customers;

use App\Models\CustomerEquipment;
use Illuminate\Foundation\Http\FormRequest;

class CustomerEquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->route('equipment'))
        {
            return $this->user()->can('update', CustomerEquipment::find($this->route('equipment')));
        }

        return $this->user()->can('create', CustomerEquipment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cust_id'  => 'required|exists:customers',
            'equip_id' => 'required|exists:equipment_types',
            'data'     => 'required|array',
            'shared'   => 'required|boolean',
        ];
    }
}
