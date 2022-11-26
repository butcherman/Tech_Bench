<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Customer;

class CustomerIdRequest extends FormRequest
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
            'cust_id' => 'required|numeric|unique:customers',
        ];
    }
}
