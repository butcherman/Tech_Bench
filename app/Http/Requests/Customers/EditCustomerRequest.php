<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class EditCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('update', Customer::find($this->route('customer')));
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'name'        => 'required|string',
            'dba_name'    => 'nullable|string',
            'address'     => 'required',
            'city'        => 'required|string',
            'state'       => 'required|string',
            'zip'         => 'required|numeric',
        ];
    }
}
