<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CustomerContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cust_id' => 'required|exists:customers,cust_id',
            'name'    => 'required|string',
            'email'   => 'nullable|email',
            'shared'  => 'required|boolean',
            'customer_contact_phones' => 'nullable|array',
            'customer_contact_phones.phone_type_id' => 'exits:phone_number_types,phone_type_id',
        ];
    }
}
