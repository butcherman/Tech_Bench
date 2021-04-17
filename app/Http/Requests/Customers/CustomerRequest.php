<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        if($this->route('customer'))
        {
            return $this->user()->can('update', Customer::find($this->route('customer')));
        }

        return $this->user()->can('create', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'cust_id'     => 'nullable|numeric|unique:customers',
            'parent_id'   => 'nullable|numeric|exists:customers,cust_id',
            'parent_name' => 'nullable',
            'name'        => 'required|string',
            'dba_name'    => 'nullable|string',
            'address'     => 'required',
            'city'        => 'required|string',
            'state'       => 'required|string',
            'zip'         => 'required|numeric',
        ];
    }
}
