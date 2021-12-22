<?php

namespace App\Http\Requests\Customers;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class LinkCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize()
    {
        return $this->user()->can('manage', Customer::class);
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules()
    {
        return [
            'cust_id'   => 'required|exists:customers',
            'parent_id' => 'nullable|exists:customers,cust_id',
            'add'       => 'required|boolean',
        ];
    }
}
