<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class CustomerContactRequest extends FormRequest
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
            'cust_id' => 'required|exists:customers',
            'name'    => 'required|string',
            'email'   => 'nullable|email',
            'shared'  => 'required|boolean',
            'phones'  => 'array',
        ];
    }
}
