<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cust_id'   => 'nullable|numeric|unique:customers,cust_id',
            'parent_id' => 'nullable|numeric|exists:customers,cust_id|different:cust_id',
            'name'      => 'required',
            'dba_name'  => 'nullable',
            'address'   => 'required',
            'city'      => 'required',
            'zip'       => 'required|numeric'
        ];
    }
}
