<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class NewCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('hasAccess', 'Add Customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cust_id'     => 'nullable|numeric|unique:customers,cust_id',
            'parent_id'   => 'nullable|numeric|exists:customers,cust_id|different:cust_id',
            'parent_name' => 'nullable|string',
            'name'        => 'required|string',
            'dba_name'    => 'nullable|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'zip'         => 'required|numeric',
            'state'       => 'required',
        ];
    }
}
